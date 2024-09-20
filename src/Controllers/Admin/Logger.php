<?php

namespace GlobalCfo\Controllers\Admin;

use GlobalCfo\Controllers\AbstractController;

class Logger extends AbstractController
{
    private string $amzDate;
    private string $dateStamp;
    private ?string $accessKey;
    private ?string $secretKey;
    private ?string $region;
    private ?string $host;
    private ?string $endpoint;
    private ?string $logGroup;
    private ?bool $loggingEnabled;

    public function __construct()
    {
        $this->setDates();
        $this->setAccessKey();
        $this->setSecretKey();
        $this->setRegion();
        $this->setHost();
        $this->setEndpoint();
        $this->setLogGroup();
        $this->setLoggingEnabled();
    }

    public function logToAws($logLine): void
    {
        if ( !$this->getLoggingEnabled() ) {
            return;
        }

        $requestParameters = json_encode(['logGroupName' => $this->logGroup]);

        $logStream = wp_parse_url(home_url())['host'];

        $requestParametersStream = json_encode([
            'logGroupName' => $this->logGroup,
            'logStreamName' => $logStream
        ]);

        $timestamp = round(microtime(true) * 1000);  // Current time in milliseconds
        $requestParametersEvent = json_encode([
            'logGroupName' => $this->logGroup,
            'logStreamName' => $logStream,
            'logEvents' => [
                [
                    'timestamp' => $timestamp,
                    'message' => $logLine
                ],
            ],
        ]);

        $responses = [];
        // Create log group
        $responses[] = $this->sendRequest('Logs_20140328.CreateLogGroup', $requestParameters);

        // Create log stream
        $responses[] = $this->sendRequest('Logs_20140328.CreateLogStream', $requestParametersStream);

        // Put log event
        $responses[] = $this->sendRequest('Logs_20140328.PutLogEvents', $requestParametersEvent);

    }

    private function sendRequest($amzTarget, $requestParameters): array
    {
        $authorizationHeader = $this->authorizationHeader($amzTarget, $requestParameters);

        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Date' => $this->amzDate,
            'X-Amz-Target' => $amzTarget,
            'Authorization' => $authorizationHeader
        ];
        return wp_remote_post($this->endpoint, [
            'headers' => $headers,
            'body' => $requestParameters,
            'method' => 'POST',
            'data_format' => 'body',
        ]);
    }

    private function sign($key, $msg): string
    {
        return hash_hmac('sha256', $msg, $key, true);
    }

    private function signatureKey($key, $dateStamp, $regionName, $serviceName): string
    {
        $k_date = $this->sign("AWS4$key", $dateStamp);
        $k_region = $this->sign($k_date, $regionName);
        $k_service = $this->sign($k_region, $serviceName);
        $k_signing = $this->sign($k_service, 'aws4_request');
        return $k_signing;
    }

    private function authorizationHeader($amzTarget, $requestParameters): string
    {
        $canonical_uri = '/';
        $canonical_querystring = '';
        $canonical_headers = "host:$this->host\nx-amz-date:$this->amzDate\nx-amz-target:$amzTarget\n";
        $signed_headers = 'host;x-amz-date;x-amz-target';
        $payload_hash = hash('sha256', $requestParameters);
        $canonical_request = "POST\n$canonical_uri\n$canonical_querystring\n$canonical_headers\n$signed_headers\n$payload_hash";

        $algorithm = 'AWS4-HMAC-SHA256';
        $credential_scope = "$this->dateStamp/$this->region/logs/aws4_request";
        $string_to_sign = "$algorithm\n$this->amzDate\n$credential_scope\n" . hash('sha256', $canonical_request);

        $signing_key = $this->signatureKey($this->secretKey, $this->dateStamp, $this->region, 'logs');
        $signature = hash_hmac('sha256', $string_to_sign, $signing_key);

        $authorizationHeader = "$algorithm Credential=$this->accessKey/$credential_scope, SignedHeaders=$signed_headers, Signature=$signature";
        return $authorizationHeader;
    }

    private function setDates(): void
    {
        $t = new \DateTime('UTC');
        $this->amzDate = $t->format('Ymd\THis\Z');
        $this->dateStamp = $t->format('Ymd');  //
    }

    private function setAccessKey(): void
    {
        $this->accessKey = cfoDecryptInput(get_option(GLOBAL_CFO_NAME.'_log_aws_access'));
    }

    private function setSecretKey(): void
    {
        $this->secretKey = cfoDecryptInput(get_option(GLOBAL_CFO_NAME.'_log_aws_secret'));
    }

    private function setRegion(): void
    {
        $this->region = get_option(GLOBAL_CFO_NAME.'_log_aws_region');
    }

    private function setLogGroup(): void
    {
        $this->logGroup = get_option(GLOBAL_CFO_NAME.'_log_aws_loggroup');
    }

    private function setHost(): void
    {
        $this->host = 'logs.' . $this->region . '.amazonaws.com';
    }

    private function setEndpoint(): void
    {
        $this->endpoint = 'https://' . $this->host;
    }

    private function setLoggingEnabled(): void
    {
        $this->loggingEnabled = get_option(GLOBAL_CFO_NAME.'_log_aws_enable');
    }

    private function getLoggingEnabled(): bool
    {
        return $this->loggingEnabled;
    }
}