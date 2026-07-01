<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\SiteSetting;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    /**
     * Generate dynamic sitemap.xml
     */
    public function sitemap(): Response
    {
        $urls = [
            [
                'loc' => route('frontend.home'),
                'lastmod' => now()->startOfDay()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => route('frontend.profile.index'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => route('frontend.profile.selayang-pandang'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('frontend.profile.visi-misi'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('frontend.profile.periodisasi-kepala'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ],
            [
                'loc' => route('frontend.profile.struktur-organisasi'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ],
            [
                'loc' => route('frontend.profile.program-madrasah'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('frontend.profile.mmc'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('frontend.ppdb.index'),
                'lastmod' => now()->startOfDay()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('frontend.article.index'),
                'lastmod' => now()->startOfDay()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('frontend.jurusan'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('frontend.kurikulum'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('frontend.ekstrakurikuler'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('frontend.prestasi'),
                'lastmod' => now()->startOfWeek()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => route('frontend.galeri'),
                'lastmod' => now()->startOfWeek()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('frontend.contact'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ],
        ];

        // Fetch articles with caching (cleared automatically on article save/delete)
        $articles = Cache::remember('seo_sitemap_articles', 86400, function () {
            return Article::published()
                ->latest()
                ->select(['id', 'slug', 'updated_at'])
                ->get();
        });

        foreach ($articles as $article) {
            $urls[] = [
                'loc' => route('frontend.article.show', $article->slug),
                'lastmod' => $article->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".view('front.seo.sitemap', compact('urls'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Generate dynamic robots.txt
     */
    public function robots(): Response
    {
        $siteSetting = Cache::remember('site_settings', 86400, function () {
            return SiteSetting::first();
        });

        $isIndexed = $siteSetting ? $siteSetting->is_indexed : true;

        if ($isIndexed) {
            $content = "User-agent: *\n".
                "Disallow: /admin/\n".
                "Disallow: /super-admin/\n".
                "Disallow: /siswa/\n".
                "Disallow: /guru/\n".
                "Disallow: /dev/\n\n".
                'Sitemap: '.route('frontend.seo.sitemap')."\n";
        } else {
            $content = "User-agent: *\n".
                "Disallow: /\n";
        }

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
