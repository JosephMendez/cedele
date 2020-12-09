<?php

namespace RebelCode\Spotlight\Instagram\Modules;

use Dhii\Services\Extensions\ArrayExtension;
use Dhii\Services\Factories\Constructor;
use Dhii\Services\Factories\ServiceList;
use Dhii\Services\Factories\Value;
use Psr\Container\ContainerInterface;
use RebelCode\Spotlight\Instagram\MediaStore\Fetchers\AccountMediaFetcher;
use RebelCode\Spotlight\Instagram\MediaStore\MediaStore;
use RebelCode\Spotlight\Instagram\MediaStore\Processors\MediaSorterProcessor;
use RebelCode\Spotlight\Instagram\Module;
use RebelCode\Spotlight\Instagram\PostTypes\MediaPostType;
use RebelCode\Spotlight\Instagram\Wp\MetaField;

/**
 * The module that adds the media post type and all related functionality to the plugin.
 *
 * @since 0.1
 */
class MediaModule extends Module
{
    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function getFactories() : array
    {
        return [
            //==========================================================================
            // POST TYPE
            //==========================================================================

            // The media CPT
            'cpt' => new Constructor(MediaPostType::class, [
                'cpt/slug',
                'cpt/args',
                'cpt/fields',
            ]),

            // The media CPT slug name
            'cpt/slug' => new Value('sl-insta-media'),
            // The media CPT registration args
            'cpt/args' => new Value([
                'public' => false,
                'supports' => ['title', 'custom-fields'],
                'show_in_rest' => false,
            ]),
            // The meta fields for the media CPT
            'cpt/fields' => new Value([
                new MetaField(MediaPostType::MEDIA_ID),
                new MetaField(MediaPostType::USERNAME),
                new MetaField(MediaPostType::TIMESTAMP),
                new MetaField(MediaPostType::CAPTION),
                new MetaField(MediaPostType::TYPE),
                new MetaField(MediaPostType::URL),
                new MetaField(MediaPostType::PERMALINK),
                new MetaField(MediaPostType::THUMBNAIL_URL),
                new MetaField(MediaPostType::LIKES_COUNT),
                new MetaField(MediaPostType::COMMENTS_COUNT),
                new MetaField(MediaPostType::CHILDREN),
                new MetaField(MediaPostType::LAST_REQUESTED),
            ]),

            //==========================================================================
            // MEDIA STORE
            //==========================================================================

            // The media store
            'store' => new Constructor(MediaStore::class, ['@wp/db', 'fetchers', 'processors', 'cpt']),

            // The media fetchers to use in the store
            'fetchers' => new ServiceList([
                'fetchers/accounts',
            ]),

            // The media processors to use in the store
            'processors' => new ServiceList([
                'processors/sorter',
            ]),

            //==========================================================================
            // FETCHERS
            //==========================================================================

            // The fetcher that gets media from accounts
            'fetchers/accounts' => new Constructor(AccountMediaFetcher::class, [
                '@ig/api/client',
                '@accounts/cpt',
            ]),

            //==========================================================================
            // PROCESSORS
            //==========================================================================

            // The processor that sorts the media
            'processors/sorter' => new Constructor(MediaSorterProcessor::class, []),
        ];
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function getExtensions() : array
    {
        return [
            // Add the post type to WordPress
            'wp/post_types' => new ArrayExtension(['cpt']),
        ];
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function run(ContainerInterface $c)
    {
    }
}
