<?php

declare(strict_types=1);

$basePath = dirname(__DIR__);

$envFile = $basePath.'/.env';

if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);

        $name = trim($name);
        $value = trim($value);

        if ($name === '') {
            continue;
        }

        if (
            strlen($value) >= 2
            && (($value[0] === '"' && $value[-1] === '"') || ($value[0] === "'" && $value[-1] === "'"))
        ) {
            $value = substr($value, 1, -1);
        }

        if (getenv($name) === false || getenv($name) === '') {
            putenv($name.'='.$value);
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

$storagePath = getenv('APP_STORAGE_PATH') ?: null;

if (! $storagePath) {
    $defaultStoragePath = $basePath.'/storage';
    $defaultLogsPath = $defaultStoragePath.'/logs';
    $defaultSessionsPath = $defaultStoragePath.'/framework/sessions';

    if (! is_writable($defaultLogsPath) || ! is_writable($defaultSessionsPath)) {
        $storagePath = $basePath.'/.local-storage';
    } else {
        $storagePath = $defaultStoragePath;
    }
}

if (! is_dir($storagePath)) {
    mkdir($storagePath, 0777, true);
}

foreach ([
    $storagePath.'/app',
    $storagePath.'/framework',
    $storagePath.'/framework/cache',
    $storagePath.'/framework/cache/data',
    $storagePath.'/framework/sessions',
    $storagePath.'/framework/views',
    $storagePath.'/logs',
] as $directory) {
    if (! is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
}

putenv('APP_STORAGE_PATH='.$storagePath);
putenv('VIEW_COMPILED_PATH='.($storagePath.'/framework/views'));

$_ENV['APP_STORAGE_PATH'] = $storagePath;
$_ENV['VIEW_COMPILED_PATH'] = $storagePath.'/framework/views';
$_SERVER['APP_STORAGE_PATH'] = $storagePath;
$_SERVER['VIEW_COMPILED_PATH'] = $storagePath.'/framework/views';

return $storagePath;
