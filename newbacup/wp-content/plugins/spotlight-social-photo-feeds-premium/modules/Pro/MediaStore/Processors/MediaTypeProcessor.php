<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Processors;

use RebelCode\Spotlight\Instagram\Feeds\Feed;
use RebelCode\Spotlight\Instagram\MediaStore\IgCachedMedia;
use RebelCode\Spotlight\Instagram\MediaStore\MediaProcessorInterface;

/**
 * Filters media according to the feed's selected media type.
 *
 * @since 0.1
 */
class MediaTypeProcessor implements MediaProcessorInterface
{
    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function process(array &$mediaList, Feed $feed)
    {
        // Get the media type option from the feed's options
        $typeOption = $feed->getOption('mediaType');

        // Do nothing if the option is set to "all"
        if ($typeOption === 'all') {
            return;
        }

        $mediaList = array_filter($mediaList, function (IgCachedMedia $m) use ($feed, $typeOption) {
            $isVideo = $m->getType() === 'VIDEO';

            return (
                ($typeOption === 'photos' && !$isVideo) ||
                ($typeOption === 'videos' && $isVideo)
            );
        });
    }
}
