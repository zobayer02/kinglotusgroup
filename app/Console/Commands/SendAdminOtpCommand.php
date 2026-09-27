<?php

namespace App\Console\Commands;

use App\Mail\AdminPasswordResetOtpMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAdminOtpCommand extends Command
{
    protected $signature = 'admin:send-otp {email} {otp}';

    protected $description = 'Send password reset OTP email via CLI';

    public function handle(): int
    {
        $email = strtolower(trim((string) $this->argument('email')));
        $otp = trim((string) $this->argument('otp'));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address provided.');
            return self::FAILURE;
        }

        if (! preg_match('/^[0-9]{6}$/', $otp)) {
            $this->error('Invalid OTP format.');
            return self::FAILURE;
        }

        try {
            Mail::to($email)->send(new AdminPasswordResetOtpMail($otp, 15));
            $this->info("OTP sent successfully to {$email}");
            Log::info("Admin password reset OTP email dispatched successfully to {$email}");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Failed to send email: " . $e->getMessage());
            Log::error("Failed to send OTP via CLI: " . $e->getMessage(), [
                'email' => $email,
            ]);
            return self::FAILURE;
        }
    }
}
