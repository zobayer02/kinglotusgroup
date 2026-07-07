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
            'a' => ['href'],
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
        for ($child = $node->firstChild; $child !== null; $child = $nextSibling) {
            $nextSibling = $child->nextSibling;

            if (! $child instanceof DOMElement) {
                continue;
            }

            $tagName = strtolower($child->tagName);

            if (! array_key_exists($tagName, $allowedTags)) {
                if (in_array($tagName, ['script', 'style', 'iframe', 'object', 'embed', 'svg', 'math'], true)) {
                    $node->removeChild($child);
                    continue;
                }

                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }

                $node->removeChild($child);
                continue;
            }

            $allowedAttributes = $allowedTags[$tagName];

            if ($child->hasAttributes()) {
                $attributesToRemove = [];

                foreach ($child->attributes as $attribute) {
                    $attributeName = strtolower($attribute->name);

                    if (! in_array($attributeName, $allowedAttributes, true)) {
                        $attributesToRemove[] = $attribute->name;
                        continue;
                    }

                    if ($tagName === 'a' && $attributeName === 'href' && ! self::isSafeHref($attribute->value)) {
                        $attributesToRemove[] = $attribute->name;
                    }
                }

                foreach ($attributesToRemove as $attributeName) {
                    $child->removeAttribute($attributeName);
                }
            }

            self::sanitizeNode($child, $allowedTags);
        }
    }

    protected static function isSafeHref(?string $href): bool
    {
        $href = trim((string) $href);

        if ($href === '') {
            return false;
        }

        if (str_starts_with($href, '#') || str_starts_with($href, '/')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($href, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https', 'mailto', 'tel'], true);
    }
}
