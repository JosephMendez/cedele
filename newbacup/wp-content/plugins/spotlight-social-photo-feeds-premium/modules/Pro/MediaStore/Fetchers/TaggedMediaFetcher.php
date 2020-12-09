<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Fetchers;

use RebelCode\Spotlight\Instagram\Feeds\Feed;
use RebelCode\Spotlight\Instagram\IgApi\IgUser;
use RebelCode\Spotlight\Instagram\MediaStore\MediaFetcherInterface;
use RebelCode\Spotlight\Instagram\MediaStore\MediaStore;
use RebelCode\Spotlight\Instagram\Modules\Pro\IgApi\ProIgGraphApiClient;
use RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\ProMediaSource;
use RebelCode\Spotlight\Instagram\PostTypes\AccountPostType;
use RebelCode\Spotlight\Instagram\Wp\PostType;

/**
 * Fetches media in which selected accounts for a feed are tagged in.
 *
 * @since 0.1
 */
class TaggedMediaFetcher implements MediaFetcherInterface
{
    /**
     * @since 0.1
     *
     * @var ProIgGraphApiClient
     */
    protected $api;

    /**
     * @since 0.1
     *
     * @var PostType
     */
    protected $cpt;

    /**
     * Constructor.
     *
     * @since 0.1
     *
     * @param ProIgGraphApiClient $api The Instagram Graph API client.
     * @param PostType            $cpt The accounts CPT.
     */
    public function __construct(ProIgGraphApiClient $api, PostType $cpt)
    {
        $this->api = $api;
        $this->cpt = $cpt;
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function fetch(Feed $feed, MediaStore $store)
    {
        $taggedIds = $feed->getOption('tagged', []);
        if (empty($taggedIds)) {
            return;
        }

        // Get the account posts with the selected tagged IDs
        $accountPosts = $this->cpt->query(['post__in' => $taggedIds]);

        foreach ($accountPosts as $taggedPost) {
            // Create the account from the post
            $account = AccountPostType::fromWpPost($taggedPost);

            // If not a business account, skip
            if ($account->getUser()->getType() !== IgUser::TYPE_BUSINESS) {
                continue;
            }

            // Create the source to assign to the media
            $source = ProMediaSource::forTagged($account->getUser());
            // Get the tagged media
            $media = $this->api->getTaggedMedia($account);

            // Add the fetched media to the store
            $store->addMedia($media, $source);
        }
    }
}
