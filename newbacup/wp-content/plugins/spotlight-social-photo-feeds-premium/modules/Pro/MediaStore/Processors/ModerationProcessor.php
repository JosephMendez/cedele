<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Processors;

use RebelCode\Spotlight\Instagram\Feeds\Feed;
use RebelCode\Spotlight\Instagram\MediaStore\IgCachedMedia;
use RebelCode\Spotlight\Instagram\MediaStore\MediaProcessorInterface;

/**
 * Filters media according to a feed's moderation options.
 *
 * @since 0.1
 */
class ModerationProcessor implements MediaProcessorInterface
{
    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function process(array &$mediaList, Feed $feed)
    {
        // Flip the array so that media IDs are array keys, enabling fast look up of IDs
        $moderation = array_flip($feed->getOption('moderation'));

        // Do nothing if moderation is empty
        if (empty($moderation)) {
            return;
        }

        $isBlacklist = $feed->getOption('moderationMode') === "blacklist";

        $mediaList = array_filter($mediaList, function (IgCachedMedia $media) use ($feed, $moderation, $isBlacklist) {
            $mediaId = $media->getId();

            return (
                // Allow media if mode is blacklist and the media is not in the moderation list
                ($isBlacklist && array_key_exists($mediaId, $moderation) === false) ||
                // Allow media if mode is whitelist and the media is in the moderation list
                (!$isBlacklist && array_key_exists($mediaId, $moderation) !== false)
            );
        });
    }
}
