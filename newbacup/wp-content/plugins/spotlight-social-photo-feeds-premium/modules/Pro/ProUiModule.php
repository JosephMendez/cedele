<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro;

use Dhii\Services\Extension;
use Dhii\Services\Factories\FuncService;
use Dhii\Services\Factory;
use Psr\Container\ContainerInterface;
use RebelCode\Spotlight\Instagram\Module;
use RebelCode\Spotlight\Instagram\Wp\Asset;

/**
 * The module that adds the PRO ui assets to the core plugin.
 *
 * @since 0.1
 */
class ProUiModule extends Module
{
    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function run(ContainerInterface $c)
    {
        // When the admin and front apps are enqueued, enqueue the admin PRO scripts and styles
        add_action('spotlight/instagram/enqueue_admin_app', $c->get('enqueue_admin_fn'));
        add_action('spotlight/instagram/enqueue_front_app', $c->get('enqueue_front_fn'));
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function getFactories()
    {
        return [
            'scripts' => new Factory(['@ui/scripts_url', '@ui/assets_ver'], function ($url, $ver) {
                return [
                    'sli-admin-pro' => Asset::script("{$url}/admin-pro.js", $ver, [
                        'sli-admin',
                        'sli-common-pro',
                    ]),
                    'sli-common-pro' => Asset::script("{$url}/common-pro.js", $ver, [
                        'sli-common',
                    ]),
                ];
            }),
            'styles' => new Factory(['@ui/styles_url', '@ui/assets_ver'], function ($url, $ver) {
                return [
                    'sli-common-pro' => Asset::style("{$url}/common-pro.css", $ver, [
                        'sli-common',
                    ]),
                ];
            }),
            'enqueue_admin_fn' => new FuncService([], function () {
                wp_enqueue_script('sli-admin-pro');
                wp_enqueue_style('sli-common-pro');
            }),
            'enqueue_front_fn' => new FuncService([], function () {
                wp_enqueue_script('sli-common-pro');
                wp_enqueue_style('sli-common-pro');
            }),
        ];
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function getExtensions()
    {
        return [
            // Add the pro scripts to the list of scripts to be registered
            'ui/scripts' => new Extension(['scripts'], function ($all, $pro) {
                return array_merge($all, $pro);
            }),
            // Add the pro styles to the list of styles to be registered
            'ui/styles' => new Extension(['styles'], function ($all, $pro) {
                return array_merge($all, $pro);
            }),
        ];
    }
}
