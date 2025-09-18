<?php
namespace GlobalCfo\Controllers\Admin;

use GlobalCfo\Controllers\AbstractController;
use GlobalCfo\Controllers\Admin\Logger;
use GlobalCfo\Controllers\Admin\CacheTags;

class CfoManager extends AbstractController
{
    private Logger $logger;
    private CacheTags $cacheTags;

    public function __construct($pluginName, $version)
    {
        $this->logger = new Logger($version, $pluginName);
        $this->cacheTags = new CacheTags($version, $pluginName);
        parent::__construct($pluginName, $version);
    }

    public function registerSavedItemURL($url)
    {
        $this->sendPurgeRequest($this->getPurgeBodyUrl($url));

        if ($this->getTrailingSlashOption()) {
            $this->sendPurgeRequest($this->getPurgeBodyUrl(rtrim($url, '/')));
        }
    }

    public function registerSavedItemCacheTags($postID)
    {
        $purgeBody = $this->cacheTags->getPurgeBody($postID);
        $this->sendPurgeRequest($purgeBody);
    }

    public function printCacheTagsHeader()
    {
        $this->cacheTags->printCacheTagsHeader();
    }

    public function registerSavedPost($postID)
    {
        if (wp_is_post_revision($postID) || wp_is_post_autosave($postID) || !$this->getCFEnabled()) {
            return;
        }

        $postType = get_post_type($postID);
        $postTypesToPurge = $this->getPostTypesOption();

        // Skipping purge for post types not in the list
        if ((empty($postTypesToPurge)) || (!empty($postTypesToPurge) && !in_array($postType, $postTypesToPurge))) {
            return;
        }

        $cacheTagsEnabled = $this->getCacheTagsEnabled();

        if ( $cacheTagsEnabled ) {
            $this->registerSavedItemCacheTags($postID);
        } else {
            $postUrl = get_permalink($postID);
            $this->registerSavedItemURL($postUrl);
        }
    }

    public function purgeMarket($marketId)
    {
        $marketURL = cfoGetWPMLLanguageById($marketId)['url'];
        if ($marketURL) {
            $body = $this->getPurgeBodyPrefix($marketURL);
            return $this->sendPurgeRequest($body);
        }
    }

    public function purgePrefix($prefix)
    {
        if ($prefix) {
            $fullURL = home_url() . $prefix;
            $body = $this->getPurgeBodyPrefix($fullURL);
            return $this->sendPurgeRequest($body);
        }
    }

    public function purgeMarkets()
    {
        if ( in_array( 'sitepress-multilingual-cms/sitepress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            $languages = apply_filters( 'wpml_active_languages', NULL );
            foreach ($languages as $language) {
                $this->purgeMarket($language['id']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function getDomainReplacedURL($url): string
    {
        $newDomain = $this->getDomainReplace();
        if ($newDomain) {
            if ($url){
                $parsedURL = parse_url($url);
                $url = $parsedURL['scheme']. '://' . $newDomain . $parsedURL['path'];
            }
        }
        return $url;
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
        $url = $this->getDomainReplacedURL($url);
        $body = json_encode([
            'files' => [$url]
        ]);
        return $body;
    }

    private function getPurgeBodyPrefix($url): string
    {
        $url = $this->getDomainReplacedURL($url);
        $uri = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
        $body = json_encode([
            'prefixes' => [$uri]
        ], JSON_UNESCAPED_SLASHES);
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

    protected function getDomainReplace(): string
    {
        return get_option(GLOBAL_CFO_NAME.'_replace_domain');
    }

    private function getCacheTagsEnabled(): string
    {
        return get_option(GLOBAL_CFO_NAME.'_enableCacheTags');
    }
}
