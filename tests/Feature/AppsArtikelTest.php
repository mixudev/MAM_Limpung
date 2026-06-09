<?php

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
});

test('siswa can update their own article and its seo metadata via mobile apps', function () {
    Storage::fake('public');

    // Create a student user
    $siswa = User::factory()->create(['is_active' => true]);
    $siswa->assignRole('siswa');

    // Create category and article
    $category = ArticleCategory::factory()->create();
    $article = Article::create([
        'user_id' => $siswa->id,
        'category_id' => $category->id,
        'judul' => 'Judul Artikel Awal',
        'ringkasan' => 'Ringkasan artikel awal',
        'konten' => '<p>Konten awal artikel</p>',
        'status' => 'published',
    ]);

    // Make request to update
    $newThumbnail = UploadedFile::fake()->create('thumbnail_baru.jpg', 100);

    $response = $this->actingAs($siswa)
        ->put(route('apps.artikel.update', $article), [
            'judul' => 'Judul Artikel Baru',
            'category_id' => $category->id,
            'ringkasan' => 'Ringkasan artikel baru yang lebih lengkap',
            'konten' => '<p>Konten artikel yang baru saja diedit</p>',
            'thumbnail' => $newThumbnail,
        ]);

    $response->assertRedirect(route('apps.artikel'));
    $response->assertSessionHas('success');

    // Assert article details in db
    $article->refresh();
    expect($article->judul)->toBe('Judul Artikel Baru');
    expect($article->ringkasan)->toBe('Ringkasan artikel baru yang lebih lengkap');
    expect($article->konten)->toBe('<p>Konten artikel yang baru saja diedit</p>');
    expect($article->status)->toBe('pending'); // resets to pending for admin approval

    // Assert SEO record updated
    $article->load('seo');
    expect($article->seo)->not->toBeNull();
    expect($article->seo->meta_title)->toBe('Judul Artikel Baru');
    expect($article->seo->meta_description)->toBe('Ringkasan artikel baru yang lebih lengkap');
});
