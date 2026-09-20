<?php

declare(strict_types=1);

$publicIndex = __DIR__.'/../public/index.php';
$publicRoot = dirname($publicIndex);
$runtimeStorage = sys_get_temp_dir().'/king-lotus-storage';
$bootstrapCache = $runtimeStorage.'/bootstrap-cache';

foreach ([
    $runtimeStorage,
    $runtimeStorage.'/framework',
    $runtimeStorage.'/framework/views',
    $runtimeStorage.'/framework/cache',
    $runtimeStorage.'/framework/cache/data',
    $runtimeStorage.'/framework/sessions',
    $runtimeStorage.'/logs',
    $bootstrapCache,
] as $directory) {
    if (! is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
}

putenv('APP_STORAGE_PATH='.$runtimeStorage);
putenv('VIEW_COMPILED_PATH='.$runtimeStorage.'/framework/views');
putenv('APP_SERVICES_CACHE='.$bootstrapCache.'/services.php');
putenv('APP_PACKAGES_CACHE='.$bootstrapCache.'/packages.php');
putenv('APP_CONFIG_CACHE='.$bootstrapCache.'/config.php');
putenv('APP_ROUTES_CACHE='.$bootstrapCache.'/routes.php');
putenv('APP_EVENTS_CACHE='.$bootstrapCache.'/events.php');

$_ENV['APP_STORAGE_PATH'] = $runtimeStorage;
$_ENV['VIEW_COMPILED_PATH'] = $runtimeStorage.'/framework/views';
$_ENV['APP_SERVICES_CACHE'] = $bootstrapCache.'/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $bootstrapCache.'/packages.php';
$_ENV['APP_CONFIG_CACHE'] = $bootstrapCache.'/config.php';
$_ENV['APP_ROUTES_CACHE'] = $bootstrapCache.'/routes.php';
$_ENV['APP_EVENTS_CACHE'] = $bootstrapCache.'/events.php';

$_SERVER['APP_STORAGE_PATH'] = $runtimeStorage;
$_SERVER['VIEW_COMPILED_PATH'] = $runtimeStorage.'/framework/views';
$_SERVER['APP_SERVICES_CACHE'] = $bootstrapCache.'/services.php';
$_SERVER['APP_PACKAGES_CACHE'] = $bootstrapCache.'/packages.php';
$_SERVER['APP_CONFIG_CACHE'] = $bootstrapCache.'/config.php';
$_SERVER['APP_ROUTES_CACHE'] = $bootstrapCache.'/routes.php';
$_SERVER['APP_EVENTS_CACHE'] = $bootstrapCache.'/events.php';

$_SERVER['SCRIPT_FILENAME'] = $publicIndex;
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['DOCUMENT_ROOT'] = $publicRoot;

require $publicIndex;
