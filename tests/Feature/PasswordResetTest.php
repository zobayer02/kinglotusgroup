<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Hash;
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
        $response->assertSee('Send Reset Link');
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
}
