<?php

namespace App\Models;

use App\Support\RichTextSanitizer;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    public const DEFAULT_LOCATION_SHORT_URL = 'https://maps.app.goo.gl/23NPMkWX8JADhU7t9';
    public const DEFAULT_LOCATION_PLACE_URL = 'https://www.google.com/maps/place/King+lotus+international+Ltd./@21.1076968,92.1070609,669m/data=!3m2!1e3!4b1!4m6!3m5!1s0x30addf00197c656b:0xf3100839a3876cb3!8m2!3d21.1076918!4d92.1096358!16s%2Fg%2F11z4t41lgh!5m1!1e1';
    public const DEFAULT_LOCATION_EMBED_URL = 'https://www.google.com/maps?q=21.1076918,92.1096358&z=17&output=embed';
    public const DEFAULT_OFFICE_SECTION_TITLE = 'Get A Quote - No Cost, No Commitment';
    public const DEFAULT_OFFICE_SECTION_SUBTITLE = 'Transparent & Competitive Rates';
    public const DEFAULT_OFFICE_NAME = 'Head Office';
    public const DEFAULT_OFFICE_ADDRESS = "King Lotus International Ltd.\n4455+3V Monakhali, Bangladesh";

    protected $connection = 'content';

    protected $fillable = [
        'youtube_url',
        'facebook_url',
        'contact_email',
        'contact_phone',
        'location_title',
        'location_subtitle',
        'location_map_url',
        'office_section_title',
        'office_section_subtitle',
        'office_name',
        'office_address',
        'office_map_url',
        'office_cards',
        'terms_title',
        'terms_subtitle',
        'terms_intro',
        'terms_content',
    ];

    protected $casts = [
        'office_cards' => 'array',
    ];

    public function hasPublicLinks(): bool
    {
        return filled($this->youtube_url)
            || filled($this->facebook_url)
            || filled($this->contact_email)
            || filled($this->contact_phone);
    }

    public function hasLocationContent(): bool
    {
        return filled($this->location_title)
            || filled($this->location_subtitle)
            || filled($this->location_map_url);
    }

    public function hasOfficeContent(): bool
    {
        return filled($this->office_section_title)
            || filled($this->office_section_subtitle)
            || collect($this->officeCards())->isNotEmpty();
    }

    public function emailHref(): ?string
    {
        return filled($this->contact_email) ? 'mailto:'.$this->contact_email : null;
    }

    public function phoneOptions(): array
    {
        if (! filled($this->contact_phone)) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', (string) $this->contact_phone) ?: [];
        $options = [];

        foreach ($lines as $line) {
            $label = trim($line);

            if ($label === '') {
                continue;
            }

            $normalized = preg_replace('/[^0-9+]/', '', $label);

            if (! filled($normalized)) {
                continue;
            }

            $options[] = [
                'label' => $label,
                'href' => 'tel:'.$normalized,
            ];
        }

        return $options;
    }

    public function phoneHref(): ?string
    {
        return $this->phoneOptions()[0]['href'] ?? null;
    }

    public function locationEmbedUrl(): ?string
    {
        if (! filled($this->location_map_url)) {
            return null;
        }

        $source = trim((string) $this->location_map_url);

        if ($source === '') {
            return null;
        }

        if ($source === self::DEFAULT_LOCATION_SHORT_URL || $source === self::DEFAULT_LOCATION_PLACE_URL) {
            return self::DEFAULT_LOCATION_EMBED_URL;
        }

        if (str_contains($source, 'output=embed') || str_contains($source, '/maps/embed')) {
            return $source;
        }

        $parts = parse_url($source);
        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = (string) ($parts['path'] ?? '');
        $query = [];
        parse_str((string) ($parts['query'] ?? ''), $query);

        $mapQuery = null;

        foreach (['q', 'query', 'destination'] as $key) {
            if (filled($query[$key] ?? null)) {
                $mapQuery = trim((string) $query[$key]);
                break;
            }
        }

        if (! filled($mapQuery) && preg_match('#/(place|search)/([^/?]+)#i', $path, $matches) === 1) {
            $mapQuery = trim(str_replace('+', ' ', urldecode($matches[2])));
        }

        if (! filled($mapQuery) && preg_match('/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/', $source, $matches) === 1) {
            $mapQuery = $matches[1].','.$matches[2];
        }

        if (! filled($mapQuery) && in_array($host, ['maps.app.goo.gl', 'goo.gl'], true)) {
            $mapQuery = $source;
        }

        if (! filled($mapQuery)) {
            $mapQuery = $source;
        }

        return 'https://www.google.com/maps?q='.rawurlencode($mapQuery).'&z=15&output=embed';
    }

    public function locationPlaceName(): ?string
    {
        $source = filled($this->location_map_url)
            ? trim((string) $this->location_map_url)
            : self::DEFAULT_LOCATION_PLACE_URL;

        if ($source === '' || $source === self::DEFAULT_LOCATION_SHORT_URL || $source === self::DEFAULT_LOCATION_PLACE_URL) {
            return 'King lotus international Ltd.';
        }

        $parts = parse_url($source);
        $path = (string) ($parts['path'] ?? '');
        $query = [];
        parse_str((string) ($parts['query'] ?? ''), $query);

        if (preg_match('#/place/([^/@?]+)#i', $path, $matches) === 1) {
            return trim(str_replace('+', ' ', urldecode($matches[1])));
        }

        foreach (['q', 'query'] as $key) {
            if (filled($query[$key] ?? null)) {
                return trim((string) $query[$key]);
            }
        }

        return null;
    }

    public function officeMapUrl(): string
    {
        $source = filled($this->office_map_url)
            ? trim((string) $this->office_map_url)
            : '';

        if ($source === '' || $source === self::DEFAULT_LOCATION_SHORT_URL) {
            return self::DEFAULT_LOCATION_PLACE_URL;
        }

        return $source;
    }

    public function officeCards(): array
    {
        if (filled($this->office_cards)) {
            return $this->normalizeOfficeCards($this->office_cards);
        }

        return $this->legacyOfficeCards();
    }

    public function officeCardsForEditor(): array
    {
        return collect($this->officeCards())
            ->map(fn (array $office): array => [
                'name' => $office['name'] ?? '',
                'address' => $office['address'] ?? '',
                'map_url' => $office['map_url'] ?? '',
                'phone' => $office['phone'] ?? '',
                'email' => $office['email'] ?? '',
            ])
            ->values()
            ->all();
    }

    protected function legacyOfficeCards(): array
    {
        return $this->normalizeOfficeCards([[
            'name' => $this->office_name ?? '',
            'address' => $this->office_address ?? '',
            'map_url' => $this->officeMapUrl(),
        ]]);
    }

    protected function normalizeOfficeCards(?array $cards): array
    {
        return collect($cards ?? [])
            ->map(function ($office): array {
                $data = is_array($office) ? $office : [];

                return [
                    'name' => trim((string) ($data['name'] ?? '')),
                    'address' => trim((string) ($data['address'] ?? '')),
                    'map_url' => trim((string) ($data['map_url'] ?? '')),
                    'phone' => trim((string) ($data['phone'] ?? '')),
                    'email' => trim((string) ($data['email'] ?? '')),
                    'phone_href' => $this->phoneLink($data['phone'] ?? null),
                    'email_href' => $this->emailLink($data['email'] ?? null),
                ];
            })
            ->filter(fn (array $office) => filled($office['name']) || filled($office['address']) || filled($office['map_url']) || filled($office['phone']) || filled($office['email']))
            ->values()
            ->all();
    }

    protected function phoneLink(mixed $value): ?string
    {
        $phone = trim((string) $value);

        if ($phone === '') {
            return null;
        }

        $normalized = preg_replace('/[^0-9+]/', '', $phone);

        return filled($normalized) ? 'tel:'.$normalized : null;
    }

    protected function emailLink(mixed $value): ?string
    {
        $email = trim((string) $value);

        return $email !== '' ? 'mailto:'.$email : null;
    }

    public function hasTermsContent(): bool
    {
        return filled($this->terms_title)
            || filled($this->terms_subtitle)
            || filled($this->terms_intro)
            || filled($this->terms_content);
    }

    public function sanitizedTermsHtml(): ?string
    {
        $blocks = collect([
            RichTextSanitizer::sanitize($this->terms_intro),
            RichTextSanitizer::sanitize($this->terms_content),
        ])->filter();

        return $blocks->isNotEmpty() ? $blocks->implode("\n") : null;
    }
}
