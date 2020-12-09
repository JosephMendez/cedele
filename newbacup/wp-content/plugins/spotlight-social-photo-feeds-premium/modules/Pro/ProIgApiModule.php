<?php

namespace RebelCode\Spotlight\Instagram\Modules\Pro;

use Dhii\Services\Factories\Constructor;
use Psr\Container\ContainerInterface;
use RebelCode\Spotlight\Instagram\Di\OverrideExtension;
use RebelCode\Spotlight\Instagram\Module;
use RebelCode\Spotlight\Instagram\Modules\Pro\IgApi\ProIgGraphApiClient;

/**
 * The module that substitutes the core plugin's Instagram API client with the PRO version.
 *
 * @since 0.1
 */
class ProIgApiModule extends Module
{
    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function run(ContainerInterface $c)
    {
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function getFactories()
    {
        return [
            // The extended Graph API client
            'api/graph/client' => new Constructor(ProIgGraphApiClient::class, [
                '@ig/api/driver',
                '@ig/api/cache',
            ]),
        ];
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    public function getExtensions() : array
    {
        return [
            // Replace the original Graph API client with the extended one
            'ig/api/graph/client' => new OverrideExtension('api/graph/client'),
        ];
    }
}
