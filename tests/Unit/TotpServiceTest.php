<?php

namespace Tests\Unit;

use App\Support\TotpService;
use PHPUnit\Framework\TestCase;

class TotpServiceTest extends TestCase
{
    /**
     * RFC 6238 test vectors for HMAC-SHA1.
     * Secret: ASCII '12345678901234567890' (20 bytes).
     * In Base32: 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'
     */
    public function test_matches_official_rfc_6238_test_vectors(): void
    {
        $secret = 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ';

        $this->assertSame('287082', TotpService::generateCode($secret, 59));
        $this->assertSame('081804', TotpService::generateCode($secret, 1111111109));
        $this->assertSame('050471', TotpService::generateCode($secret, 1111111111));
        $this->assertSame('005924', TotpService::generateCode($secret, 1234567890));
        $this->assertSame('279037', TotpService::generateCode($secret, 2000000000));
    }

    public function test_base32_encode_and_decode_are_symmetric(): void
    {
        $binary = random_bytes(20);
        $encoded = TotpService::base32Encode($binary);
        $decoded = TotpService::base32Decode($encoded);

        $this->assertSame($binary, $decoded);
    }

    public function test_verify_code_accepts_valid_code_within_drift_window(): void
    {
        $secret = TotpService::generateSecret();
        $currentTime = 1700000000;

        $codeNow = TotpService::generateCode($secret, $currentTime);
        $this->assertTrue(TotpService::verifyCode($secret, $codeNow, 1, $currentTime));

        // Code from 30 seconds ago (window = 1)
        $codePast = TotpService::generateCode($secret, $currentTime - 30);
        $this->assertTrue(TotpService::verifyCode($secret, $codePast, 1, $currentTime));

        // Code from 30 seconds in future (window = 1)
        $codeFuture = TotpService::generateCode($secret, $currentTime + 30);
        $this->assertTrue(TotpService::verifyCode($secret, $codeFuture, 1, $currentTime));

        // Code from 90 seconds ago (beyond window = 1)
        $codeTooOld = TotpService::generateCode($secret, $currentTime - 90);
        $this->assertFalse(TotpService::verifyCode($secret, $codeTooOld, 1, $currentTime));

        // Invalid codes
        $this->assertFalse(TotpService::verifyCode($secret, '000000', 1, $currentTime));
        $this->assertFalse(TotpService::verifyCode($secret, 'abcdef', 1, $currentTime));
        $this->assertFalse(TotpService::verifyCode($secret, '123', 1, $currentTime));
    }

    public function test_recovery_codes_generation_format(): void
    {
        $codes = TotpService::generateRecoveryCodes(8);

        $this->assertCount(8, $codes);
        $this->assertCount(8, array_unique($codes));

        foreach ($codes as $code) {
            $this->assertMatchesRegularExpression('/^[A-Z0-9]{5}-[A-Z0-9]{5}$/', $code);
        }
    }

    public function test_otpauth_url_formatting(): void
    {
        $url = TotpService::getOtpAuthUrl('King Lotus Group', 'admin@example.com', 'JBSWY3DPEHPK3PXP');

        $this->assertStringStartsWith('otpauth://totp/', $url);
        $this->assertStringContainsString('secret=JBSWY3DPEHPK3PXP', $url);
        $this->assertStringContainsString('issuer=King%20Lotus%20Group', $url);
        $this->assertStringContainsString('digits=6', $url);
        $this->assertStringContainsString('period=30', $url);
    }
}
