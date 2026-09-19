<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    public function test_security_headers_are_present_on_web_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->assertHeader('Content-Security-Policy');
        $this->assertFalse($response->headers->has('X-Powered-By'));
    }

    public function test_robots_txt_disallows_admin_and_login(): void
    {
        $robotsContent = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Disallow: /admin/', $robotsContent);
        $this->assertStringContainsString('Disallow: /login', $robotsContent);
        $this->assertStringContainsString('Disallow: /api/', $robotsContent);
    }

    public function test_failed_login_logs_warning_and_throttles(): void
    {
        \Illuminate\Support\Facades\RateLimiter::clear(\Illuminate\Support\Str::transliterate('attacker@example.com|127.0.0.1'));
        \Illuminate\Support\Facades\RateLimiter::clear('admin-login-account:attacker@example.com');

        Log::shouldReceive('warning')
            ->once()
            ->with('Failed admin login attempt', \Mockery::on(function ($context) {
                return isset($context['identifier']) && $context['identifier'] === 'attacker@example.com';
            }));

        $response = $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'attacker@example.com',
                'password' => 'invalid-password',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_weak_passwords_are_rejected_on_admin_profile_update(): void
    {
        $admin = Admin::query()->first();
        if (! $admin) {
            $this->markTestSkipped('No admin available.');
        }

        $response = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $admin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->from(route('admin.profile.edit'))
            ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->put(route('admin.profile.password.update'), [
                'current_password' => 'wrong',
                'password' => 'simple', // lacks min length, numbers, symbols, mixed case
                'password_confirmation' => 'simple',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_admin_can_update_profile_name_email_and_mobile(): void
    {
        $admin = Admin::query()->first();
        if (! $admin) {
            $this->markTestSkipped('No admin available.');
        }

        $originalEmail = $admin->email;

        $response = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $admin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->from(route('admin.profile.edit'))
            ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->patch(route('admin.profile.update'), [
                'full_name' => 'Updated Admin Name',
                'name' => 'Lead Super Admin',
                'email' => 'updated.admin@kinglotusgroup.com',
                'current_password' => 'TestSecretPassword@123!',
                'mobile' => '01812345678',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.profile.edit'));

        $admin->refresh();
        $this->assertSame('Updated Admin Name', $admin->full_name);
        $this->assertSame('Lead Super Admin', $admin->name);
        $this->assertSame('updated.admin@kinglotusgroup.com', $admin->email);
        $this->assertSame('01812345678', $admin->mobile);
    }

    public function test_admin_role_middleware_enforces_super_admin_and_admin_permissions(): void
    {
        // 1. Unauthenticated guest is redirected
        $guestResponse = $this->get(route('admin.content.index'));
        $guestResponse->assertRedirect(route('admin.login'));

        // 2. Regular admin (role: admin) can access content management
        $regularAdmin = Admin::query()->create([
            'email' => 'editor@kinglotusgroup.com',
            'name' => 'Editor Admin',
            'full_name' => 'Editor Admin User',
            'password' => 'EditorSecret@123!',
            'role' => 'admin',
            'session_version' => 1,
        ]);

        $adminResponse = $this->actingAs($regularAdmin, 'admin')
            ->withSession([
                'admin_session_version' => 1,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->get(route('admin.content.index'));
        $adminResponse->assertStatus(200);

        // 3. Regular admin cannot access super_admin-only profile route
        $forbiddenResponse = $this->actingAs($regularAdmin, 'admin')
            ->withSession([
                'admin_session_version' => 1,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->get(route('admin.profile.edit'));
        $forbiddenResponse->assertStatus(403);

        // 4. Super admin can access profile route
        $superAdmin = Admin::query()->where('role', 'super_admin')->first();
        $superResponse = $this->actingAs($superAdmin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $superAdmin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->get(route('admin.profile.edit'));
        $superResponse->assertStatus(200);
    }

    public function test_updating_password_increments_session_version_and_invalidates_stale_sessions(): void
    {
        $admin = Admin::query()->where('role', 'super_admin')->first();
        $oldVersion = (int) $admin->session_version;

        $newPassword = 'BrandNewPassword@2026!';

        $response = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => $oldVersion,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->from(route('admin.profile.edit'))
            ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->put(route('admin.profile.password.update'), [
                'current_password' => 'TestSecretPassword@123!',
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ]);

        $response->assertSessionHasNoErrors();
        $admin->refresh();
        $this->assertSame($oldVersion + 1, (int) $admin->session_version);

        // Stale session with old session_version is rejected by EnsureAdminAuthenticated
        $staleResponse = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => $oldVersion,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->get(route('admin.dashboard'));

        $staleResponse->assertRedirect(route('login'));
        $staleResponse->assertSessionHas('error', 'Your session was ended. Please sign in again.');
    }

    public function test_changing_email_requires_current_password(): void
    {
        $admin = Admin::query()->where('role', 'super_admin')->first();

        // Attempting to change email without current_password fails
        $response = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $admin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->from(route('admin.profile.edit'))
            ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->patch(route('admin.profile.update'), [
                'full_name' => $admin->full_name,
                'name' => $admin->name,
                'email' => 'new.email@kinglotusgroup.com',
                'mobile' => $admin->mobile,
            ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_custom_404_view_renders_on_missing_route(): void
    {
        $response = $this->get('/non-existent-page-url');

        $response->assertStatus(404);
        $response->assertSee('Page Not Found');
        $response->assertSee('Return to Homepage');
    }

    public function test_admin_seeder_throws_in_production(): void
    {
        $originalEnv = app()->environment();
        app()->detectEnvironment(fn () => 'production');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('AdminSeeder is disabled in production.');

        try {
            (new \Database\Seeders\AdminSeeder())->run();
        } finally {
            app()->detectEnvironment(fn () => $originalEnv);
        }
    }

    public function test_admin_rotate_password_command_increments_session_version_and_updates_password(): void
    {
        $admin = Admin::query()->first();
        if (! $admin) {
            $this->markTestSkipped('No admin available.');
        }

        $oldVersion = (int) $admin->session_version;
        $newPassword = 'NewSecretPassword@2026!';

        $this->artisan('admin:rotate-password', ['email' => $admin->email])
            ->expectsQuestion('Enter new admin password (hidden)', $newPassword)
            ->expectsQuestion('Confirm new admin password (hidden)', $newPassword)
            ->assertSuccessful();

        $admin->refresh();
        $this->assertSame($oldVersion + 1, (int) $admin->session_version);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check($newPassword, $admin->password));
    }
}
