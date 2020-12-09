<?php

use RebelCode\Spotlight\Instagram\MediaStore\IgCachedMedia;
use RebelCode\Spotlight\Instagram\MediaStore\MediaStore;
use RebelCode\Spotlight\Instagram\PostTypes\FeedPostType;
use RebelCode\Spotlight\Instagram\Wp\PostType;

/**
 * The Spotlight Instagram Developer API.
 *
 * A simple class that exposes common functionality for developers, themes and other integrations.
 *
 * @since 0.3.2
 */
class SpotlightInstagram
{
    /**
     * @since 0.3.2
     *
     * @param int $feedId  The ID of the feed.
     * @param int $num     The number of media objects to return. Will return all media objects if less than or equal
     *                     to zero.
     * @param int $offset  The offset from which to begin returning media. Negative values will be treated as zero.
     *
     * @return IgCachedMedia[]
     */
    static function getFeedMedia(int $feedId, int $num = -1, int $offset = 0)
    {
        $plugin = spotlightInsta();

        /* @var $store MediaStore */
        /* @var $feeds PostType */
        $store = $plugin->get("media/store");
        $feeds = $plugin->get("feeds/cpt");

        $post = $feeds->get($feedId);

        if ($post instanceof WP_Post) {
            $feed = FeedPostType::fromWpPost($post);
            $media = $store->getFeedMedia($feed, $num, $offset);

            return $media[0];
        } else {
            return [];
        }
    }

    /**
     * @since 0.3.2
     *
     * @param int $feedId The ID of the feed.
     *
     * @return IgCachedMedia[]
     */
    static function getFeedStories(int $feedId)
    {
        $plugin = spotlightInsta();

        /* @var $store MediaStore */
        /* @var $feeds PostType */
        $store = $plugin->get("media/store");
        $feeds = $plugin->get("feeds/cpt");

        $feed = FeedPostType::fromWpPost($feeds->get($feedId));
        $media = $store->getFeedMedia($feed);

        return $media[1];
    }
}
