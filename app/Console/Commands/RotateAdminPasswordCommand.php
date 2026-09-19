<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RotateAdminPasswordCommand extends Command
{
    protected $signature = 'admin:rotate-password {email? : The email address of the admin}';

    protected $description = 'Rotate an admin password and revoke all active sessions';

    public function handle(): int
    {
        $email = $this->argument('email') ?: $this->ask('Enter the admin email address');

        /** @var Admin|null $admin */
        $admin = Admin::query()->where('email', strtolower(trim((string) $email)))->first();

        if (! $admin) {
            $this->error("No admin found with email [{$email}].");
            return self::FAILURE;
        }

        $password = $this->secret('Enter new admin password (hidden)');
        $confirmation = $this->secret('Confirm new admin password (hidden)');

        if ($password !== $confirmation) {
            $this->error('Passwords do not match.');
            return self::FAILURE;
        }

        $validator = Validator::make(['password' => $password], [
            'password' => [
                'required',
                'string',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $admin->forceFill([
            'password' => $password,
            'session_version' => ((int) $admin->session_version) + 1,
        ])->save();

        $this->info("Password for admin [{$admin->email}] was rotated successfully.");
        $this->info("All active sessions across all devices have been revoked (session_version: {$admin->session_version}).");

        return self::SUCCESS;
    }
}
