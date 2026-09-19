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

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertStringNotContainsString("'unsafe-eval'", $csp);
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("base-uri 'self'", $csp);
        $this->assertStringContainsString("form-action 'self'", $csp);

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

    public function test_rich_text_sanitizer_blocks_xss_and_unsafe_schemes(): void
    {
        // 1. Script, style, iframe, svg, math, form, input, and video are completely removed
        $maliciousHtml = '<p>Normal text</p><script>alert("xss")</script><svg onload="alert(1)"><circle r="10"/></svg><iframe src="https://evil.com"></iframe><form action="/steal"><input type="text"/></form><style>body { display:none; }</style>';
        $sanitized = \App\Support\RichTextSanitizer::sanitize($maliciousHtml);

        $this->assertStringNotContainsString('<script', $sanitized);
        $this->assertStringNotContainsString('alert', $sanitized);
        $this->assertStringNotContainsString('<svg', $sanitized);
        $this->assertStringNotContainsString('<iframe', $sanitized);
        $this->assertStringNotContainsString('<form', $sanitized);
        $this->assertStringNotContainsString('<style', $sanitized);
        $this->assertStringContainsString('<p>Normal text</p>', $sanitized);

        // 2. Event handlers on allowed tags are removed
        $eventHandlerHtml = '<p onclick="alert(1)" onmouseover="evil()">Paragraph with events</p><strong onload="bad()">Bold</strong>';
        $sanitizedEvents = \App\Support\RichTextSanitizer::sanitize($eventHandlerHtml);
        $this->assertStringNotContainsString('onclick', $sanitizedEvents);
        $this->assertStringNotContainsString('onmouseover', $sanitizedEvents);
        $this->assertStringNotContainsString('onload', $sanitizedEvents);
        $this->assertStringContainsString('<p>Paragraph with events</p>', $sanitizedEvents);
        $this->assertStringContainsString('<strong>Bold</strong>', $sanitizedEvents);

        // 3. Protocol-relative URLs and dangerous schemes are rejected
        $linksHtml = '<a href="//evil.com/phish">Protocol relative</a><a href="javascript:alert(1)">JS link</a><a href="data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==">Data link</a><a href="vbscript:msgbox(1)">VB link</a><a href="https://example.com/safe" target="_blank">Safe external link</a><a href="/safe-local-path">Safe internal link</a>';
        $sanitizedLinks = \App\Support\RichTextSanitizer::sanitize($linksHtml);

        $this->assertStringNotContainsString('//evil.com', $sanitizedLinks);
        $this->assertStringNotContainsString('javascript:', $sanitizedLinks);
        $this->assertStringNotContainsString('data:', $sanitizedLinks);
        $this->assertStringNotContainsString('vbscript:', $sanitizedLinks);
        $this->assertStringContainsString('href="https://example.com/safe"', $sanitizedLinks);
        $this->assertStringContainsString('rel="noopener noreferrer"', $sanitizedLinks);
        $this->assertStringContainsString('href="/safe-local-path"', $sanitizedLinks);
    }

    public function test_uploads_directory_has_htaccess_blocking_scripts(): void
    {
        $htaccessPath = public_path('uploads/.htaccess');
        $this->assertFileExists($htaccessPath);

        $content = file_get_contents($htaccessPath);
        $this->assertStringContainsString('Options -ExecCGI -Indexes', $content);
        $this->assertStringContainsString('Require all denied', $content);
        $this->assertStringContainsString('php_flag engine off', $content);
        $this->assertStringContainsString('RemoveHandler .php', $content);
    }

    public function test_uploader_ensures_upload_protection(): void
    {
        $uploader = new \App\Support\PublicWebpUploader();
        $uploader->ensureUploadProtection();

        $this->assertFileExists(public_path('uploads/.htaccess'));
    }

    public function test_url_validation_rejects_dangerous_schemes_and_untrusted_hosts(): void
    {
        $admin = Admin::query()->where('role', 'super_admin')->first();

        // 1. Non http/https schemes like javascript: or ftp: are rejected
        $response = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $admin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->patch(route('admin.content.about.update'), [
                'title' => 'About Us',
                'description' => 'Valid description',
                'left_video_url' => 'javascript:alert(1)',
            ]);

        $response->assertSessionHasErrors('left_video_url');

        // 2. Untrusted/lookalike hosts for YouTube are rejected
        $fakeYoutubeResponse = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $admin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->patch(route('admin.content.about.update'), [
                'title' => 'About Us',
                'description' => 'Valid description',
                'left_video_url' => 'https://evil-youtube.com/watch?v=12345',
            ]);

        $fakeYoutubeResponse->assertSessionHasErrors('left_video_url');

        // 3. Valid YouTube URL is accepted
        $validYoutubeResponse = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $admin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->patch(route('admin.content.about.update'), [
                'title' => 'About Us',
                'description' => 'Valid description',
                'left_video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ]);

        $validYoutubeResponse->assertSessionHasNoErrors();
    }

    public function test_shareholder_search_escapes_sql_wildcards(): void
    {
        \App\Models\ValuedShareholder::query()->create([
            'name' => 'John Doe Special',
            'position' => 'Lead Architect',
            'sort_order' => 1,
        ]);

        // Searching '%' should NOT match 'John Doe Special' because '%' is escaped as literal
        $queryPercent = \App\Models\ValuedShareholder::query()->search('%')->get();
        $this->assertFalse($queryPercent->contains('name', 'John Doe Special'));

        // Searching '_' should NOT match 'John Doe Special'
        $queryUnderscore = \App\Models\ValuedShareholder::query()->search('_')->get();
        $this->assertFalse($queryUnderscore->contains('name', 'John Doe Special'));

        // Searching exact name matches
        $queryMatch = \App\Models\ValuedShareholder::query()->search('John Doe')->get();
        $this->assertTrue($queryMatch->contains('name', 'John Doe Special'));
    }
}
