<?php

/*
 * @wordpress-plugin
 *
 * Plugin Name: Spotlight - Social Photo Feeds (Premium)
 * Description: Easily embed beautiful Instagram feeds on your WordPress site.
 * Version: 0.3.2
 * Author: RebelCode
 * Plugin URI: https://spotlightwp.com
 * Author URI: https://rebelcode.com
 * Requires at least: 5.0
 * Requires PHP: 7.1
 *
 * @fs_premium_only /modules/Pro/, /ui/src/admin-pro/, /ui/src/common-pro/, /ui/dist/admin-pro.js, /ui/dist/common-pro.js, /ui/dist/styles/admin-pro.css, /ui/dist/styles/common-pro.css
 * @fs_ignore /ui/, /vendor/
 */

use RebelCode\Spotlight\Instagram\Plugin;

// If not running within a WordPress context, or the plugin is already running, stop
if (!defined('ABSPATH')) {
    exit;
}

// Check if Freemius is already loaded by another instance of this plugin
if (function_exists('sliFreemius')) {
    // A side-effect of doing this is the disabling of the free version when a premium version is activated
    sliFreemius()->set_basename(true, __FILE__);

    // Stop here if Freemius is already loaded
    return;
}

// Load Freemius
require_once __DIR__ . '/freemius.php';

// Plugin logic - only if not already declared
if (!defined('SL_INSTA')) {
    // Used for detecting that the plugin is running
    define('SL_INSTA', true);
    // The plugin version
    define('SL_INSTA_VERSION', '0.3.2');

    // Dev mode constant that controls whether development tools are enabled
    if (!defined('SL_INSTA_DEV')) {
        define('SL_INSTA_DEV', false);
    }

    // Check PHP version
    if (version_compare(PHP_VERSION, '7.1', '<')) {
        add_action('admin_notices', function () {
            printf(
                '<div class="notice notice-error"><p>%s</p></div>',
                sprintf(
                    _x(
                        '%1$s requires PHP version %2$s or later',
                        '%1$s is the name of the plugin, %2$s is the required PHP version',
                        'sli'
                    ),
                    '<strong>Spotlight - Social Photo Feeds</strong>',
                    '7.1'
                )
            );
        });

        return;
    }

    // Check WordPress version
    global $wp_version;
    if (version_compare($wp_version, '5.0', '<')) {
        add_action('admin_notices', function () {
            printf(
                '<div class="notice notice-error"><p>%s</p></div>',
                sprintf(
                    _x(
                        '%1$s requires WordPress version %2$s or later',
                        '%1$s is the name of the plugin, %2$s is the required WP version',
                        'sli'
                    ),
                    '<strong>Spotlight - Social Photo Feeds</strong>',
                    '5.0'
                )
            );
        });

        return;
    }

    // Autoloader
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require __DIR__ . '/vendor/autoload.php';
    }

    // Load the developer API
    require_once __DIR__ . '/includes/dev-api.php';

    /**
     * Retrieves the plugin instance.
     *
     * @since 0.2
     *
     * @return Plugin
     */
    function spotlightInsta()
    {
        static $instance = null;

        return ($instance === null)
            ? $instance = new Plugin(__FILE__)
            : $instance;
    }

    try {
        spotlightInsta()->run();
    } catch (Throwable $exception) {
        wp_die(
            $exception->getMessage() . "\n<pre>" . $exception->getTraceAsString() . '</pre>',
            'Spotlight - Social Photo Feeds | Error',
            [
                'back_link' => true,
            ]
        );
    }
}
