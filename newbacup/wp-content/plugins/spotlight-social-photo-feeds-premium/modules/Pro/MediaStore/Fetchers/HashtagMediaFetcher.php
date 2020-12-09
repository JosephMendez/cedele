<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Fetchers;

use RebelCode\Spotlight\Instagram\Feeds\Feed;
use RebelCode\Spotlight\Instagram\IgApi\IgMedia;
use RebelCode\Spotlight\Instagram\MediaStore\MediaFetcherInterface;
use RebelCode\Spotlight\Instagram\MediaStore\MediaStore;
use RebelCode\Spotlight\Instagram\Modules\Pro\IgApi\ProIgGraphApiClient;
use RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\ProMediaSource;
use RebelCode\Spotlight\Instagram\PostTypes\AccountPostType;
use RebelCode\Spotlight\Instagram\Wp\PostType;

/**
 * Fetches media that contain a specific hashtag, from all across Instagram.
 *
 * @since 0.1
 */
class HashtagMediaFetcher implements MediaFetcherInterface
{
    /**
     * @since 0.1
     *
     * @var ProIgGraphApiClient
     */
    protected $api;

    /**
     * @since 0.1
     *
     * @var PostType
     */
    protected $cpt;

    /**
     * Constructor.
     *
     * @since 0.1
     *
     * @param ProIgGraphApiClient $api The Instagram Graph API client.
     * @param PostType            $cpt The accounts CPT.
     */
    public function __construct(ProIgGraphApiClient $api, PostType $cpt)
    {
        $this->api = $api;
        $this->cpt = $cpt;
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function fetch(Feed $feed, MediaStore $store)
    {
        $hashtags = $feed->getOption('hashtags', []);
        if (empty($hashtags)) {
            return;
        }

        // Find a business account. Stop here if no business account was found
        $account = AccountPostType::findBusinessAccount($this->cpt);
        if ($account === null) {
            return;
        }

        foreach ($hashtags as $hashtag) {
            $tag = $hashtag['tag'] ?? '';
            if (empty($tag)) {
                continue;
            }

            $type = !isset($hashtag['sort']) || ($hashtag['sort'] === 'recent')
                ? ProIgGraphApiClient::RECENT_MEDIA
                : ProIgGraphApiClient::TOP_MEDIA;

            $hashtagId = $this->api->getHashtagId($tag, $account);

            $media = $this->api->getHashtagMedia($hashtagId, $type, $account);
            $media = array_filter($media, function(IgMedia $media) {
                return !empty($media->getUrl());
            });

            $source = ProMediaSource::forHashtag($tag, $type);

            $store->addMedia($media, $source);
        }
    }
}
