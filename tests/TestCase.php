<?php

namespace Tests;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase {
        refreshTestDatabase as baseRefreshTestDatabase;
    }

    /**
     * Every application connection participates in the per-test transaction.
     *
     * @var list<string>
     */
    protected $connectionsToTransact = ['sqlite', 'auth', 'content'];

    public function createApplication()
    {
        $testingEnvironment = [
            'APP_ENV' => 'testing',
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => ':memory:',
            'DB_AUTH_DRIVER' => 'sqlite',
            'DB_AUTH_DATABASE' => ':memory:',
            'DB_CONTENT_DRIVER' => 'sqlite',
            'DB_CONTENT_DATABASE' => ':memory:',
        ];

        foreach ($testingEnvironment as $key => $value) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }

        return parent::createApplication();
    }

    protected function setUp(): void
    {
        parent::setUp();

        Admin::query()->updateOrCreate(
            ['email' => 'superadmin@kinglotusgroup.com'],
            [
                'name' => 'Super Admin',
                'full_name' => 'A S M Zobayer',
                'password' => 'TestSecretPassword@123!',
                'role' => 'super_admin',
                'session_version' => 1,
            ]
        );
    }

    protected function refreshTestDatabase(): void
    {
        $this->ensureTestingDatabasesAreIsolated();
        $this->baseRefreshTestDatabase();
    }

    protected function ensureTestingDatabasesAreIsolated(): void
    {
        foreach ($this->connectionsToTransact as $connName) {
            $driver = config("database.connections.{$connName}.driver");
            $database = config("database.connections.{$connName}.database");

            if ($driver !== 'sqlite' || $database !== ':memory:') {
                throw new RuntimeException(
                    "UNSAFE TEST CONFIGURATION: Connection [{$connName}] must use an in-memory SQLite database."
                );
            }
        }
    }
}
