<?php

use RebelCode\Spotlight\Instagram\Modules\AccountsModule;
use RebelCode\Spotlight\Instagram\Modules\CleanUpCronModule;
use RebelCode\Spotlight\Instagram\Modules\ConfigModule;
use RebelCode\Spotlight\Instagram\Modules\FeedsModule;
use RebelCode\Spotlight\Instagram\Modules\ImportCronModule;
use RebelCode\Spotlight\Instagram\Modules\InstagramModule;
use RebelCode\Spotlight\Instagram\Modules\MediaModule;
use RebelCode\Spotlight\Instagram\Modules\NewsModule;
use RebelCode\Spotlight\Instagram\Modules\NotificationsModule;
use RebelCode\Spotlight\Instagram\Modules\RestApiModule;
use RebelCode\Spotlight\Instagram\Modules\ShortcodeModule;
use RebelCode\Spotlight\Instagram\Modules\TokenRefresherModule;
use RebelCode\Spotlight\Instagram\Modules\UiModule;
use RebelCode\Spotlight\Instagram\Modules\WidgetModule;
use RebelCode\Spotlight\Instagram\Modules\WordPressModule;
use RebelCode\Spotlight\Instagram\Modules\WpBlockModule;

$modules = [
    'wp' => new WordPressModule(),
    'config' => new ConfigModule(),
    'ig' => new InstagramModule(),
    'feeds' => new FeedsModule(),
    'accounts' => new AccountsModule(),
    'media' => new MediaModule(),
    'importer' => new ImportCronModule(),
    'cleaner' => new CleanUpCronModule(),
    'token_refresher' => new TokenRefresherModule(),
    'rest_api' => new RestApiModule(),
    'ui' => new UiModule(),
    'shortcode' => new ShortcodeModule(),
    'wp_block' => new WpBlockModule(),
    'widget' => new WidgetModule(),
    'notifications' => new NotificationsModule(),
    'news' => new NewsModule(),
];

if (sliFreemius()->is__premium_only()) {
    if (sliFreemius()->is_plan_or_trial('pro')) {
        $modules = array_merge($modules, [
            'pro/ig' => new RebelCode\Spotlight\Instagram\Modules\Pro\ProIgApiModule(),
            'pro/media' => new RebelCode\Spotlight\Instagram\Modules\Pro\ProMediaModule(),
            'pro/ui' => new RebelCode\Spotlight\Instagram\Modules\Pro\ProUiModule(),
        ]);
    }
}

return $modules;
