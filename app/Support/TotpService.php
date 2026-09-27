<?php

namespace App\Support;

class TotpService
{
    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Generate a new cryptographically secure Base32 secret (160 bits / 20 bytes).
     */
    public static function generateSecret(int $byteCount = 20): string
    {
        return self::base32Encode(random_bytes($byteCount));
    }

    /**
     * Calculate 6-digit TOTP code for a secret and timestamp (RFC 6238).
     */
    public static function generateCode(string $secret, ?int $timestamp = null): string
    {
        $timestamp = $timestamp ?? time();
        $counter = (int) floor($timestamp / 30);

        $secretBinary = self::base32Decode($secret);
        $binaryCounter = pack('N*', 0).pack('N*', $counter);
        $hash = hash_hmac('sha1', $binaryCounter, $secretBinary, true);

        $offset = ord(substr($hash, -1)) & 0x0F;
        $truncatedHash = substr($hash, $offset, 4);

        $value = unpack('N', $truncatedHash)[1] & 0x7FFFFFFF;
        $code = $value % 1000000;

        return str_pad((string) $code, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verify a 6-digit TOTP code with time drift window.
     */
    public static function verifyCode(string $secret, string $code, int $window = 1, ?int $timestamp = null): bool
    {
        $cleanCode = trim($code);
        if (strlen($cleanCode) !== 6 || ! ctype_digit($cleanCode)) {
            return false;
        }

        $timestamp = $timestamp ?? time();
        for ($i = -$window; $i <= $window; $i++) {
            $calculated = self::generateCode($secret, $timestamp + ($i * 30));
            if (hash_equals($calculated, $cleanCode)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate 8 unique formatted recovery codes (XXXXX-XXXXX).
     *
     * @return list<string>
     */
    public static function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $bytes = bin2hex(random_bytes(5));
            $codes[] = strtoupper(substr($bytes, 0, 5).'-'.substr($bytes, 5, 5));
        }

        return $codes;
    }

    /**
     * Generate standard otpauth URL for authenticator apps.
     */
    public static function getOtpAuthUrl(string $companyName, string $accountEmail, string $secret): string
    {
        $label = rawurlencode($companyName).':'.rawurlencode($accountEmail);
        $issuer = rawurlencode($companyName);

        return "otpauth://totp/{$label}?secret={$secret}&issuer={$issuer}&algorithm=SHA1&digits=6&period=30";
    }

    /**
     * Decode Base32 string to binary bytes.
     */
    public static function base32Decode(string $b32): string
    {
        $b32 = strtoupper(preg_replace('/[^A-Z2-7]/', '', $b32));
        $binaryString = '';
        $length = strlen($b32);

        for ($i = 0; $i < $length; $i++) {
            $pos = strpos(self::BASE32_ALPHABET, $b32[$i]);
            if ($pos === false) {
                continue;
            }
            $binaryString .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }

        $octets = str_split($binaryString, 8);
        $binary = '';
        foreach ($octets as $octet) {
            if (strlen($octet) === 8) {
                $binary .= chr((int) bindec($octet));
            }
        }

        return $binary;
    }

    /**
     * Encode binary bytes to Base32 string.
     */
    public static function base32Encode(string $data): string
    {
        $binaryString = '';
        $length = strlen($data);

        for ($i = 0; $i < $length; $i++) {
            $binaryString .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
        }

        $chunks = str_split($binaryString, 5);
        $b32 = '';
        foreach ($chunks as $chunk) {
            $padded = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            $b32 .= self::BASE32_ALPHABET[bindec($padded)];
        }

        return $b32;
    }
}
