<?php

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);

    // Clear cache
    Cache::forget('site_settings');
    Cache::forget('seo_sitemap_articles');
});

test('sitemap xml renders correctly and includes home and article urls', function () {
    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->published()->create([
        'judul' => 'Sitemap Test Article',
        'category_id' => $category->id,
    ]);

    $response = $this->get(route('frontend.seo.sitemap'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');

    // Check for schema/urlset tag
    expect($response->getContent())->toContain('<urlset');
    expect($response->getContent())->toContain(route('frontend.home'));
    expect($response->getContent())->toContain(route('frontend.article.show', $article->slug));
});

test('robots txt matches is_indexed global setting', function () {
    $siteSetting = SiteSetting::first() ?? SiteSetting::create(['school_name' => 'MAM Limpung']);

    // Test case 1: Indexed is true
    $siteSetting->update(['is_indexed' => true]);
    Cache::forget('site_settings');

    $response = $this->get('/robots.txt');
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    expect($response->getContent())->toContain('User-agent: *');
    expect($response->getContent())->toContain('Disallow: /admin/');
    expect($response->getContent())->toContain('Sitemap: '.route('frontend.seo.sitemap'));

    // Test case 2: Indexed is false
    $siteSetting->update(['is_indexed' => false]);
    Cache::forget('site_settings');

    $response = $this->get('/robots.txt');
    $response->assertStatus(200);
    expect($response->getContent())->toContain('User-agent: *');
    expect($response->getContent())->toContain('Disallow: /');
    expect($response->getContent())->not->toContain('Sitemap:');
});

test('article detail view renders custom seo metadata and social tags', function () {
    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->published()->create([
        'judul' => 'Artikel Berita Keren',
        'category_id' => $category->id,
    ]);

    $article->seo()->create([
        'meta_title' => 'Custom Judul SEO',
        'meta_description' => 'Deskripsi Kustom SEO yang sangat menarik sekali untuk dibaca.',
        'meta_keywords' => 'keyword1, keyword2',
        'is_indexed' => true,
        'is_followed' => true,
    ]);

    $response = $this->get(route('frontend.article.show', $article->slug));

    $response->assertStatus(200);
    expect($response->getContent())->toContain('<title>Custom Judul SEO</title>');
    expect($response->getContent())->toContain('name="description" content="Deskripsi Kustom SEO yang sangat menarik sekali untuk dibaca."');
    expect($response->getContent())->toContain('name="keywords" content="keyword1, keyword2"');
    expect($response->getContent())->toContain('property="og:title" content="Custom Judul SEO"');
    expect($response->getContent())->toContain('https://api.whatsapp.com/send');
    expect($response->getContent())->toContain('https://www.facebook.com/sharer');
    expect($response->getContent())->toContain('https://twitter.com/intent/tweet');
    expect($response->getContent())->toContain('https://telegram.me/share/url');
    expect($response->getContent())->toContain('https://www.linkedin.com/sharing/share-offsite');
});

test('article detail view injects breadcrumb and article schemas', function () {
    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->published()->create([
        'judul' => 'Skema JSON LD Test',
        'category_id' => $category->id,
    ]);

    $response = $this->get(route('frontend.article.show', $article->slug));

    $response->assertStatus(200);
    expect($response->getContent())->toContain('"@type": "BreadcrumbList"');
    expect($response->getContent())->toContain('"@type": "NewsArticle"');
    expect($response->getContent())->toContain(route('frontend.article.show', $article->slug));
});

test('admin can update seo and analytics global settings', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $siteSetting = SiteSetting::first() ?? SiteSetting::create(['school_name' => 'MAM Limpung']);

    $response = $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'school_name' => 'MA Muhammadiyah Limpung Baru',
            'email' => 'admin@mamlimpung.sch.id',
            'phone' => '0812345678',
            'address' => 'Alamat baru',
            'meta_title' => 'MAM Limpung Unggul',
            'meta_description' => 'Deskripsi sekolah terindeks.',
            'google_analytics_id' => 'G-GA4TESTID',
            'google_search_console_id' => 'gsc-verification-id',
            'google_tag_manager_id' => 'GTM-TESTID',
            'is_indexed' => '1',
        ]);

    $response->assertRedirect(route('admin.settings.edit'));

    $siteSetting->refresh();
    expect($siteSetting->google_analytics_id)->toBe('G-GA4TESTID');
    expect($siteSetting->google_tag_manager_id)->toBe('GTM-TESTID');
    expect($siteSetting->meta_title)->toBe('MAM Limpung Unggul');
    expect($siteSetting->is_indexed)->toBeTrue();
});
