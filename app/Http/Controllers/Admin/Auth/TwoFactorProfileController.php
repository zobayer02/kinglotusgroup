<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Support\TotpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TwoFactorProfileController extends Controller
{
    public function enable(Request $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
        ], [
            'current_password.required' => 'Your current password is required to configure two-factor authentication.',
        ]);

        if (! Hash::check($request->input('current_password'), $admin->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        if ($admin->hasTwoFactorEnabled()) {
            return back()->with('error', 'Two-factor authentication is already enabled for your account.');
        }

        $secret = TotpService::generateSecret();
        $recoveryCodes = TotpService::generateRecoveryCodes(8);
        $otpauthUrl = TotpService::getOtpAuthUrl('King Lotus Group', $admin->email, $secret);

        $request->session()->put('mfa_setup_secret', $secret);
        $request->session()->put('mfa_setup_recovery_codes', $recoveryCodes);

        return back()
            ->with('mfa_enrolling', true)
            ->with('mfa_secret', $secret)
            ->with('mfa_otpauth_url', $otpauthUrl)
            ->with('mfa_recovery_codes', $recoveryCodes);
    }

    public function confirm(Request $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'Please enter the 6-digit code from your authenticator app.',
            'code.size' => 'The authentication code must be exactly 6 digits.',
        ]);

        $secret = (string) $request->session()->get('mfa_setup_secret');
        $recoveryCodes = $request->session()->get('mfa_setup_recovery_codes');

        if (empty($secret) || ! is_array($recoveryCodes)) {
            return back()->with('error', 'Two-factor setup expired or was not initiated. Please start setup again.');
        }

        if (! TotpService::verifyCode($secret, $request->input('code'))) {
            $otpauthUrl = TotpService::getOtpAuthUrl('King Lotus Group', $admin->email, $secret);

            return back()
                ->with('mfa_enrolling', true)
                ->with('mfa_secret', $secret)
                ->with('mfa_otpauth_url', $otpauthUrl)
                ->with('mfa_recovery_codes', $recoveryCodes)
                ->withErrors(['code' => 'The verification code was invalid. Please check your authenticator clock and try again.']);
        }

        $admin->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $recoveryCodes,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget(['mfa_setup_secret', 'mfa_setup_recovery_codes']);

        Log::notice('Admin enabled two-factor authentication', [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'ip' => $request->ip(),
        ]);

        return back()->with('success', 'Two-factor authentication has been successfully enabled.');
    }

    public function disable(Request $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
        ], [
            'current_password.required' => 'Your current password is required to disable two-factor authentication.',
        ]);

        if (! Hash::check($request->input('current_password'), $admin->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        $admin->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $request->session()->forget(['mfa_setup_secret', 'mfa_setup_recovery_codes']);

        Log::notice('Admin disabled two-factor authentication', [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'ip' => $request->ip(),
        ]);

        return back()->with('success', 'Two-factor authentication has been disabled.');
    }

    public function regenerateRecoveryCodes(Request $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
        ], [
            'current_password.required' => 'Your current password is required to regenerate recovery codes.',
        ]);

        if (! Hash::check($request->input('current_password'), $admin->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        if (! $admin->hasTwoFactorEnabled()) {
            return back()->with('error', 'Two-factor authentication is not enabled on this account.');
        }

        $newCodes = TotpService::generateRecoveryCodes(8);

        $admin->forceFill([
            'two_factor_recovery_codes' => $newCodes,
        ])->save();

        Log::notice('Admin regenerated MFA recovery codes', [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'ip' => $request->ip(),
        ]);

        return back()
            ->with('success', 'New recovery codes generated. Store them in a secure location.')
            ->with('new_recovery_codes', $newCodes);
    }
}
