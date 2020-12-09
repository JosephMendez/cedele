<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore;

use RebelCode\Spotlight\Instagram\IgApi\IgUser;
use RebelCode\Spotlight\Instagram\MediaStore\MediaSource;
use RebelCode\Spotlight\Instagram\Modules\Pro\IgApi\ProIgGraphApiClient;

/**
 * Extended media source, for the PRO version of the plugin.
 *
 * @since 0.1
 */
class ProMediaSource extends MediaSource
{
    const TAGGED_ACCOUNT = 'TAGGED_ACCOUNT';
    const HASHTAG_RECENT = 'RECENT_HASHTAG';
    const HASHTAG_POPULAR = 'POPULAR_HASHTAG';
    const USER_STORY = 'USER_STORY';

    /**
     * Creates a media source for a tagged user.
     *
     * @since 0.1
     *
     * @param IgUser $user The user instance.
     *
     * @return static The created media source instance.
     */
    public static function forTagged(IgUser $user)
    {
        return static::create([
            'name' => $user->getUsername(),
            'type' => static::TAGGED_ACCOUNT,
        ]);
    }

    /**
     * Creates a media source for a hashtag.
     *
     * @since 0.1
     *
     * @see   ProIgGraphApiClient::RECENT_MEDIA
     * @see   ProIgGraphApiClient::TOP_MEDIA
     *
     * @param string $tag  The hashtag.
     * @param string $type The type.
     *
     * @return static The created media source instance.
     *
     */
    public static function forHashtag(string $tag, string $type)
    {
        return static::create([
            'name' => $tag,
            'type' => ($type === ProIgGraphApiClient::RECENT_MEDIA) ? static::HASHTAG_RECENT : static::HASHTAG_POPULAR,
        ]);
    }

    /**
     * Creates a media source for a user's story.
     *
     * @since 0.1
     *
     * @param IgUser $user The user instance.
     *
     * @return static The created media source instance.
     */
    public static function forStory(IgUser $user) {
        return static::create([
            'name' => $user->getUsername(),
            'type' => static::USER_STORY,
        ]);
    }
}
