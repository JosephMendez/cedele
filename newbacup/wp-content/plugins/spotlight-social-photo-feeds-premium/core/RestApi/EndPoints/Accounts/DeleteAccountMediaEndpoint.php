<?php

namespace RebelCode\Spotlight\Instagram\RestApi\EndPoints\Accounts;

use RebelCode\Spotlight\Instagram\MediaStore\MediaSource;
use RebelCode\Spotlight\Instagram\PostTypes\AccountPostType;
use RebelCode\Spotlight\Instagram\PostTypes\MediaPostType;
use RebelCode\Spotlight\Instagram\RestApi\EndPoints\AbstractEndpointHandler;
use RebelCode\Spotlight\Instagram\Utils\Arrays;
use RebelCode\Spotlight\Instagram\Wp\PostType;
use RebelCode\Spotlight\Instagram\Wp\RestRequest;
use WP_Error;
use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Endpoint handler for deleting accounts.
 *
 * @since 0.1
 */
class DeleteAccountMediaEndpoint extends AbstractEndpointHandler
{
    /**
     * @since 0.1
     *
     * @var PostType
     */
    protected $accountsCpt;

    /**
     * @since 0.1
     *
     * @var PostType
     */
    protected $mediaCpt;

    /**
     * Constructor.
     *
     * @since 0.1
     *
     * @param PostType $accountsCpt The accounts post type.
     * @param PostType $mediaCpt    The media post type.
     */
    public function __construct(PostType $accountsCpt, PostType $mediaCpt)
    {
        $this->accountsCpt = $accountsCpt;
        $this->mediaCpt = $mediaCpt;
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    protected function handle(WP_REST_Request $request)
    {
        if (!RestRequest::has_param($request, 'id')) {
            return new WP_Error('sli_missing_id', 'Missing account ID in request', ['status' => 400]);
        }

        $id = $request->get_param('id');
        $accountPost = $this->accountsCpt->get($id);

        if ($accountPost === null) {
            return new WP_Error('sli_account_not_found', "Account with ID {$id} not found", ['status' => 404]);
        }

        $account = AccountPostType::fromWpPost($accountPost);
        $source = MediaSource::forUser($account->getUser());

        MediaPostType::deleteForSource($source, $this->mediaCpt);


        $media = $this->mediaCpt->query([
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => MediaPostType::SOURCE_NAME,
                    'value' => $source->getName(),
                ],
                [
                    'key' => MediaPostType::SOURCE_TYPE,
                    'value' => $source->getType(),
                ],
            ],
        ]);

        Arrays::each($media, function (WP_Post $post) {
            wp_delete_post($post->ID, true);
        });

        return new WP_REST_Response(['ok' => 1]);
    }
}
