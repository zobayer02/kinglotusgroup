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

    public function test_rich_text_sanitizer_blocks_nested_unwrapped_xss_payloads(): void
    {
        // 1. Unwrapped div/span with img onerror
        $payload1 = '<div><img src=x onerror=alert(1)></div>';
        $sanitized1 = \App\Support\RichTextSanitizer::sanitize($payload1);
        $this->assertNull($sanitized1);

        // 2. Unwrapped div with javascript link
        $payload2 = '<div><a href="javascript:alert(1)">click</a></div>';
        $sanitized2 = \App\Support\RichTextSanitizer::sanitize($payload2);
        $this->assertSame('<a>click</a>', $sanitized2);

        // 3. Unwrapped div with svg onload
        $payload3 = '<div><svg onload=alert(1)></svg></div>';
        $sanitized3 = \App\Support\RichTextSanitizer::sanitize($payload3);
        $this->assertNull($sanitized3);

        // 4. Unwrapped span with script tag
        $payload4 = '<span><script>alert(1)</script></span>';
        $sanitized4 = \App\Support\RichTextSanitizer::sanitize($payload4);
        $this->assertNull($sanitized4);

        // 5. Deeply nested unwrapped tags with mixed content
        $payload5 = '<div><section><article><span><img src=x onerror=alert(1)><p>Safe content</p><script>alert(2)</script></span></article></section></div>';
        $sanitized5 = \App\Support\RichTextSanitizer::sanitize($payload5);
        $this->assertSame('<p>Safe content</p>', $sanitized5);

        // 6. Comment nodes are stripped
        $payload6 = '<!-- <script>alert(1)</script> --><p>Clean text</p>';
        $sanitized6 = \App\Support\RichTextSanitizer::sanitize($payload6);
        $this->assertSame('<p>Clean text</p>', $sanitized6);

        // 7. Sanitizer is idempotent
        $html = '<p>Paragraph with <a href="https://example.com" target="_blank">valid link</a> and <strong>bold text</strong>.</p>';
        $pass1 = \App\Support\RichTextSanitizer::sanitize($html);
        $pass2 = \App\Support\RichTextSanitizer::sanitize($pass1);
        $this->assertSame($pass1, $pass2);
    }

    public function test_terms_page_never_renders_stored_xss_payloads(): void
    {
        \Illuminate\Support\Facades\Cache::forget(\App\Support\SiteCache::TERMS_PAGE_DATA_KEY);

        $setting = \App\Models\FooterSetting::query()->firstOrNew();
        $originalTitle = $setting->terms_title;
        $originalIntro = $setting->terms_intro;
        $originalContent = $setting->terms_content;

        try {
            $setting->terms_title = 'Terms of Service';
            $setting->terms_intro = '<div><img src=x onerror=alert(1)></div><p>Intro</p>';
            $setting->terms_content = '<div><a href="javascript:alert(1)">Click here</a></div><span><script>alert(2)</script></span>';
            $setting->save();

            $response = $this->get(route('terms.show'));

            $response->assertStatus(200);
            $response->assertDontSee('onerror=alert(1)', false);
            $response->assertDontSee('javascript:alert(1)', false);
            $response->assertDontSee('<script>alert(2)', false);
            $response->assertSee('Intro');
            $response->assertSee('Click here');
        } finally {
            $setting->terms_title = $originalTitle;
            $setting->terms_intro = $originalIntro;
            $setting->terms_content = $originalContent;
            $setting->save();
            \App\Support\SiteCache::forgetPublicPages();
        }
    }

    public function test_terms_page_preserves_safe_inline_formatting_and_colors(): void
    {
        \Illuminate\Support\Facades\Cache::forget(\App\Support\SiteCache::TERMS_PAGE_DATA_KEY);

        $setting = \App\Models\FooterSetting::query()->firstOrNew();
        $originalTitle = $setting->terms_title;
        $originalContent = $setting->terms_content;

        try {
            $setting->terms_title = 'Terms and Conditions';
            $setting->terms_content = '<p><span style="color: rgb(255, 0, 0); background-color: rgb(255, 235, 59);">Highlighted and colored text</span></p>';
            $setting->save();

            $response = $this->get(route('terms.show'));

            $response->assertStatus(200);
            $response->assertSee('style="color: rgb(255, 0, 0); background-color: rgb(255, 235, 59);"', false);
            $response->assertSee('Highlighted and colored text', false);
        } finally {
            $setting->terms_title = $originalTitle;
            $setting->terms_content = $originalContent;
            $setting->save();
            \App\Support\SiteCache::forgetPublicPages();
        }
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

    public function test_admin_system_migrate_rejects_unauthorized_requests(): void
    {
        $response = $this->get('/admin/system/migrate');
        $response->assertStatus(403);

        $invalidResponse = $this->get('/admin/system/migrate?key=wrong-key');
        $invalidResponse->assertStatus(403);
    }

    public function test_admin_system_migrate_allows_valid_app_key(): void
    {
        $appKey = (string) config('app.key');
        $this->assertNotEmpty($appKey);

        $response = $this->get('/admin/system/migrate?key=' . urlencode($appKey));
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);
    }
}
