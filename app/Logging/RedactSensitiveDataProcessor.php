<?php

namespace App\Logging;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class RedactSensitiveDataProcessor implements ProcessorInterface
{
    private const SENSITIVE_PATTERNS = [
        'password',
        'password_confirmation',
        'current_password',
        'secret',
        'two_factor_secret',
        'recovery_code',
        'recovery_codes',
        'two_factor_recovery_codes',
        'token',
        'reset_token',
        'remember_token',
        'api_key',
        'authorization',
        'cookie',
        'app_key',
        'db_password',
        'mail_password',
    ];

    public function __invoke(LogRecord $record): LogRecord
    {
        if (empty($record->context)) {
            return $record;
        }

        $redactedContext = $this->redactArray($record->context);

        return $record->with(context: $redactedContext);
    }

    /**
     * Recursively redact sensitive keys from context array.
     */
    private function redactArray(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if ($this->isSensitiveKey((string) $key)) {
                $result[$key] = '[REDACTED]';

                continue;
            }

            if (is_array($value)) {
                $result[$key] = $this->redactArray($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    private function isSensitiveKey(string $key): bool
    {
        $lower = strtolower($key);

        foreach (self::SENSITIVE_PATTERNS as $pattern) {
            if (str_contains($lower, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
