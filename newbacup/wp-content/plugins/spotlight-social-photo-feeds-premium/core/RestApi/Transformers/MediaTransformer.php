<?php

namespace RebelCode\Spotlight\Instagram\RestApi\Transformers;

use DateTime;
use Dhii\Transformer\TransformerInterface;
use RebelCode\Spotlight\Instagram\IgApi\IgComment;
use RebelCode\Spotlight\Instagram\IgApi\IgMedia;
use RebelCode\Spotlight\Instagram\MediaStore\IgCachedMedia;

/**
 * Transforms {@link IgMedia} instances into REST API response format.
 *
 * @since 0.1
 */
class MediaTransformer implements TransformerInterface
{
    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function transform($source)
    {
        if (!($source instanceof IgMedia)) {
            return $source;
        }

        $media = IgCachedMedia::from($source);

        $children = $media->getChildren();
        foreach ($children as $idx => $child) {
            $children[$idx] = [
                'id' => $child->getId(),
                'type' => $child->getType(),
                'url' => $child->getUrl(),
                'permalink' => $child->getPermalink(),
            ];
        }

        $timestamp = $media->getTimestamp();

        return [
            'id' => $media->getId(),
            'username' => $media->getUsername(),
            'caption' => $media->getCaption(),
            'timestamp' => $timestamp ? $timestamp->format(DateTime::ISO8601) : null,
            'type' => $media->getType(),
            'url' => $media->getUrl(),
            'permalink' => $media->getPermalink(),
            'thumbnail' => $media->getThumbnailUrl(),
            'likesCount' => $media->getLikesCount(),
            'commentsCount' => $media->getCommentsCount(),
            'comments' => array_map([$this, 'transformComment'], $media->getComments()),
            'children' => $children,
            'source' => $media->getSource()->toArray(),
        ];
    }

    /**
     * Transforms a single media comment.
     *
     * @since 0.1
     *
     * @param IgComment $comment The comment instance.
     *
     * @return array The transformation result.
     */
    public function transformComment(IgComment $comment)
    {
        $timestamp = $comment->getTimestamp();

        return [
            'id' => $comment->getId(),
            'username' => $comment->getUsername(),
            'text' => $comment->getText(),
            'timestamp' => $timestamp ? $timestamp->format(DateTime::ISO8601) : null,
            'likeCount' => $comment->getLikeCount(),
        ];
    }
}
