<?php

declare(strict_types=1);

$publicIndex = __DIR__.'/../public/index.php';
$publicRoot = dirname($publicIndex);
$runtimeStorage = sys_get_temp_dir().'/king-lotus-storage';

foreach ([
    $runtimeStorage,
    $runtimeStorage.'/framework',
    $runtimeStorage.'/framework/views',
    $runtimeStorage.'/framework/cache',
    $runtimeStorage.'/framework/cache/data',
    $runtimeStorage.'/framework/sessions',
    $runtimeStorage.'/logs',
] as $directory) {
    if (! is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
}

putenv('APP_STORAGE_PATH='.$runtimeStorage);
putenv('VIEW_COMPILED_PATH='.$runtimeStorage.'/framework/views');

$_SERVER['SCRIPT_FILENAME'] = $publicIndex;
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['DOCUMENT_ROOT'] = $publicRoot;

require $publicIndex;
