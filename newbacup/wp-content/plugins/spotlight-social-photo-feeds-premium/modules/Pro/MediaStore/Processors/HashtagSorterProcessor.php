<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Processors;

use RebelCode\Spotlight\Instagram\Feeds\Feed;
use RebelCode\Spotlight\Instagram\MediaStore\IgCachedMedia;
use RebelCode\Spotlight\Instagram\MediaStore\MediaProcessorInterface;
use RebelCode\Spotlight\Instagram\MediaStore\MediaSource;
use RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\ProMediaSource;

/**
 * Media store processor that sorts hashtag media based on the order of the hashtag sources in a feed.
 *
 * @since 0.1
 */
class HashtagSorterProcessor implements MediaProcessorInterface
{
    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function process(array &$mediaList, Feed $feed)
    {
        $fHashtags = $feed->getOption('hashtags');

        if (count($fHashtags) === 0) {
            return;
        }

        // Pluck the 'tag' column to get an array of tags only.
        // Then flip the array so that the tags are array keys and their positions are array values.
        $hashtagOrder = array_flip(array_column($fHashtags, 'tag'));

        usort($mediaList, function (IgCachedMedia $m1, IgCachedMedia $m2) use ($hashtagOrder) {
            $src1 = $m1->getSource();
            $src2 = $m2->getSource();

            // Get whether the media posts come from hashtag sources
            $ifh1 = $this->isFromHashtag($src1);
            $ifh2 = $this->isFromHashtag($src2);

            // If the first media post is not from a hashtag, do not sort regardless of what the second one is from
            if (!$ifh1) {
                return 0;
            }

            // If the first one is from a hashtag and the second one isn't, move the first one down the list
            if ($ifh1 && !$ifh2) {
                return 1;
            }

            // If both media posts are from hashtags, sort according to their hashtag's index in the feed's options.
            return $hashtagOrder[$src1->getName()] <=> $hashtagOrder[$src2->getName()];
        });
    }

    /**
     * Checks if the given media source is a hashtag source.
     *
     * @since 0.1
     *
     * @param MediaSource $source The media source.
     *
     * @return bool True if a hashtag source, false if not.
     */
    protected function isFromHashtag(MediaSource $source)
    {
        $type = $source->getType();

        return $type === ProMediaSource::HASHTAG_POPULAR || $type === ProMediaSource::HASHTAG_RECENT;
    }
}
