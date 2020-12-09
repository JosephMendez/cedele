<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro\MediaStore\Fetchers;

use RebelCode\Spotlight\Instagram\Feeds\Feed;
use RebelCode\Spotlight\Instagram\IgApi\IgUser;
use RebelCode\Spotlight\Instagram\MediaStore\MediaFetcherInterface;
use RebelCode\Spotlight\Instagram\MediaStore\MediaStore;
use RebelCode\Spotlight\Instagram\Modules\Pro\IgApi\ProIgGraphApiClient;
use RebelCode\Spotlight\Instagram\PostTypes\AccountPostType;
use RebelCode\Spotlight\Instagram\Wp\PostType;
use WP_Post;

/**
 * Fetches user stories according to a feed's options.
 *
 * @since 0.1
 */
class StoriesMediaFetcher implements MediaFetcherInterface
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
        // Get the IDs of the selected accounts and tagged accounts. If empty, stop early
        $accountIds = $feed->getOption('accounts', []);
        $taggedIds = $feed->getOption('tagged', []);
        $allAccountIds = array_merge($accountIds, $taggedIds);
        if (empty($allAccountIds)) {
            return;
        }

        // Retrieve the accounts for these IDs, filtering for existing business accounts only.
        // If empty, stop here
        $accountPosts = $this->cpt->query(['post__in' => $allAccountIds]);
        $accountPosts = array_filter($accountPosts, function (WP_Post $post) {
            return $post->{AccountPostType::TYPE} === IgUser::TYPE_BUSINESS;
        });
        if (empty($accountPosts)) {
            return;
        }

        // Make sure that the order of accounts is preserved after the query
        usort($accountPosts, function (WP_Post $post1, WP_Post $post2) use ($allAccountIds) {
            // Retrieve the index of each post's ID from $allAccountIds and use the comparison for sorting
            return array_search($post1->ID, $allAccountIds) <=> array_search($post2->ID, $allAccountIds);
        });

        // Get the ID of the header account option, defaulting to the first account in the list
        $headerAccountId = $feed->getOption('headerAccount', reset($accountPosts)->ID);
        // Get the header account, if the account ID is one of the accounts used by the feed
        $headerAccount = (array_search($headerAccountId, $allAccountIds) !== false)
            ? $this->cpt->get($headerAccountId)
            : null;

        // If the feed only has one account, use that account. Otherwise use the header account.
        $accountPost = (count($accountPosts) === 1)
            ? reset($accountPosts)
            : $headerAccount;

        // Get the stories from the API if the account was successfully determined.
        if ($accountPost instanceof WP_Post) {
            $account = AccountPostType::fromWpPost($accountPost);
            $stories = $this->api->getStories($account);

            // Add the stories to the store
            $store->addStories($stories, $account->getUser());
        }
    }
}
