<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro;

use Dhii\Services\Extension;
use Dhii\Services\Extensions\ArrayExtension;
use Dhii\Services\Factories\Constructor;
use Dhii\Services\Factories\ServiceList;
use Dhii\Services\Factories\Value;
use Psr\Container\ContainerInterface;
use RebelCode\Spotlight\Instagram\Config\WpOption;
use RebelCode\Spotlight\Instagram\Module;
use RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Fetchers\HashtagMediaFetcher;
use RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Fetchers\StoriesMediaFetcher;
use RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Fetchers\TaggedMediaFetcher;
use RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Processors\FilteringProcessor;
use RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Processors\HashtagSorterProcessor;
use RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Processors\MediaTypeProcessor;
use RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Processors\ModerationProcessor;

/**
 * The module that adds PRO media fetchers and processors to the core plugin's media store.
 *
 * @since 0.1
 */
class ProMediaModule extends Module
{
    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function run(ContainerInterface $c)
    {
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function getFactories() : array
    {
        return [
            //==========================================================================
            // FETCHERS
            //==========================================================================

            // The media fetchers that are added by this module.
            'media/fetchers' => new ServiceList([
                'media/fetchers/tagged',
                'media/fetchers/hashtags',
                'media/fetchers/stories',
            ]),

            // The fetcher that gets media for tagged accounts
            'media/fetchers/tagged' => new Constructor(TaggedMediaFetcher::class, [
                '@ig/api/graph/client',
                '@accounts/cpt',
            ]),

            // The fetcher that gets media with specific hashtags
            'media/fetchers/hashtags' => new Constructor(HashtagMediaFetcher::class, [
                '@ig/api/graph/client',
                '@accounts/cpt',
            ]),

            // The fetcher that gets user stories
            'media/fetchers/stories' => new Constructor(StoriesMediaFetcher::class, [
                '@ig/api/graph/client',
                '@accounts/cpt',
            ]),

            //==========================================================================
            // PROCESSORS
            //==========================================================================

            // The media processors that are added by this module.
            'media/processors' => new ServiceList([
                'media/processors/mediaType',
                'media/processors/filters',
                'media/processors/moderation',
            ]),

            // The processor that filters by media type
            'media/processors/mediaType' => new Constructor(MediaTypeProcessor::class),

            // The processor that filters media by the hashtag and caption filters
            'media/processors/filters' => new Constructor(FilteringProcessor::class, ['@config/set']),

            // The processor that filters moderated media
            'media/processors/moderation' => new Constructor(ModerationProcessor::class),

            //==========================================================================
            // CONFIG
            //==========================================================================

            // The config entries for the global filters
            'config/filters/hashtags/whitelist' => new Value(new WpOption('sli_hashtag_whitelist', [])),
            'config/filters/hashtags/blacklist' => new Value(new WpOption('sli_hashtag_blacklist', [])),
            'config/filters/captions/whitelist' => new Value(new WpOption('sli_caption_whitelist', [])),
            'config/filters/captions/blacklist' => new Value(new WpOption('sli_caption_blacklist', [])),
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
            // Adds the fetchers to core's media store
            'media/fetchers' => new Extension(['media/fetchers'], function ($prev, $new) {
                return array_merge($prev, $new);
            }),

            // Adds the processors to core's media store
            'media/processors' => new Extension(['media/processors'], function ($prev, $new) {
                return array_merge($prev, $new);
            }),

            // Register the config entries
            'config/entries' => new ArrayExtension([
                'hashtagWhitelist' => 'config/filters/hashtags/whitelist',
                'hashtagBlacklist' => 'config/filters/hashtags/blacklist',
                'captionWhitelist' => 'config/filters/captions/whitelist',
                'captionBlacklist' => 'config/filters/captions/blacklist',
            ]),
        ];
    }
}
