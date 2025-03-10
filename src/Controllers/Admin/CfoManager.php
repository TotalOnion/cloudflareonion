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

    public function registerSavedItem($url)
    {
        $this->sendPurgeRequest($this->getPurgeBodyUrl($url));

        if ($this->getTrailingSlashOption()) {
            $this->sendPurgeRequest($this->getPurgeBodyUrl(rtrim($url, '/')));
        }
    }

    public function registerSavedPost($postID)
    {
        if (wp_is_post_revision($postID) || wp_is_post_autosave($postID) || !$this->getCFEnabled()) {
            return;
        }

        $postType = get_post_type($postID);
        $postTypesToPurge = $this->getPostTypesOption();

        // Skipping purge for post types not in the list
        if (!in_array($postType, $postTypesToPurge)) {
            return;
        }

        $postUrl = get_permalink($postID);
        $this->registerSavedItem($postUrl);
    }

    public function purgeMarket($marketId)
    {
        $marketURL = cfoGetWPMLLanguageById($marketId)['url'];
        if($marketURL) {
            $marketURI = parse_url($marketURL, PHP_URL_HOST) . parse_url($marketURL, PHP_URL_PATH);
            $body = json_encode([
                'prefixes' => [$marketURI]
            ], JSON_UNESCAPED_SLASHES);
            return $this->sendPurgeRequest($body);
        }
    }

    private function sendPurgeRequest($body): string
    {
        $endpointUrl = $this->getPurgeEndpoint();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAPIKey(),
        ];
        $this->logger->logToAws('Purging cache with ' . $body);
        $response = wp_remote_post($endpointUrl, [
            'headers' => $headers,
            'body' => $body,
            'method' => 'POST',
            'data_format' => 'body',
        ]);
        $this->logger->logToAws($response['body']);
        return $response['body'];
    }

    private function getPurgeBodyUrl($url): string
    {
        $body = json_encode([
            'files' => [$url]
        ]);
        return $body;
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

    private function getPostTypesOption(): array
    {
        $postTypes = get_option(GLOBAL_CFO_NAME.'_purgePostTypes');
        return is_array($postTypes) ? $postTypes : [];
    }

    private function getPurgeEndpoint(): string
    {
       return 'https://api.cloudflare.com/client/v4/zones/' . $this->getZoneID() . '/purge_cache';
    }
}
