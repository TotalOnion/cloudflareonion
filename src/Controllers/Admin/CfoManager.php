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
        $this->logger->logToAws('a save has been triggered');
        if (wp_is_post_revision($postID) || wp_is_post_autosave($postID) || !$this->getCFEnabled()) {
            return;
        }
        $this->purgeCache($postID);
    }

    private function purgeCache($postID): void
    {
        $url = 'https://api.cloudflare.com/client/v4/zones/' . $this->getZoneID() . '/purge_cache';
        $postUrl = get_permalink($postID);
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAPIKey(),
        ];
        // $body = json_encode(['tags' => $cacheTags]);
        $body = json_encode([
            'files' => [$postUrl]
        ]);
        $response = wp_remote_post($url, [
            'headers' => $headers,
            'body' => $body,
            'method' => 'POST',
            'data_format' => 'body',
        ]);
        $this->logger->logToAws('Purging cache for ' . $postUrl );
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
}
