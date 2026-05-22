<?php

namespace App\Support\Security;

use DOMDocument;
use DOMElement;
use DOMXPath;

class HtmlSanitizer
{
    /**
     * Clean the given HTML content to prevent Cross-Site Scripting (XSS)
     */
    public static function clean(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Setup DOMDocument
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);

        // Prepend XML encoding structure to force UTF-8 parsing in DOMDocument
        $encodedHtml = '<?xml encoding="utf-8" ?>'.$html;
        $dom->loadHTML($encodedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // 1. Remove dangerous tags completely
        $dangerousTags = [
            'script',
            'iframe',
            'object',
            'embed',
            'applet',
            'meta',
            'link',
            'style',
            'form',
            'input',
            'button',
            'select',
            'textarea',
            'option',
            'svg',
            'canvas',
            'audio',
            'video',
            'base',
        ];

        foreach ($dangerousTags as $tag) {
            $elements = $dom->getElementsByTagName($tag);
            while ($elements->length > 0) {
                $element = $elements->item(0);
                if ($element && $element->parentNode) {
                    $element->parentNode->removeChild($element);
                }
            }
        }

        // 2. Remove dangerous attributes (e.g. event handlers like onload, onerror, and javascript: links)
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('//*');
        if ($nodes) {
            foreach ($nodes as $node) {
                if (! $node instanceof DOMElement) {
                    continue;
                }

                $attributesToRemove = [];

                foreach ($node->attributes as $attribute) {
                    $name = strtolower($attribute->name);
                    $value = strtolower(trim($attribute->value));

                    // Remove any attribute starting with 'on' (JavaScript event handlers)
                    if (str_starts_with($name, 'on')) {
                        $attributesToRemove[] = $attribute->name;
                    }

                    // Remove javascript:, data:, or vbscript: links in href, src, or action attributes
                    if (in_array($name, ['href', 'src', 'action'])) {
                        if (preg_match('/^(javascript|data|vbscript|onload|onerror):/i', $value)) {
                            $attributesToRemove[] = $attribute->name;
                        }
                    }
                }

                foreach ($attributesToRemove as $attrName) {
                    $node->removeAttribute($attrName);
                }
            }
        }

        // Save and cleanup HTML
        $cleanHtml = $dom->saveHTML();

        // Remove the temporary XML encoding declaration
        $cleanHtml = str_replace('<?xml encoding="utf-8" ?>', '', $cleanHtml);

        return trim($cleanHtml);
    }
}
