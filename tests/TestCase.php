<?php

namespace Tests;

use App\Models\Admin;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    private static bool $migrated = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ensureTestingDatabasesAreIsolated();
        $this->setUpTestingDatabases();
    }

    protected function ensureTestingDatabasesAreIsolated(): void
    {
        $connections = [
            'default' => config('database.default'),
            'auth' => 'auth',
            'content' => 'content',
        ];

        foreach ($connections as $alias => $connName) {
            $driver = config("database.connections.{$connName}.driver");
            $database = config("database.connections.{$connName}.database");

            // Strictly guard against executing tests against non-test MySQL databases
            if ($driver === 'mysql') {
                $dbString = strtolower((string) $database);
                if (! str_ends_with($dbString, '_test') && ! str_ends_with($dbString, '_testing')) {
                    throw new RuntimeException(
                        "UNSAFE TEST CONFIGURATION: Connection [{$connName}] points to non-test MySQL database [{$database}]. Aborting to prevent data loss."
                    );
                }
            }
        }
    }

    protected function setUpTestingDatabases(): void
    {
        if (self::$migrated) {
            return;
        }

        foreach (['default', 'auth', 'content'] as $conn) {
            $driver = config("database.connections.{$conn}.driver");
            $dbPath = config("database.connections.{$conn}.database");

            if ($driver === 'sqlite' && $dbPath && $dbPath !== ':memory:') {
                $resolved = str_starts_with($dbPath, '/') || preg_match('/^[A-Za-z]:/', $dbPath)
                    ? $dbPath
                    : base_path($dbPath);

                File::ensureDirectoryExists(dirname($resolved));
                File::put($resolved, '');
            }
        }

        Artisan::call('migrate:fresh', ['--force' => true]);

        Admin::query()->firstOrCreate(
            ['email' => 'superadmin@kinglotusgroup.com'],
            [
                'name' => 'Super Admin',
                'full_name' => 'A S M Zobayer',
                'password' => 'TestSecretPassword@123!',
                'role' => 'super_admin',
                'session_version' => 1,
            ]
        );

        self::$migrated = true;
    }
}
