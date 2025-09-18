<?php

namespace GlobalCfo\Controllers\Admin;

use GlobalCfo\Controllers\AbstractController;

class CacheTags extends AbstractController
{

    public function __construct()
    {
    }

    public function getPurgeBody($postID): string
    {
        $tags = $this->getTagsToPurge($postID);
        $body = json_encode([
            'tags' => $tags
        ]);
        return $body;
    }

    public function printCacheTagsHeader(): void
    {
        $postID = get_the_ID();
        $cacheTags = $this->getTagsToPrint($postID);
        $tagsString = implode(',', $cacheTags);
        header('Cache-Tag: ' . $tagsString);
    }

    private function getTagsToPrint($postID): array {
        $tags = [];
        $tags[] = $this->getIDTag($postID);
        return $tags;
    }

    private function getTagsToPurge($postID): array {
        $tags = [];
        $tags[] = $this->getIDTag($postID);
        $cptTag = $this->getCptTag($postID);
        if ($cptTag) {
            $tags[] = $this->getCptTag($postID);
        }
        return $tags;
    }

    private function getIDTag($postID): string {
        return 'cfo-' . $postID;
    }

    private function getCptTag($postID): string {
        $cptTag = '';
        $postType = get_post_type($postID);
        $cptTagsEncoded = get_option(GLOBAL_CFO_NAME.'_customCPTTags');
        if ($cptTagsEncoded) {
            $cptTags = json_decode($cptTagsEncoded);
            if ($postType && is_object($cptTags) && property_exists($cptTags, $postType)) {
                $cptTag = $cptTags->{$postType};
            }
        }
        return $cptTag;
    }
}