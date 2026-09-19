<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', [
            'admin' => Auth::guard('admin')->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $emailChanged = $request->filled('email') && strtolower(trim((string) $request->input('email'))) !== strtolower((string) $admin->email);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(Admin::class, 'email')->ignore($admin->id),
            ],
            'current_password' => [
                Rule::requiredIf($emailChanged),
                'nullable',
                'current_password:admin',
            ],
            'mobile' => ['nullable', 'string', 'max:30'],
        ], [
            'current_password.required' => 'Your current password is required to change your email address.',
            'current_password.current_password' => 'Your current password is incorrect.',
        ]);

        unset($validated['current_password']);

        if (empty($validated['name'])) {
            $validated['name'] = $admin->name ?: 'Super Admin';
        }

        $admin->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'confirmed',
            ],
        ], [
            'current_password.current_password' => 'Your current password is incorrect.',
        ]);

        $admin->forceFill([
            'password' => $validated['password'],
            'session_version' => ((int) $admin->session_version) + 1,
        ])->save();

        $request->session()->put('admin_session_version', (int) $admin->session_version);
        $request->session()->put('admin_last_activity_at', now()->timestamp);

        return back()->with('success', 'Password updated successfully.');
    }

    public function logoutOtherDevices(Request $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $admin->forceFill([
            'session_version' => ((int) $admin->session_version) + 1,
        ])->save();

        $request->session()->put('admin_session_version', (int) $admin->session_version);
        $request->session()->put('admin_last_activity_at', now()->timestamp);

        return back()->with('success', 'All other admin sessions were logged out successfully.');
    }
}
