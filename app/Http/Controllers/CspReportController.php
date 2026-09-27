<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class CspReportController extends Controller
{
    /**
     * Maximum allowed payload size for CSP reports (32 KB).
     */
    protected const MAX_PAYLOAD_BYTES = 32768;

    /**
     * Rate limit: maximum reports per minute per IP.
     */
    protected const MAX_REPORTS_PER_MINUTE = 60;

    /**
     * Handle incoming Content Security Policy (CSP) violation reports.
     */
    public function report(Request $request): Response|JsonResponse
    {
        $content = $request->getContent();

        // 1. Enforce payload size limit
        if (strlen($content) > self::MAX_PAYLOAD_BYTES) {
            return response()->json(['error' => 'Payload too large'], 413);
        }

        // 2. Enforce IP-based rate limiting
        $ip = $request->ip() ?: '127.0.0.1';
        $throttleKey = 'csp-report:' . $ip;

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_REPORTS_PER_MINUTE)) {
            return response()->json(['error' => 'Too many reports'], 429);
        }

        RateLimiter::hit($throttleKey, 60);

        // 3. Parse JSON
        $payload = json_decode($content, true);

        if (! is_array($payload)) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        // 4. Validate CSP report structure
        $reportData = $this->extractReportData($payload);

        if ($reportData === null) {
            return response()->json(['error' => 'Invalid CSP report structure'], 422);
        }

        // 5. Redact and sanitize report data
        $sanitized = $this->sanitizeReportData($reportData);

        // 6. Log the violation
        Log::warning('CSP violation reported', [
            'client_ip' => $ip,
            'report' => $sanitized,
        ]);

        return response()->noContent();
    }

    /**
     * Extract report array from either classic CSP report or modern Reporting API payload.
     */
    protected function extractReportData(array $payload): ?array
    {
        // Format A: Classic CSP report { "csp-report": { ... } }
        if (isset($payload['csp-report']) && is_array($payload['csp-report'])) {
            return $payload['csp-report'];
        }

        // Format B: Modern Reporting API [ { "type": "csp-violation", "body": { ... } } ]
        if (isset($payload[0]['type']) && $payload[0]['type'] === 'csp-violation' && isset($payload[0]['body']) && is_array($payload[0]['body'])) {
            return $payload[0]['body'];
        }

        return null;
    }

    /**
     * Sanitize and redact sensitive query parameters, URLs, and potential secrets.
     */
    protected function sanitizeReportData(array $report): array
    {
        $sanitized = [];

        $fields = [
            'document-uri' => 'document_uri',
            'documentURL' => 'document_uri',
            'referrer' => 'referrer',
            'violated-directive' => 'violated_directive',
            'effective-directive' => 'effective_directive',
            'effectiveDirective' => 'effective_directive',
            'original-policy' => 'original_policy',
            'originalPolicy' => 'original_policy',
            'disposition' => 'disposition',
            'blocked-uri' => 'blocked_uri',
            'blockedURL' => 'blocked_uri',
            'line-number' => 'line_number',
            'lineNumber' => 'line_number',
            'column-number' => 'column_number',
            'columnNumber' => 'column_number',
            'source-file' => 'source_file',
            'sourceFile' => 'source_file',
            'status-code' => 'status_code',
            'statusCode' => 'status_code',
            'script-sample' => 'script_sample',
            'sample' => 'script_sample',
        ];

        foreach ($fields as $key => $targetKey) {
            if (! isset($report[$key])) {
                continue;
            }

            $value = $report[$key];

            if (is_string($value)) {
                $value = $this->redactSensitiveUrlOrString($value);
            } elseif (is_numeric($value)) {
                $value = (int) $value;
            }

            $sanitized[$targetKey] = $value;
        }

        return $sanitized;
    }

    /**
     * Strip potential sensitive parameters (tokens, passwords, codes, keys) from URL strings.
     */
    protected function redactSensitiveUrlOrString(string $input): string
    {
        // Truncate overly long strings to prevent log inflation
        $truncated = Str::limit(trim($input), 1024);

        // Redact query parameter values that match sensitive names
        return preg_replace_callback('/([?&](?:token|password|secret|code|key|auth|session|recovery_code)=)([^&]*)/i', function ($matches) {
            return $matches[1] . '[REDACTED]';
        }, $truncated);
    }
}
