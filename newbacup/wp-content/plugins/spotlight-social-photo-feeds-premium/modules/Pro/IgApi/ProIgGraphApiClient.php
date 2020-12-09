<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro\IgApi;

use DomainException;
use RebelCode\Spotlight\Instagram\IgApi\IgAccount;
use RebelCode\Spotlight\Instagram\IgApi\IgApiUtils;
use RebelCode\Spotlight\Instagram\IgApi\IgGraphApiClient;
use RebelCode\Spotlight\Instagram\IgApi\IgMedia;

/**
 * An extended Graph API client for the PRO version of the plugin.
 *
 * @since 0.1
 */
class ProIgGraphApiClient extends IgGraphApiClient
{
    const TOP_MEDIA = 'top_media';
    const RECENT_MEDIA = 'recent_media';

    /**
     * Retrieves the ID of a given hashtag.
     *
     * @since 0.1
     *
     * @param string    $hashtag The hashtag, without the '#' prefix.
     * @param IgAccount $account A business account, used for authorization.
     *
     * @return string|null The hashtag ID, or null of the hashtag does not exist.
     */
    public function getHashtagId(string $hashtag, IgAccount $account) : ?string
    {
        $getRemote = function () use ($hashtag, $account) {
            return IgApiUtils::request($this->client, 'GET', static::API_URI . "/ig_hashtag_search", [
                'query' => [
                    'q' => $hashtag,
                    'user_id' => $account->getUser()->getId(),
                    'access_token' => $account->getAccessToken()->getCode(),
                    'limit' => 1,
                ],
            ]);
        };

        $body = IgApiUtils::getCachedResponse($this->cache, "hashtag/{$hashtag}", $getRemote);
        $data = $body['data'];

        if (count($data) === 0) {
            return null;
        }

        $hashtag = $data[0];

        return $hashtag['id'] ?? null;
    }

    /**
     * Fetches media for a specific hashtag, from all across Instagram.
     *
     * @since 0.1
     *
     * @param string    $hashtagId The hashtag ID.
     * @param string    $type      The media type to get, either {@link TOP_MEDIA} and {@link RECENT_MEDIA}.
     * @param IgAccount $account   A business account, used for authorization.
     *
     * @return IgMedia[] A list of media objects.
     */
    public function getHashtagMedia(string $hashtagId, string $type, IgAccount $account) : array
    {
        if ($type !== static::RECENT_MEDIA && $type !== static::TOP_MEDIA) {
            throw new DomainException('Invalid media type');
        }

        $getRemote = function () use ($hashtagId, $type, $account) {
            return IgApiUtils::request($this->client, 'GET', static::API_URI . "/{$hashtagId}/{$type}", [
                'query' => [
                    'fields' => implode(',', IgApiUtils::getHashtagMediaFields()),
                    'user_id' => $account->getUser()->getId(),
                    'access_token' => $account->getAccessToken()->getCode(),
                    'limit' => 200,
                ],
            ]);
        };

        $body = IgApiUtils::getCachedResponse($this->cache, "hashtag/${hashtagId}_${type}", $getRemote);
        $media = $body['data'];

        return array_map([IgMedia::class, 'create'], $media);
    }

    /**
     * Retrieves media where an account is tagged.
     *
     * @since 0.1
     *
     * @param IgAccount $account The account for which to retrieve tagged media. Must be a business account.
     *
     * @return IgMedia[] A list of media objects.
     */
    public function getTaggedMedia(IgAccount $account) : array
    {
        $userId = $account->getUser()->getId();

        $getRemote = function () use ($userId, $account) {
            return IgApiUtils::request($this->client, 'GET', static::API_URI . "/{$userId}/tags", [
                'query' => [
                    'fields' => implode(',', IgApiUtils::getMediaFields()),
                    'user_id' => $userId,
                    'access_token' => $account->getAccessToken()->getCode(),
                    'limit' => 200,
                ],
            ]);
        };

        $body = IgApiUtils::getCachedResponse($this->cache, "tagged/{$userId}", $getRemote);
        $media = $body['data'];

        return array_map([IgMedia::class, 'create'], $media);
    }

    /**
     * Retrieves story media for a given account.
     *
     * @since 0.1
     *
     * @param IgAccount $account The account for which to retrieve story media.
     *
     * @return IgMedia[] A list of media objects, one for each story.
     */
    public function getStories(IgAccount $account)
    {
        $userId = $account->getUser()->getId();

        $getRemote = function () use ($userId, $account) {
            return IgApiUtils::request($this->client, 'GET', static::API_URI . "/{$userId}/stories", [
                'query' => [
                    'fields' => implode(',', IgApiUtils::getMediaFields()),
                    'access_token' => $account->getAccessToken()->getCode(),
                    'limit' => 50,
                ],
            ]);
        };

        $body = IgApiUtils::getCachedResponse($this->cache, "stories/{$userId}", $getRemote);
        $media = $body['data'];

        return array_map([IgMedia::class, 'create'], $media);
    }
}
