<?php

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
});

// ─── ACCESS CONTROL ──────────────────────────────────────────────────────────

test('unauthenticated guest cannot access article categories page', function () {
    $this->get(route('admin.article-categories.index'))->assertRedirect(route('login'));
    $this->post(route('admin.article-categories.store'), [])->assertRedirect(route('login'));
});

test('unauthorized siswa cannot access article categories page', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $this->actingAs($siswa)
        ->get(route('admin.article-categories.index'))
        ->assertStatus(302)
        ->assertRedirect(route('frontend.home'));
});

test('unauthorized guru cannot access article categories management', function () {
    $guru = User::factory()->create();
    $guru->assignRole('guru');

    // Guru does not have access-admin-dashboard, so they are redirected
    $this->actingAs($guru)
        ->get(route('admin.article-categories.index'))
        ->assertStatus(302);
});

test('authorized admin can view article categories index page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    ArticleCategory::factory()->create(['name' => 'Prestasi Siswa']);

    $response = $this->actingAs($admin)->get(route('admin.article-categories.index'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.article-categories.index')
        ->assertSee('Prestasi Siswa');
});

// ─── CREATE ───────────────────────────────────────────────────────────────────

test('authorized admin can create a new article category', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.article-categories.store'), [
        'name' => 'Ekstrakurikuler',
        'description' => 'Kegiatan di luar kelas.',
    ]);

    $response->assertRedirect(route('admin.article-categories.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('article_categories', [
        'name' => 'Ekstrakurikuler',
        'slug' => 'ekstrakurikuler',
    ]);
});

test('auto-slug is generated correctly on create', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->post(route('admin.article-categories.store'), [
        'name' => 'Info PPDB Terbaru',
    ]);

    $this->assertDatabaseHas('article_categories', [
        'name' => 'Info PPDB Terbaru',
        'slug' => 'info-ppdb-terbaru',
    ]);
});

test('duplicate category name is rejected by validation', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    ArticleCategory::factory()->create(['name' => 'Prestasi']);

    $response = $this->actingAs($admin)->post(route('admin.article-categories.store'), [
        'name' => 'Prestasi',
    ]);

    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('article_categories', 1);
});

test('category name is required and max 100 characters', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Empty name
    $this->actingAs($admin)
        ->post(route('admin.article-categories.store'), ['name' => ''])
        ->assertSessionHasErrors('name');

    // Too long
    $this->actingAs($admin)
        ->post(route('admin.article-categories.store'), ['name' => str_repeat('a', 101)])
        ->assertSessionHasErrors('name');
});

// ─── UPDATE ───────────────────────────────────────────────────────────────────

test('authorized admin can update an existing article category', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create(['name' => 'Nama Lama']);

    $response = $this->actingAs($admin)->put(route('admin.article-categories.update', $category), [
        'name' => 'Nama Baru',
        'description' => 'Deskripsi diperbarui.',
    ]);

    $response->assertRedirect(route('admin.article-categories.index'))
        ->assertSessionHas('success');

    $category->refresh();
    expect($category->name)->toBe('Nama Baru');
    expect($category->slug)->toBe('nama-baru');
});

test('slug is regenerated when category name changes on update', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create(['name' => 'Tips Belajar']);

    $this->actingAs($admin)->put(route('admin.article-categories.update', $category), [
        'name' => 'Tips Dan Trik Belajar',
    ]);

    expect($category->fresh()->slug)->toBe('tips-dan-trik-belajar');
});

// ─── DELETE ───────────────────────────────────────────────────────────────────

test('authorized admin can delete an article category', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();

    $response = $this->actingAs($admin)->delete(route('admin.article-categories.destroy', $category));

    $response->assertRedirect(route('admin.article-categories.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('article_categories', ['id' => $category->id]);
});

test('deleting a category sets category_id to null on linked articles (nullOnDelete)', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->create(['category_id' => $category->id]);

    $this->actingAs($admin)->delete(route('admin.article-categories.destroy', $category));

    $article->refresh();
    expect($article->category_id)->toBeNull();
});
