<?php

namespace Tests\Feature;

use RuntimeException;
use Tests\TestCase;

class TestingDatabaseIsolationTest extends TestCase
{
    public function test_suite_rejects_any_non_sqlite_application_connection(): void
    {
        $original = config('database.connections.auth');

        try {
            config()->set('database.connections.auth.driver', 'mysql');
            config()->set('database.connections.auth.database', 'production_auth');

            $this->ensureTestingDatabasesAreIsolated();
            $this->fail('Unsafe non-SQLite test connection was accepted.');
        } catch (RuntimeException $exception) {
            $this->assertSame(
                'UNSAFE TEST CONFIGURATION: Connection [auth] must use an in-memory SQLite database.',
                $exception->getMessage()
            );
        } finally {
            config()->set('database.connections.auth', $original);
        }
    }

    public function test_all_application_connections_use_isolated_in_memory_sqlite(): void
    {
        foreach (['sqlite', 'auth', 'content'] as $connection) {
            $this->assertSame('sqlite', config("database.connections.{$connection}.driver"));
            $this->assertSame(':memory:', config("database.connections.{$connection}.database"));
        }
    }
}
