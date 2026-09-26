<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class RichTextSanitizer
{
    public static function sanitize(?string $html): ?string
    {
        if (! filled($html)) {
            return null;
        }

        $previousState = libxml_use_internal_errors(true);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<!DOCTYPE html><html><body>'.$html.'</body></html>';

        if (! $dom->loadHTML('<?xml encoding="utf-8" ?>'.$wrappedHtml, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED)) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousState);

            return null;
        }

        $body = $dom->getElementsByTagName('body')->item(0);

        if (! $body instanceof DOMElement) {
            libxml_clear_errors();
            libxml_use_internal_errors($previousState);

            return null;
        }

        $allowedTags = [
            'p' => ['style', 'class'],
            'br' => [],
            'strong' => ['style', 'class'],
            'b' => ['style', 'class'],
            'em' => ['style', 'class'],
            'i' => ['style', 'class'],
            'u' => ['style', 'class'],
            'span' => ['style', 'class'],
            'mark' => ['style', 'class'],
            'ul' => ['style', 'class'],
            'ol' => ['style', 'class'],
            'li' => ['style', 'class'],
            'a' => ['href', 'target', 'rel', 'style', 'class'],
            'h1' => ['style', 'class'],
            'h2' => ['style', 'class'],
            'h3' => ['style', 'class'],
            'h4' => ['style', 'class'],
            'blockquote' => ['style', 'class'],
            'table' => ['style', 'class'],
            'thead' => ['style', 'class'],
            'tbody' => ['style', 'class'],
            'tr' => ['style', 'class'],
            'th' => ['style', 'class'],
            'td' => ['style', 'class'],
        ];

        self::sanitizeNode($body, $allowedTags);

        $clean = '';

        foreach ($body->childNodes as $childNode) {
            $clean .= $dom->saveHTML($childNode);
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previousState);

        $clean = trim($clean);

        // Trim trailing empty paragraphs/breaks that might be created by editors
        $clean = preg_replace('/(<p[^>]*>(\s|&nbsp;|<br\s*\/?>)*<\/p>\s*)+$/i', '', $clean);
        $clean = trim((string) $clean);

        return filled($clean) ? $clean : null;
    }

    protected static function sanitizeNode(DOMNode $node, array $allowedTags): void
    {
        $unwrapTags = ['div', 'font', 'section', 'article', 'aside', 'header', 'footer', 'main'];

        for ($child = $node->firstChild; $child !== null; $child = $nextSibling) {
            $nextSibling = $child->nextSibling;

            if ($child->nodeType === XML_COMMENT_NODE) {
                $node->removeChild($child);
                continue;
            }

            if (! $child instanceof DOMElement) {
                continue;
            }

            $tagName = strtolower($child->tagName);

            if (! array_key_exists($tagName, $allowedTags)) {
                if (in_array($tagName, $unwrapTags, true)) {
                    self::sanitizeNode($child, $allowedTags);

                    while ($child->firstChild) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                }

                $node->removeChild($child);
                continue;
            }

            $allowedAttributes = $allowedTags[$tagName];

            if ($child->hasAttributes()) {
                $attributesToRemove = [];

                foreach ($child->attributes as $attribute) {
                    $attributeName = strtolower($attribute->name);

                    // Block any event handler attributes or non-allowed attributes
                    if (str_starts_with($attributeName, 'on') || ! in_array($attributeName, $allowedAttributes, true)) {
                        $attributesToRemove[] = $attribute->name;
                        continue;
                    }

                    if ($attributeName === 'style') {
                        $sanitizedStyle = self::sanitizeStyleAttribute($attribute->value);
                        if ($sanitizedStyle !== null) {
                            $child->setAttribute('style', $sanitizedStyle);
                        } else {
                            $attributesToRemove[] = $attribute->name;
                        }
                        continue;
                    }

                    if ($attributeName === 'class') {
                        $sanitizedClass = self::sanitizeClassAttribute($attribute->value);
                        if ($sanitizedClass !== null) {
                            $child->setAttribute('class', $sanitizedClass);
                        } else {
                            $attributesToRemove[] = $attribute->name;
                        }
                        continue;
                    }

                    if ($tagName === 'a') {
                        if ($attributeName === 'href' && ! self::isSafeHref($attribute->value)) {
                            $attributesToRemove[] = $attribute->name;
                        }

                        if ($attributeName === 'target' && ! in_array(strtolower($attribute->value), ['_blank', '_self'], true)) {
                            $attributesToRemove[] = $attribute->name;
                        }
                    }
                }

                foreach ($attributesToRemove as $attributeName) {
                    $child->removeAttribute($attributeName);
                }

                // If anchor opens in a new tab or is external, enforce rel="noopener noreferrer"
                if ($tagName === 'a' && $child->hasAttribute('href')) {
                    $target = strtolower((string) $child->getAttribute('target'));
                    $href = $child->getAttribute('href');
                    $isExternal = str_starts_with($href, 'http://') || str_starts_with($href, 'https://');

                    if ($target === '_blank' || $isExternal) {
                        $child->setAttribute('rel', 'noopener noreferrer');
                    }
                }
            }

            // If a span has no attributes (or its attributes were removed), unwrap it
            if ($tagName === 'span' && ! $child->hasAttributes()) {
                self::sanitizeNode($child, $allowedTags);

                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }

                $node->removeChild($child);
                continue;
            }

            self::sanitizeNode($child, $allowedTags);

            // Prune empty tags with only whitespace and no child elements (like empty <p>, <span>, <mark>, etc.)
            if (in_array($tagName, ['span', 'mark', 'p', 'h1', 'h2', 'h3', 'h4', 'blockquote'], true)
                && trim(str_replace(["\xc2\xa0", "\u{00a0}"], ' ', $child->textContent)) === ''
                && $child->getElementsByTagName('*')->length === 0) {
                $node->removeChild($child);
                continue;
            }
        }
    }

    public static function sanitizeStyleAttribute(?string $style): ?string
    {
        if (! filled($style)) {
            return null;
        }

        // Block control characters, angle brackets, null bytes
        if (preg_match('/[\x00-\x1F\x7F<>]/', $style)) {
            return null;
        }

        $normalized = strtolower($style);
        if (str_contains($normalized, 'url(') ||
            str_contains($normalized, 'expression(') ||
            str_contains($normalized, 'javascript:') ||
            str_contains($normalized, 'vbscript:') ||
            str_contains($normalized, 'data:') ||
            str_contains($normalized, '@import') ||
            str_contains($normalized, '-moz-binding') ||
            str_contains($normalized, 'behavior:')) {
            return null;
        }

        $allowedProperties = [
            'color',
            'background-color',
            'background',
            'font-size',
            'font-family',
            'font-weight',
            'font-style',
            'text-align',
            'text-decoration',
            'text-decoration-line',
            'text-decoration-color',
            'line-height',
            'letter-spacing',
        ];

        $declarations = explode(';', $style);
        $cleanDeclarations = [];

        foreach ($declarations as $declaration) {
            $declaration = trim($declaration);
            if ($declaration === '' || ! str_contains($declaration, ':')) {
                continue;
            }

            [$prop, $val] = explode(':', $declaration, 2);
            $prop = strtolower(trim($prop));
            $val = trim($val);

            // Strip !important if present, then re-check
            $valWithoutImportant = trim((string) preg_replace('/!\s*important$/i', '', $val));

            if (! in_array($prop, $allowedProperties, true)) {
                continue;
            }

            if (! self::isSafeCssValue($prop, $valWithoutImportant)) {
                continue;
            }

            $cleanDeclarations[] = $prop . ': ' . $val;
        }

        return ! empty($cleanDeclarations) ? implode('; ', $cleanDeclarations) . ';' : null;
    }

    protected static function isSafeCssValue(string $prop, string $val): bool
    {
        $val = trim($val);

        if ($val === '') {
            return false;
        }

        return match ($prop) {
            'color', 'background-color' => self::isSafeColor($val),
            'background' => self::isSafeColor($val),
            'text-align' => (bool) preg_match('/^(left|right|center|justify|start|end|inherit)$/i', $val),
            'font-size' => (bool) preg_match('/^(?:\d+(?:\.\d+)?(?:px|em|rem|%|pt)|small|medium|large|x-small|x-large|xx-small|xx-large|smaller|larger|inherit)$/i', $val),
            'font-weight' => (bool) preg_match('/^(bold|bolder|lighter|normal|[1-9]00|inherit)$/i', $val),
            'font-style' => (bool) preg_match('/^(normal|italic|oblique|inherit)$/i', $val),
            'text-decoration', 'text-decoration-line' => (bool) preg_match('/^(none|underline|overline|line-through|inherit)$/i', $val),
            'text-decoration-color' => self::isSafeColor($val),
            'font-family' => (bool) preg_match('/^[a-zA-Z0-9_\-\s,\'\"]+$/', $val),
            'line-height' => (bool) preg_match('/^(?:\d+(?:\.\d+)?(?:px|em|rem|%)?|normal|inherit)$/i', $val),
            'letter-spacing' => (bool) preg_match('/^(?:\-?\d+(?:\.\d+)?(?:px|em|rem)|normal|inherit)$/i', $val),
            default => false,
        };
    }

    protected static function isSafeColor(string $val): bool
    {
        $val = trim($val);

        // Hex: #fff, #ffffff, #ffffffff
        if (preg_match('/^#(?:[0-9a-fA-F]{3,4}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/', $val)) {
            return true;
        }

        // rgb / rgba
        if (preg_match('/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}(?:\s*,\s*(?:0|1|0?\.\d+|\d+%))?\s*\)$/i', $val)) {
            return true;
        }

        // hsl / hsla
        if (preg_match('/^hsla?\(\s*\d{1,3}(?:deg)?\s*,\s*\d{1,3}%\s*,\s*\d{1,3}%(?:\s*,\s*(?:0|1|0?\.\d+|\d+%))?\s*\)$/i', $val)) {
            return true;
        }

        // Named standard CSS colors (letters only, e.g. red, yellow, green, transparent, inherit)
        if (preg_match('/^[a-zA-Z]+$/', $val)) {
            return true;
        }

        return false;
    }

    public static function sanitizeClassAttribute(?string $class): ?string
    {
        if (! filled($class)) {
            return null;
        }

        if (! preg_match('/^[a-zA-Z0-9_\-\s]+$/', $class)) {
            return null;
        }

        $classes = array_filter(explode(' ', $class), fn ($c) => trim($c) !== '');

        return ! empty($classes) ? implode(' ', $classes) : null;
    }

    public static function isSafeHref(?string $href): bool
    {
        $href = trim((string) $href);

        if ($href === '') {
            return false;
        }

        // Disallow null bytes or ASCII control characters
        if (preg_match('/[\x00-\x1F\x7F]/', $href)) {
            return false;
        }

        // Relative in-page anchors
        if (str_starts_with($href, '#')) {
            return true;
        }

        // Disallow protocol-relative URLs (e.g. //evil.com or /\\evil.com)
        if (str_starts_with($href, '//') || str_starts_with($href, '/\\')) {
            return false;
        }

        // Root-relative internal paths (e.g. /terms, /about)
        if (str_starts_with($href, '/')) {
            return true;
        }

        // Check for dangerous schemes before parsing (including spaces/entities)
        $normalized = strtolower(preg_replace('/\s+/', '', $href));
        if (str_starts_with($normalized, 'javascript:') ||
            str_starts_with($normalized, 'data:') ||
            str_starts_with($normalized, 'vbscript:')) {
            return false;
        }

        $parts = parse_url($href);
        if ($parts === false || ! isset($parts['scheme'])) {
            return false;
        }

        $scheme = strtolower($parts['scheme']);

        if (! in_array($scheme, ['http', 'https', 'mailto', 'tel'], true)) {
            return false;
        }

        if (in_array($scheme, ['http', 'https'], true) && empty($parts['host'])) {
            return false;
        }

        return true;
    }
}
