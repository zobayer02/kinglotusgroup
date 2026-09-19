<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create {--email=} {--name=} {--full_name=} {--role=super_admin}';

    protected $description = 'Interactively create a new admin with strong credentials';

    public function handle(): int
    {
        $email = $this->option('email') ?: $this->ask('Enter admin email address');
        $fullName = $this->option('full_name') ?: $this->ask('Enter admin full name');
        $name = $this->option('name') ?: $this->ask('Enter admin designation/title', 'Super Admin');
        $role = $this->option('role') ?: 'super_admin';

        $validator = Validator::make([
            'email' => $email,
            'full_name' => $fullName,
            'name' => $name,
            'role' => $role,
        ], [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(Admin::class, 'email')],
            'full_name' => ['required', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', Rule::in(['super_admin', 'admin'])],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $password = $this->secret('Enter admin password (hidden)');
        $confirmation = $this->secret('Confirm admin password (hidden)');

        if ($password !== $confirmation) {
            $this->error('Passwords do not match.');
            return self::FAILURE;
        }

        $passwordValidator = Validator::make(['password' => $password], [
            'password' => [
                'required',
                'string',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        if ($passwordValidator->fails()) {
            foreach ($passwordValidator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $admin = Admin::query()->create([
            'email' => strtolower(trim((string) $email)),
            'full_name' => trim((string) $fullName),
            'name' => trim((string) $name),
            'password' => $password,
            'role' => $role,
            'session_version' => 1,
        ]);

        $this->info("Admin [{$admin->email}] created successfully with role [{$admin->role}].");

        return self::SUCCESS;
    }
}
