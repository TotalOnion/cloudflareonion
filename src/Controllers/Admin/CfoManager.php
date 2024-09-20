<?php
namespace GlobalCfo\Controllers\Admin;

use GlobalCfo\Controllers\AbstractController;
use GlobalCfo\Controllers\Admin\Logger;

class CfoManager extends AbstractController
{
    private Logger $logger;

    public function __construct($pluginName, $version)
    {
        $this->logger = new Logger(GLOBAL_CFO_VERSION, GLOBAL_CFO_NAME);
        parent::__construct($pluginName, $version);
    }

    public function registerSave($postID)
    {
        if (wp_is_post_revision($postID) || wp_is_post_autosave($postID) || !$this->getCFEnabled()) {
            return;
        }

        $postUrl = get_permalink($postID);
        $this->sendPurgeRequest($postUrl);

        if ($this->getTrailingSlashOption()) {
            $this->sendPurgeRequest(rtrim($postUrl, '/'));
        }
    }

    private function sendPurgeRequest($postUrl): void
    {
        $endpointUrl = $this->getPurgeEndpoint();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAPIKey(),
        ];
        $body = json_encode([
            'files' => [$postUrl]
        ]);
        $this->logger->logToAws('Purging cache for ' . $postUrl);
        $response = wp_remote_post($endpointUrl, [
            'headers' => $headers,
            'body' => $body,
            'method' => 'POST',
            'data_format' => 'body',
        ]);
        $this->logger->logToAws($response['body']);
    }

    private function getAPIKey(): string
    {
        return cfoDecryptInput(get_option(GLOBAL_CFO_NAME.'_tokenCF'));
    }

    private function getZoneID(): string
    {
        return get_option(GLOBAL_CFO_NAME.'_zoneID');
    }

    private function getCFEnabled(): string
    {
        return get_option(GLOBAL_CFO_NAME.'_enableCF');
    }

    private function getTrailingSlashOption(): string
    {
        return get_option(GLOBAL_CFO_NAME.'_purgeNoTrailingSlash');
    }

    private function getPurgeEndpoint(): string
    {
       return 'https://api.cloudflare.com/client/v4/zones/' . $this->getZoneID() . '/purge_cache';
    }
}
