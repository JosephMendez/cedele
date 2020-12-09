<?php

namespace RebelCode\Spotlight\Instagram\MediaStore;

use DateTime;
use RebelCode\Spotlight\Instagram\IgApi\IgMedia;
use WP_Post;

class IgCachedMedia extends IgMedia
{
    /**
     * @since 0.1
     *
     * @var WP_Post|null
     */
    protected $post;

    /**
     * @since 0.1
     *
     * @var int
     */
    protected $lastRequested;

    /**
     * @since 0.1
     *
     * @var MediaSource
     */
    protected $source;

    /**
     * @inheritDoc
     *
     * @since 0.1
     *
     * @return IgCachedMedia
     */
    public static function create(array $data)
    {
        /* @var $instance IgCachedMedia */
        $instance = parent::create($data);

        $instance->post = $data['post'] ?? null;
        $instance->lastRequested = empty($data['last_requested']) ? time() : $data['last_requested'];
        $instance->source = MediaSource::create($data['source'] ?? []);

        return $instance;
    }

    /**
     * Creates an instance from a non-cached instance.
     *
     * @since 0.1
     *
     * @param IgMedia $media
     * @param array   $extra
     *
     * @return IgCachedMedia
     */
    public static function from(IgMedia $media, array $extra = []) : IgCachedMedia
    {
        if ($media instanceof static) {
            return $media;
        }

        $post = $extra['post'] ?? null;
        $lastRequested = empty($extra['last_requested']) ? time() : $extra['last_requested'];
        $source = MediaSource::create($extra['source'] ?? []);

        $timestamp = $media->getTimestamp();

        return static::create([
            'post' => $post,
            'id' => $media->getId(),
            'username' => $media->getUsername(),
            'timestamp' => $timestamp ? $timestamp->format(DateTime::ISO8601) : null,
            'caption' => $media->getCaption(),
            'media_type' => $media->getType(),
            'media_url' => $media->getUrl(),
            'permalink' => $media->getPermalink(),
            'thumbnail_url' => $media->getThumbnailUrl(),
            'like_count' => $media->getLikesCount(),
            'comments_count' => $media->getCommentsCount(),
            'comments' => $media->getComments(),
            'children' => $media->getChildren(),
            'last_requested' => $lastRequested,
            'source' => $source,
        ]);
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public static function getDefaults()
    {
        $defaults = parent::getDefaults();
        $defaults['last_requested'] = '';
        $defaults['source'] = MediaSource::create([]);

        return $defaults;
    }

    /**
     * @since 0.1
     *
     * @return WP_Post|null
     */
    public function getPost() : ?WP_Post
    {
        return $this->post;
    }

    /**
     * @since 0.1
     *
     * @return int
     */
    public function getLastRequested() : int
    {
        return $this->lastRequested;
    }

    /**
     * @since 0.1
     *
     * @return MediaSource
     */
    public function getSource() : MediaSource
    {
        return $this->source;
    }

    /**
     * Gets a {@link DateTime} instance that represents the current date and time.
     *
     * @since 0.1
     *
     * @return DateTime
     */
    public static function now()
    {
        return DateTime::createFromFormat(DateTime::ISO8601, date(DateTime::ISO8601));
    }
}
