<?php

namespace RebelCode\Spotlight\Instagram\Feeds;

use Dhii\Output\TemplateInterface;

/**
 * The template that renders a feed.
 *
 * @since 0.1
 */
class FeedTemplate implements TemplateInterface
{
    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function render($ctx = null)
    {
        if (!is_array($ctx)) {
            return '';
        }

        // Convert the options into JSON
        $feedJson = json_encode($ctx);
        // The name of the JS variable to use
        $varName = uniqid('feed');

        // Prepare the HTML class
        $className = 'spotlight-instagram-feed';
        if (array_key_exists('className', $ctx) && !empty($ctx['className'])) {
            $className .= ' ' . $ctx['className'];
        }

        // Output the required HTML and JS
        ob_start();
        ?>
        <div class="<?= $className ?>" data-feed-var="<?= $varName ?>"></div>
        <script type="text/javascript">
            if (!window.SliFrontCtx) {
                window.SliFrontCtx = {};
            }

            window.SliFrontCtx["<?= $varName ?>"] = <?= $feedJson ?>;
        </script>
        <?php

        // Trigger the action that will enqueue the required JS bundles
        do_action('spotlight/instagram/enqueue_front_app');

        return ob_get_clean();
    }
}
