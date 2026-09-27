<?php

namespace Tests\Feature;

use App\Mail\AdminPasswordResetOtpMail;
use App\Models\Admin;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    public function test_login_page_renders_share_owner_and_forgot_password_links(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Share Owner Login');
        $response->assertSee('https://kinglotusgroup.com/customer/login.php');
        $response->assertSee(route('password.request'));
        $response->assertSee('Forgot password?');
        $response->assertDontSee('Don’t have an account?');
        $response->assertDontSee('Email or mobile number');
    }

    public function test_forgot_password_page_renders_successfully(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Password Recovery');
        $response->assertSee('Send Verification Code');
        $response->assertSee(route('login'));
    }

    public function test_reset_password_page_renders_with_token(): void
    {
        $admin = Admin::query()->firstOrCreate(
            ['email' => 'test-reset@kinglotusgroup.com'],
            [
                'name' => 'Reset Test Admin',
                'password' => Hash::make('InitialPassword123!'),
                'role' => 'admin',
                'session_version' => 1,
            ]
        );

        $token = Password::broker('admins')->createToken($admin);

        $response = $this->get(route('password.reset', ['token' => $token, 'email' => $admin->email]));

        $response->assertStatus(200);
        $response->assertSee('Set New Password');
        $response->assertSee($token);
    }

    public function test_admin_can_reset_password_with_valid_token(): void
    {
        $admin = Admin::query()->firstOrCreate(
            ['email' => 'test-reset-user@kinglotusgroup.com'],
            [
                'name' => 'Reset Test User',
                'password' => Hash::make('OldPassword123!'),
                'role' => 'admin',
                'session_version' => 1,
            ]
        );

        $token = Password::broker('admins')->createToken($admin);

        $response = $this->withoutMiddleware(ValidateCsrfToken::class)->post(route('password.update'), [
            'token' => $token,
            'email' => $admin->email,
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');

        $admin->refresh();
        $this->assertTrue(Hash::check('NewSecurePassword123!', $admin->password));
        $this->assertGreaterThan(1, $admin->session_version);

        // Clean up
        $admin->delete();
    }

    public function test_admin_can_request_otp_and_verify_it_to_reset_password(): void
    {
        Mail::fake();

        $admin = Admin::query()->firstOrCreate(
            ['email' => 'otp-admin@kinglotusgroup.com'],
            [
                'name' => 'OTP Admin',
                'password' => Hash::make('OldSecretPassword123!'),
                'role' => 'admin',
                'session_version' => 1,
            ]
        );

        // Step 1: Request OTP
        $response = $this->withoutMiddleware(ValidateCsrfToken::class)->post(route('password.email'), [
            'email' => $admin->email,
        ]);

        $response->assertRedirect(route('password.verify-otp'));
        $response->assertSessionHas('status');
        $response->assertSessionHas('password_reset_email', $admin->email);

        Mail::assertSent(AdminPasswordResetOtpMail::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email);
        });

        // Verify OTP is stored in Cache
        $cacheData = Cache::get('admin_pwd_reset_otp:'.$admin->email);
        $this->assertNotNull($cacheData);

        // Step 2: Access Verify OTP form
        $verifyFormResponse = $this->withSession(['password_reset_email' => $admin->email])
            ->get(route('password.verify-otp'));
        $verifyFormResponse->assertStatus(200);
        $verifyFormResponse->assertSee('Verify Code');
        $verifyFormResponse->assertSee($admin->email);

        // Step 3: Submit invalid OTP
        $invalidOtpResponse = $this->withSession(['password_reset_email' => $admin->email])
            ->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('password.verify-otp.submit'), [
                'otp' => '000000',
            ]);
        $invalidOtpResponse->assertSessionHasErrors('otp');

        // Step 4: Submit valid OTP
        // We set known OTP in cache for predictable test
        $knownOtp = '789123';
        Cache::put('admin_pwd_reset_otp:'.$admin->email, [
            'hash' => Hash::make($knownOtp),
            'attempts' => 0,
        ], now()->addMinutes(15));

        $validOtpResponse = $this->withSession(['password_reset_email' => $admin->email])
            ->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('password.verify-otp.submit'), [
                'otp' => $knownOtp,
            ]);

        $validOtpResponse->assertRedirect();
        $this->assertNull(Cache::get('admin_pwd_reset_otp:'.$admin->email));

        // Get grant token from target URL
        $targetUrl = $validOtpResponse->headers->get('Location');
        $this->assertStringContainsString('reset-password', $targetUrl);
        parse_str(parse_url($targetUrl, PHP_URL_QUERY), $queryParams);
        $grantToken = $queryParams['token'] ?? null;
        if (! $grantToken) {
            // Or from path /reset-password/{token}
            $parts = explode('/', parse_url($targetUrl, PHP_URL_PATH));
            $grantToken = end($parts);
        }
        $this->assertNotEmpty($grantToken);
        $this->assertSame($admin->email, Cache::get('admin_pwd_grant:'.$grantToken));

        // Step 5: Update password using OTP grant token
        $resetResponse = $this->withoutMiddleware(ValidateCsrfToken::class)
            ->post(route('password.update'), [
                'token' => $grantToken,
                'email' => $admin->email,
                'password' => 'BrandNewPassword123!',
                'password_confirmation' => 'BrandNewPassword123!',
            ]);

        $resetResponse->assertRedirect(route('login'));
        $resetResponse->assertSessionHas('status');

        $admin->refresh();
        $this->assertTrue(Hash::check('BrandNewPassword123!', $admin->password));
        $this->assertGreaterThan(1, $admin->session_version);
        $this->assertNull(Cache::get('admin_pwd_grant:'.$grantToken));

        // Clean up
        $admin->delete();
    }

    public function test_verify_otp_page_does_not_contain_use_different_email_link(): void
    {
        $response = $this->withSession(['password_reset_email' => 'admin@kinglotusgroup.com'])
            ->get(route('password.verify-otp'));

        $response->assertStatus(200);
        $response->assertDontSee('Use a different email');
        $response->assertSee("Didn't receive the code? Resend Code", false);
        $response->assertSee('Back to Sign In');
    }

    public function test_password_reset_otp_uses_updated_email_from_admin_profile(): void
    {
        Mail::fake();

        $admin = Admin::query()->create([
            'name' => 'Original Admin',
            'full_name' => 'Original Admin Name',
            'email' => 'initial-profile-email@kinglotusgroup.com',
            'password' => Hash::make('Secret123!@#'),
            'role' => 'admin',
            'session_version' => 1,
        ]);

        // Simulate admin changing their email in Admin Portal
        $newEmail = 'updated-portal-email@kinglotusgroup.com';
        $admin->update(['email' => $newEmail]);

        // Request reset for old email should NOT send mail
        $this->withoutMiddleware(ValidateCsrfToken::class)->post(route('password.email'), [
            'email' => 'initial-profile-email@kinglotusgroup.com',
        ]);

        Mail::assertNotSent(AdminPasswordResetOtpMail::class);

        // Request reset for new updated portal email should send mail to new email
        $response = $this->withoutMiddleware(ValidateCsrfToken::class)->post(route('password.email'), [
            'email' => $newEmail,
        ]);

        $response->assertRedirect(route('password.verify-otp'));
        $response->assertSessionHas('password_reset_email', $newEmail);

        Mail::assertSent(AdminPasswordResetOtpMail::class, function ($mail) use ($newEmail) {
            return $mail->hasTo($newEmail);
        });

        // Clean up
        $admin->delete();
    }
}
