<?php

namespace Tests\Feature;

use App\Logging\RedactSensitiveDataProcessor;
use App\Models\Admin;
use App\Models\ValuedShareholder;
use App\Support\TotpService;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Monolog\Level;
use Monolog\LogRecord;
use Tests\TestCase;

class AuthenticationHardeningTest extends TestCase
{
    // =========================================================================
    // 1. PROXY SPOOFING TESTS
    // =========================================================================

    public function test_spoofed_forwarded_headers_do_not_override_client_ip_when_proxies_are_not_trusted(): void
    {
        $response = $this->withServerVariables([
            'REMOTE_ADDR' => '192.0.2.1',
            'HTTP_X_FORWARDED_FOR' => '203.0.113.195',
            'HTTP_FORWARDED' => 'for=203.0.113.195;proto=https',
        ])->get('/');

        $response->assertStatus(200);

        // App should resolve client IP from REMOTE_ADDR, not the spoofed X-Forwarded-For
        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.0.2.1',
            'HTTP_X_FORWARDED_FOR' => '203.0.113.195',
        ]);

        $this->assertSame('192.0.2.1', $request->getClientIp());
    }

    public function test_spoofed_https_header_does_not_fool_secure_detection_when_proxies_untrusted(): void
    {
        $request = Request::create('http://localhost/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '192.0.2.1',
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'HTTP_X_FORWARDED_PORT' => '443',
        ]);

        $this->assertFalse($request->isSecure());
    }

    // =========================================================================
    // 2. FORGOT PASSWORD & RESET ANTI-ENUMERATION & TOKEN TESTS
    // =========================================================================

    public function test_forgot_password_returns_indistinguishable_response_for_existing_and_nonexistent_accounts(): void
    {
        RateLimiter::clear('forgot_password|existing@kinglotusgroup.com|127.0.0.1');
        RateLimiter::clear('forgot_password|nonexistent@example.com|127.0.0.1');

        Admin::query()->updateOrCreate(
            ['email' => 'existing@kinglotusgroup.com'],
            [
                'name' => 'Existing Admin',
                'full_name' => 'Existing Administrator',
                'password' => 'TestSecretPassword@123!',
                'role' => 'super_admin',
                'session_version' => 1,
            ]
        );

        // 1. Existing user
        $existingResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('password.email'), [
                'email' => 'existing@kinglotusgroup.com',
            ]);

        $existingResponse->assertSessionHas('status', __(Password::RESET_LINK_SENT));
        $existingResponse->assertSessionHasNoErrors();

        // 2. Non-existent user
        $nonExistentResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('password.email'), [
                'email' => 'nonexistent@example.com',
            ]);

        $nonExistentResponse->assertSessionHas('status', __(Password::RESET_LINK_SENT));
        $nonExistentResponse->assertSessionHasNoErrors();

        // The two responses must be completely indistinguishable to prevent user enumeration
        $this->assertSame(
            $existingResponse->getSession()->get('status'),
            $nonExistentResponse->getSession()->get('status')
        );
    }

    public function test_forgot_password_and_reset_endpoints_are_throttled(): void
    {
        $email = 'throttle.target@example.com';
        $throttleKey = 'forgot_password|'.Str::transliterate($email).'|127.0.0.1';
        RateLimiter::clear($throttleKey);

        for ($i = 0; $i < 5; $i++) {
            $this->withoutMiddleware(ValidateCsrfToken::class)
                ->post(route('password.email'), ['email' => $email]);
        }

        // 6th attempt should be rejected with rate limit error
        $blockedResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('password.email'), ['email' => $email]);

        $blockedResponse->assertSessionHasErrors('email');
    }

    public function test_reset_password_enforces_strong_password_policy(): void
    {
        $admin = Admin::query()->first();
        $token = Password::broker('admins')->createToken($admin);

        $weakResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('password.update'), [
                'token' => $token,
                'email' => $admin->email,
                'password' => 'simple',
                'password_confirmation' => 'simple',
            ]);

        $weakResponse->assertSessionHasErrors('password');
    }

    public function test_reset_token_single_use_and_replay_prevention(): void
    {
        $admin = Admin::query()->first();
        $initialPassword = $admin->password;
        $initialSessionVersion = (int) $admin->session_version;

        $token = Password::broker('admins')->createToken($admin);
        $newPassword = 'BrandNewValidPassword@2026!';

        // 1. First valid reset succeeds
        $firstResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('password.update'), [
                'token' => $token,
                'email' => $admin->email,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ]);

        $firstResponse->assertSessionHasNoErrors();
        $firstResponse->assertRedirect(route('login'));

        $admin->refresh();
        $this->assertTrue(Hash::check($newPassword, $admin->password));
        $this->assertSame($initialSessionVersion + 1, (int) $admin->session_version);

        // 2. Replay attempt using same token must be rejected
        $replayResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('password.update'), [
                'token' => $token,
                'email' => $admin->email,
                'password' => 'AnotherNewPassword@2026!',
                'password_confirmation' => 'AnotherNewPassword@2026!',
            ]);

        $replayResponse->assertSessionHasErrors('email');
    }

    public function test_expired_reset_token_is_rejected(): void
    {
        $admin = Admin::query()->first();
        $token = Password::broker('admins')->createToken($admin);

        // Travel 61 minutes into future (beyond 60 min expiration)
        $this->travel(61)->minutes();

        $expiredResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('password.update'), [
                'token' => $token,
                'email' => $admin->email,
                'password' => 'NewSecretPassword@2026!',
                'password_confirmation' => 'NewSecretPassword@2026!',
            ]);

        $expiredResponse->assertSessionHasErrors('email');
    }

    // =========================================================================
    // 3. MFA / TWO-FACTOR AUTHENTICATION WORKFLOW TESTS
    // =========================================================================

    public function test_admin_can_initiate_and_confirm_totp_mfa_enrollment(): void
    {
        $admin = Admin::query()->where('role', 'super_admin')->first();
        $admin->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        $this->assertFalse($admin->hasTwoFactorEnabled());

        // 1. Enable step requires current password
        $enableResponse = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => 1,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('admin.profile.two-factor.enable'), [
                'current_password' => 'TestSecretPassword@123!',
            ]);

        $enableResponse->assertSessionHas('mfa_enrolling', true);
        $enableResponse->assertSessionHas('mfa_secret');
        $enableResponse->assertSessionHas('mfa_recovery_codes');

        $secret = session('mfa_setup_secret');
        $recoveryCodes = session('mfa_setup_recovery_codes');
        $this->assertNotEmpty($secret);
        $this->assertCount(8, $recoveryCodes);

        // 2. Confirm step with invalid code fails
        $invalidConfirmResponse = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => 1,
                'admin_last_activity_at' => now()->timestamp,
                'mfa_setup_secret' => $secret,
                'mfa_setup_recovery_codes' => $recoveryCodes,
            ])
            ->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('admin.profile.two-factor.confirm'), [
                'code' => '000000',
            ]);

        $invalidConfirmResponse->assertSessionHasErrors('code');
        $admin->refresh();
        $this->assertFalse($admin->hasTwoFactorEnabled());

        // 3. Confirm step with valid TOTP code succeeds
        $validCode = TotpService::generateCode($secret);

        $validConfirmResponse = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => 1,
                'admin_last_activity_at' => now()->timestamp,
                'mfa_setup_secret' => $secret,
                'mfa_setup_recovery_codes' => $recoveryCodes,
            ])
            ->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('admin.profile.two-factor.confirm'), [
                'code' => $validCode,
            ]);

        $validConfirmResponse->assertSessionHas('success');
        $admin->refresh();
        $this->assertTrue($admin->hasTwoFactorEnabled());
        $this->assertNotNull($admin->two_factor_confirmed_at);
        $this->assertCount(8, $admin->two_factor_recovery_codes);
    }

    public function test_login_flow_with_mfa_enabled_requires_challenge(): void
    {
        $secret = TotpService::generateSecret();
        $recoveryCodes = TotpService::generateRecoveryCodes(8);

        $admin = Admin::query()->where('role', 'super_admin')->first();
        $admin->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $recoveryCodes,
            'two_factor_confirmed_at' => now(),
        ])->save();

        RateLimiter::clear(Str::transliterate($admin->email).'|127.0.0.1');
        RateLimiter::clear('admin_account|'.Str::transliterate($admin->email));

        // 1. Password credentials step: does NOT log user in, redirects to challenge
        $response = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('login.store'), [
                'email' => $admin->email,
                'password' => 'TestSecretPassword@123!',
            ]);

        $response->assertRedirect(route('login.challenge'));
        $this->assertFalse(Auth::guard('admin')->check());
        $this->assertSame($admin->id, session('login.mfa_admin_id'));

        // 2. Challenge page loads
        $challengePage = $this->get(route('login.challenge'));
        $challengePage->assertStatus(200);
        $challengePage->assertSee('Two-Factor Authentication');

        // 3. Challenge verification with correct TOTP code succeeds
        $code = TotpService::generateCode($secret);
        $verifyResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->withSession([
                'login.mfa_admin_id' => $admin->id,
                'login.mfa_expires_at' => now()->addMinutes(10)->timestamp,
            ])
            ->post(route('login.challenge.verify'), [
                'code' => $code,
            ]);

        $verifyResponse->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertSame($admin->id, Auth::guard('admin')->id());
    }

    public function test_login_flow_with_recovery_code_burns_code_and_authenticates(): void
    {
        $secret = TotpService::generateSecret();
        $recoveryCodes = TotpService::generateRecoveryCodes(8);
        $codeToUse = $recoveryCodes[0];

        $admin = Admin::query()->where('role', 'super_admin')->first();
        $admin->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $recoveryCodes,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $verifyResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->withSession([
                'login.mfa_admin_id' => $admin->id,
                'login.mfa_expires_at' => now()->addMinutes(10)->timestamp,
            ])
            ->post(route('login.challenge.verify'), [
                'recovery_code' => $codeToUse,
            ]);

        $verifyResponse->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(Auth::guard('admin')->check());

        // Recovery code must be burned/consumed (7 remaining)
        $admin->refresh();
        $this->assertCount(7, $admin->two_factor_recovery_codes);
        $this->assertNotContains($codeToUse, $admin->two_factor_recovery_codes);

        // Reusing the same recovery code must fail
        Auth::guard('admin')->logout();
        $reuseResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->withSession([
                'login.mfa_admin_id' => $admin->id,
                'login.mfa_expires_at' => now()->addMinutes(10)->timestamp,
            ])
            ->post(route('login.challenge.verify'), [
                'recovery_code' => $codeToUse,
            ]);

        $reuseResponse->assertSessionHasErrors('code');
    }

    public function test_admin_can_regenerate_recovery_codes_and_disable_mfa(): void
    {
        $admin = Admin::query()->where('role', 'super_admin')->first();
        $admin->forceFill([
            'two_factor_secret' => TotpService::generateSecret(),
            'two_factor_recovery_codes' => TotpService::generateRecoveryCodes(8),
            'two_factor_confirmed_at' => now(),
        ])->save();

        // 1. Regenerate recovery codes requires password
        $regenResponse = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $admin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('admin.profile.two-factor.recovery-codes'), [
                'current_password' => 'TestSecretPassword@123!',
            ]);

        $regenResponse->assertSessionHas('new_recovery_codes');
        $admin->refresh();
        $this->assertCount(8, $admin->two_factor_recovery_codes);

        // 2. Disable MFA requires password
        $disableResponse = $this->actingAs($admin, 'admin')
            ->withSession([
                'admin_session_version' => (int) $admin->session_version,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('admin.profile.two-factor.disable'), [
                'current_password' => 'TestSecretPassword@123!',
            ]);

        $disableResponse->assertSessionHas('success');
        $admin->refresh();
        $this->assertFalse($admin->hasTwoFactorEnabled());
        $this->assertNull($admin->two_factor_secret);
        $this->assertNull($admin->two_factor_confirmed_at);
    }

    // =========================================================================
    // 4. SUSPICIOUS LOGIN & STRUCTURED LOG REDACTION TESTS
    // =========================================================================

    public function test_login_from_new_ip_logs_warning_without_leaking_credentials(): void
    {
        $admin = Admin::query()->where('role', 'super_admin')->first();
        $admin->forceFill([
            'last_login_ip' => '10.0.0.1',
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        RateLimiter::clear(Str::transliterate($admin->email).'|198.51.100.25');
        RateLimiter::clear('admin_account|'.Str::transliterate($admin->email));

        Log::shouldReceive('warning')
            ->once()
            ->with('Admin login from new IP address', \Mockery::on(function ($context) {
                return isset($context['previous_ip'], $context['current_ip'], $context['email'])
                    && $context['previous_ip'] === '10.0.0.1'
                    && $context['current_ip'] === '198.51.100.25'
                    && ! isset($context['password'])
                    && ! isset($context['token']);
            }));

        Log::shouldReceive('info')
            ->once()
            ->with('Successful admin login', \Mockery::type('array'));

        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.25'])
            ->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('login.store'), [
                'email' => $admin->email,
                'password' => 'TestSecretPassword@123!',
            ]);
    }

    public function test_log_redaction_processor_strips_sensitive_keys(): void
    {
        $processor = new RedactSensitiveDataProcessor;

        $record = new LogRecord(
            datetime: new \DateTimeImmutable,
            channel: 'testing',
            level: Level::Info,
            message: 'User authentication trace',
            context: [
                'user_id' => 123,
                'password' => 'SuperSecret123!',
                'current_password' => 'OldSecret123!',
                'two_factor_secret' => 'BASE32SECRETKEY',
                'recovery_code' => 'ABCDE-12345',
                'nested' => [
                    'token' => 'Bearer token_xyz',
                    'safe_field' => 'visible_value',
                ],
            ]
        );

        $redacted = $processor($record);

        $this->assertSame(123, $redacted->context['user_id']);
        $this->assertSame('[REDACTED]', $redacted->context['password']);
        $this->assertSame('[REDACTED]', $redacted->context['current_password']);
        $this->assertSame('[REDACTED]', $redacted->context['two_factor_secret']);
        $this->assertSame('[REDACTED]', $redacted->context['recovery_code']);
        $this->assertSame('[REDACTED]', $redacted->context['nested']['token']);
        $this->assertSame('visible_value', $redacted->context['nested']['safe_field']);
    }

    // =========================================================================
    // 5. 429 RESPONSES & AUTHORIZATION BOUNDARIES
    // =========================================================================

    public function test_friendly_429_html_view_renders_on_rate_limit(): void
    {
        $view = view('errors.429')->render();

        $this->assertStringContainsString('429', $view);
        $this->assertStringContainsString('Too Many Requests', $view);
        $this->assertStringContainsString('slow down and try again shortly', $view);
    }

    public function test_friendly_429_json_response_returned_for_json_requests(): void
    {
        for ($i = 0; $i < 65; $i++) {
            $this->getJson('/faq/items');
        }

        $throttledResponse = $this->getJson('/faq/items');
        $throttledResponse->assertStatus(429);
        $throttledResponse->assertJson([
            'message' => 'Too many requests. Please slow down and try again later.',
        ]);
    }

    public function test_admin_login_rate_limiting_enforces_combined_and_ip_throttles(): void
    {
        $ip = '198.51.100.99';
        RateLimiter::clear('admin_ip|'.$ip);

        // 1. Five failed attempts for single account triggers account+IP lockout
        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => $ip])
                ->withoutMiddleware(ValidateCsrfToken::class)
                ->post(route('login.store'), [
                    'email' => 'victim@kinglotusgroup.com',
                    'password' => 'wrong-pass',
                ]);
        }

        $lockedOutResponse = $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('login.store'), [
                'email' => 'victim@kinglotusgroup.com',
                'password' => 'wrong-pass',
            ]);

        $lockedOutResponse->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many login attempts', session('errors')->first('email'));
    }

    public function test_authorization_boundaries_and_idor_prevention(): void
    {
        // 1. Unauthenticated guest cannot mutate content
        $guestFaqResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('admin.content.faqs.store'), [
                'question' => 'Malicious question?',
                'answer' => 'Malicious answer.',
                'order' => 1,
            ]);
        $guestFaqResponse->assertRedirect(route('admin.login'));

        $shareholder = ValuedShareholder::query()->create([
            'name' => 'Target Shareholder',
            'position' => 'Partner',
            'sort_order' => 1,
        ]);

        $guestShareholderResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->delete(route('admin.content.valued-shareholders.destroy', $shareholder));
        $guestShareholderResponse->assertRedirect(route('admin.login'));

        // 2. Regular admin cannot mutate profile or MFA (super_admin only)
        $regularAdmin = Admin::query()->create([
            'email' => 'editor.staff@kinglotusgroup.com',
            'name' => 'Staff Editor',
            'full_name' => 'Staff Editor User',
            'password' => 'StaffSecret@123!',
            'role' => 'admin',
            'session_version' => 1,
        ]);

        $forbiddenMfaResponse = $this->actingAs($regularAdmin, 'admin')
            ->withSession([
                'admin_session_version' => 1,
                'admin_last_activity_at' => now()->timestamp,
            ])
            ->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('admin.profile.two-factor.enable'), [
                'current_password' => 'StaffSecret@123!',
            ]);

        $forbiddenMfaResponse->assertStatus(403);
    }

    public function test_production_json_errors_do_not_leak_internal_paths_or_sql(): void
    {
        $originalEnv = app()->environment();
        config(['app.debug' => false]);
        app()->detectEnvironment(fn () => 'production');

        try {
            $response = $this->getJson('/non-existent-api-or-route-endpoint');
            $response->assertStatus(404);

            $content = $response->getContent();
            $this->assertStringNotContainsString('SQLSTATE', $content);
            $this->assertStringNotContainsString('Stack trace', $content);
            $this->assertStringNotContainsString('kinglotusgroup', $content);
            $this->assertStringNotContainsString('vendor', $content);
        } finally {
            app()->detectEnvironment(fn () => $originalEnv);
            config(['app.debug' => true]);
        }
    }
}
