<?php

namespace RebelCode\Spotlight\Instagram\Actions;

use Dhii\Output\TemplateInterface;
use RebelCode\Spotlight\Instagram\PostTypes\FeedPostType;
use RebelCode\Spotlight\Instagram\Utils\Arrays;
use RebelCode\Spotlight\Instagram\Utils\Strings;
use RebelCode\Spotlight\Instagram\Wp\PostType;
use WP_Post;

/**
 * The action that renders the content for the shortcode.
 *
 * @since 0.1
 */
class RenderShortcode
{
    /**
     * @since 0.1
     *
     * @var PostType
     */
    protected $cpt;

    /**
     * @since 0.1
     *
     * @var TemplateInterface
     */
    protected $template;

    /**
     * Constructor.
     *
     * @since 0.1
     *
     * @param PostType          $cpt      The feeds post type.
     * @param TemplateInterface $template The template to use for rendering.
     */
    public function __construct(PostType $cpt, TemplateInterface $template)
    {
        $this->cpt = $cpt;
        $this->template = $template;
    }

    /**
     * Renders the content for the shortcode.
     *
     * @since 0.1
     *
     * @param array $args The render arguments.
     *
     * @return string The rendered content.
     */
    public function __invoke(array $args)
    {
        $options = Arrays::mapPairs($args, function ($key, $value) {
            return [Strings::kebabToCamel($key), $value];
        });

        // If the "feed" arg is given, get the feed for that ID and merge its options with the other args
        if (array_key_exists('feed', $options)) {
            $feedId = $options['feed'];
            $feedPost = $this->cpt->get($feedId);

            if ($feedPost instanceof WP_Post) {
                unset($options['feed']);
                $options = array_merge($feedPost->{FeedPostType::OPTIONS}, $options);
            } else {
                return is_user_logged_in()
                    ? "<p>There is no feed with ID {$feedId} <small>(only admins can see this message)</small></p>"
                    : '';
            }
        }

        return $this->template->render($options);
    }
}
