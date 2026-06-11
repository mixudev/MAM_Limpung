<?php

namespace App\Support\Security;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * HtmlSanitizer — Membersihkan HTML dari XSS menggunakan HTMLPurifier.
 *
 * Menggantikan implementasi DOMDocument kustom yang memiliki beberapa bypass vector:
 *   - srcset / formaction / ping attributes tidak terfilter
 *   - mXSS (Mutation XSS) via DOMDocument re-serialization
 *   - Encoding bypass (&#x69; dll)
 *   - Alpine.js attribute injection (x-bind, @click)
 *
 * HTMLPurifier adalah library battle-tested untuk sanitasi HTML di PHP
 * dan digunakan secara luas di industri (MediaWiki, Yii, dsb).
 */
class HtmlSanitizer
{
    /**
     * Bersihkan HTML dari XSS menggunakan HTMLPurifier.
     *
     * Mengizinkan tag dan attribute yang umum digunakan di konten artikel/galeri,
     * sekaligus memblokir semua script, event handler, dan URI berbahaya.
     */
    public static function clean(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        $config = HTMLPurifier_Config::createDefault();

        // -----------------------------------------------------------------------
        //  Custom HTML Definition ID + Rev — wajib saat mendaftarkan elemen custom.
        //  Rev harus dinaikkan setiap kali definisi berubah agar cache di-rebuild.
        // -----------------------------------------------------------------------
        $config->set('HTML.DefinitionID', 'mam-limpung-editor');
        $config->set('HTML.DefinitionRev', 3);

        // -----------------------------------------------------------------------
        //  Daftar elemen yang diizinkan — tanpa definisi attribute inline.
        //  Attribute per-elemen didaftarkan terpisah via HTML.AllowedAttributes
        //  agar tidak konflik dengan HTMLDefinition custom di bawah.
        // -----------------------------------------------------------------------
        $config->set('HTML.AllowedElements', implode(',', [
            'p', 'br', 'hr',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'b', 'strong', 'i', 'em', 'u', 's', 'small', 'sub', 'sup',
            'del', 'ins', 'mark',
            'div', 'span', 'blockquote', 'pre', 'code',
            'ul', 'ol', 'li',
            'a', 'img',
            'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption',
            'iframe',
            'figure', 'figcaption',
        ]));

        // -----------------------------------------------------------------------
        //  Daftar attribute yang diizinkan — format 'elemen.attr' atau '*.attr'
        //  Hanya attribute yang sudah dikenal HTMLPurifier.
        //  Attribute non-standard (loading, allowfullscreen, frameborder) didaftarkan
        //  via HTMLDefinition addAttribute() di bawah.
        // -----------------------------------------------------------------------
        $config->set('HTML.AllowedAttributes', implode(',', [
            // Global
            '*.class', '*.id', '*.style',
            // Tautan
            'a.href', 'a.title', 'a.target', 'a.rel',
            // Gambar
            'img.src', 'img.alt', 'img.width', 'img.height',
            // Tabel
            'th.colspan', 'th.rowspan', 'th.scope',
            'td.colspan', 'td.rowspan',
            // Iframe (hanya attribute standar HTML4 — non-standard didaftarkan via addAttribute)
            'iframe.src', 'iframe.width', 'iframe.height', 'iframe.title',
        ]));

        // -----------------------------------------------------------------------
        //  URI — hanya http dan https yang diizinkan
        //  Memblokir: javascript:, data:, vbscript:, blob:, dll.
        // -----------------------------------------------------------------------
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
        ]);

        // -----------------------------------------------------------------------
        //  Iframe — hanya izinkan dari domain yang dipercaya (YouTube, Google Maps)
        // -----------------------------------------------------------------------
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^https://(www\.youtube(?:-nocookie)?\.com/embed/|www\.google\.com/maps/embed)%');

        // -----------------------------------------------------------------------
        //  Attribute style — batasi properti CSS yang diizinkan
        //  Mencegah CSS injection seperti: expression(), url(javascript:), dll.
        // -----------------------------------------------------------------------
        $config->set('CSS.AllowedProperties', [
            'font-weight', 'font-style', 'font-size', 'font-family',
            'text-align', 'text-decoration', 'text-transform',
            'color', 'background-color',
            'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
            'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
            'border', 'border-radius',
            'width', 'max-width', 'height',
            'display', 'float', 'clear',
            'line-height', 'letter-spacing',
        ]);

        // -----------------------------------------------------------------------
        //  Atribut rel="noopener noreferrer" otomatis pada link target="_blank"
        // -----------------------------------------------------------------------
        $config->set('HTML.TargetBlank', true);
        $config->set('HTML.TargetNoreferrer', true);
        $config->set('HTML.TargetNoopener', true);

        // -----------------------------------------------------------------------
        //  Encoding — selalu UTF-8
        // -----------------------------------------------------------------------
        $config->set('Core.Encoding', 'UTF-8');

        // -----------------------------------------------------------------------
        //  Cache — simpan di storage/framework/htmlpurifier
        // -----------------------------------------------------------------------
        $cacheDir = storage_path('framework/htmlpurifier');
        if (! is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        $config->set('Cache.SerializerPath', $cacheDir);

        // -----------------------------------------------------------------------
        //  HTMLDefinition — daftarkan elemen non-standard yang tidak dikenal HTMLPurifier
        //  (mark, ins, del, figure, figcaption).
        //  maybeGetRawHTMLDefinition() hanya mengembalikan objek saat pertama kali
        //  (sebelum di-cache), sehingga aman dipanggil setiap request.
        // -----------------------------------------------------------------------
        if ($def = $config->maybeGetRawHTMLDefinition()) {
            $def->addElement('mark', 'Inline', 'Inline', 'Common');
            $def->addElement('ins', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'Text']);
            $def->addElement('del', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'Text']);
            $def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
            $def->addElement('figcaption', 'Inline', 'Flow', 'Common');
            // HTML5 attributes yang tidak dikenal HTMLPurifier — daftarkan manual
            $def->addAttribute('img', 'loading', 'Enum#lazy,eager,auto');
            $def->addAttribute('iframe', 'allowfullscreen', 'Bool#allowfullscreen');
            $def->addAttribute('iframe', 'frameborder', 'Enum#0,1');
        }

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }

    /**
     * Strip semua HTML tag — untuk keperluan plain text (excerpt, meta description).
     */
    public static function strip(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        return strip_tags($html);
    }
}
