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
            'p' => [],
            'br' => [],
            'strong' => [],
            'b' => [],
            'em' => [],
            'i' => [],
            'u' => [],
            'ul' => [],
            'ol' => [],
            'li' => [],
            'a' => ['href', 'target', 'rel'],
            'h2' => [],
            'h3' => [],
            'blockquote' => [],
        ];

        self::sanitizeNode($body, $allowedTags);

        $clean = '';

        foreach ($body->childNodes as $childNode) {
            $clean .= $dom->saveHTML($childNode);
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previousState);

        $clean = trim($clean);

        return filled($clean) ? $clean : null;
    }

    protected static function sanitizeNode(DOMNode $node, array $allowedTags): void
    {
        $unwrapTags = ['span', 'div', 'font', 'section', 'article', 'aside', 'header', 'footer', 'main'];

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

            self::sanitizeNode($child, $allowedTags);
        }
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
