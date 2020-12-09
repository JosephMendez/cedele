<?php

namespace RebelCode\Spotlight\Instagram\RestApi\Transformers;

use Dhii\Transformer\TransformerInterface;
use RebelCode\Spotlight\Instagram\IgApi\IgAccount;
use RebelCode\Spotlight\Instagram\PostTypes\AccountPostType;
use RebelCode\Spotlight\Instagram\PostTypes\FeedPostType;
use RebelCode\Spotlight\Instagram\Wp\PostType;
use WP_Post;

/**
 * Transforms {@link IgAccount} instances into REST API response format.
 *
 * @since 0.1
 */
class AccountTransformer implements TransformerInterface
{
    /**
     * @since 0.1
     *
     * @var PostType
     */
    protected $feedsCpt;

    /**
     * Constructor.
     *
     * @since 0.1
     *
     * @param PostType $feedsCpt The feeds post type.
     */
    public function __construct(PostType $feedsCpt)
    {
        $this->feedsCpt = $feedsCpt;
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function transform($source)
    {
        if (!($source instanceof WP_Post)) {
            return $source;
        }

        $account = AccountPostType::fromWpPost($source);
        $user = $account->getUser();

        $usages = [];
        $feeds = $this->feedsCpt->query();
        foreach ($feeds as $feedPost) {
            $options = $feedPost->{FeedPostType::OPTIONS};

            $usedAccounts = $options['accounts'] ?? [];
            $usedTagged = $options['tagged'] ?? [];

            $used = array_search($source->ID, $usedAccounts) !== false ||
                    array_search($source->ID, $usedTagged) !== false;

            if ($used) {
                $usages[] = $feedPost->ID;
            }
        }

        return [
            'id' => $source->ID,
            'type' => $user->getType(),
            'userId' => $user->getId(),
            'username' => $user->getUsername(),
            'bio' => $user->getBio(),
            'customBio' => $source->{AccountPostType::CUSTOM_BIO},
            'profilePicUrl' => $user->getProfilePicUrl(),
            'customProfilePicUrl' => $source->{AccountPostType::CUSTOM_PROFILE_PIC},
            'mediaCount' => $user->getMediaCount(),
            'followersCount' => $user->getFollowersCount(),
            'usages' => $usages,
        ];
    }
}
