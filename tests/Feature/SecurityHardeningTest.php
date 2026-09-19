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
                'mobile' => '01812345678',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.profile.edit'));

        $admin->refresh();
        $this->assertSame('Updated Admin Name', $admin->full_name);
        $this->assertSame('Lead Super Admin', $admin->name);
        $this->assertSame('updated.admin@kinglotusgroup.com', $admin->email);
        $this->assertSame('01812345678', $admin->mobile);

        // Revert back
        $admin->update([
            'full_name' => 'A S M Zobayer',
            'name' => 'Super Admin',
            'email' => $originalEmail,
            'mobile' => '01700000000',
        ]);
    }
}
