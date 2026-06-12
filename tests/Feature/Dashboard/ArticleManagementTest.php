<?php

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleRevision;
use App\Models\User;
use App\Support\Security\HtmlSanitizer;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
    Storage::fake('public');
});

// ─── ACCESS CONTROL ──────────────────────────────────────────────────────────

test('unauthenticated guest cannot access articles dashboard', function () {
    $this->get(route('admin.articles.index'))->assertRedirect(route('login'));
    $this->get(route('admin.articles.create'))->assertRedirect(route('login'));
    $this->post(route('admin.articles.store'), [])->assertRedirect(route('login'));
});

test('unauthorized user without access-dashboard permission is blocked from articles dashboard', function () {
    $user = User::factory()->create(); // No roles assigned

    $this->actingAs($user)
        ->get(route('admin.articles.index'))
        ->assertStatus(403);
});

test('authorized admin can view articles index page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $article = Article::factory()->published()->create(['judul' => 'Berita Robotik Sekolah']);

    $response = $this->actingAs($admin)->get(route('admin.articles.index'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.articles.index')
        ->assertSee('Berita Robotik Sekolah');
});

test('authorized guru can view articles index page but only sees their own articles', function () {
    $guru = User::factory()->create();
    $guru->assignRole('guru');

    $otherGuru = User::factory()->create();
    $otherGuru->assignRole('guru');

    Article::factory()->published()->create([
        'user_id' => $guru->id,
        'judul' => 'Artikel Guru Saya',
    ]);

    Article::factory()->published()->create([
        'user_id' => $otherGuru->id,
        'judul' => 'Artikel Guru Lain',
    ]);

    $response = $this->actingAs($guru)->get(route('admin.articles.index'));

    $response->assertStatus(200)
        ->assertSee('Artikel Guru Saya')
        ->assertDontSee('Artikel Guru Lain');
});

// ─── CREATE ───────────────────────────────────────────────────────────────────

test('authorized admin can access article create form', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get(route('admin.articles.create'))
        ->assertStatus(200)
        ->assertViewIs('dashboard.admin.articles.create');
});

test('authorized admin can create a new article without thumbnail', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'judul' => 'Prestasi Membanggakan Siswa MAM',
        'category_id' => $category->id,
        'ringkasan' => 'Ringkasan singkat artikel ini.',
        'konten' => '<p>Isi artikel lengkap di sini.</p>',
        'status' => 'published',
        'published_at' => now()->format('Y-m-d\TH:i'),
    ]);

    $response->assertRedirect(route('admin.articles.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('articles', [
        'judul' => 'Prestasi Membanggakan Siswa MAM',
        'user_id' => $admin->id, // user_id must come from session, not form
        'category_id' => $category->id,
        'status' => 'published',
    ]);
});

test('authorized guru can create an article and user_id is set to their own id', function () {
    $guru = User::factory()->create();
    $guru->assignRole('guru');

    $category = ArticleCategory::factory()->create();

    $this->actingAs($guru)->post(route('admin.articles.store'), [
        'judul' => 'Artikel Saya Sendiri',
        'category_id' => $category->id,
        'konten' => '<p>Konten artikel saya.</p>',
        'status' => 'draft',
    ]);

    $article = Article::where('judul', 'Artikel Saya Sendiri')->first();
    expect($article)->not->toBeNull();
    expect($article->user_id)->toBe($guru->id);
});

test('article creation with valid image thumbnail stores file with random safe name', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();
    $image = UploadedFile::fake()->create('foto_sekolah.jpg', 100, 'image/jpeg');

    $this->actingAs($admin)->post(route('admin.articles.store'), [
        'judul' => 'Artikel Dengan Foto',
        'category_id' => $category->id,
        'konten' => '<p>Konten.</p>',
        'status' => 'draft',
        'thumbnail' => $image,
    ]);

    $article = Article::where('judul', 'Artikel Dengan Foto')->first();
    expect($article)->not->toBeNull();
    expect($article->thumbnail)->not->toBeNull();

    // Filename must NOT contain original name (security: renamed to random)
    expect($article->thumbnail)->not->toContain('foto_sekolah');

    Storage::disk('public')->assertExists($article->thumbnail);
});

test('article creation rejects dangerous file types (PHP shell upload attempt)', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();
    $shell = UploadedFile::fake()->create('shell.php', 10, 'application/x-httpd-php');

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'judul' => 'Artikel Dengan Shell',
        'category_id' => $category->id,
        'konten' => '<p>Konten.</p>',
        'status' => 'draft',
        'thumbnail' => $shell,
    ]);

    $response->assertSessionHasErrors('thumbnail');
    $this->assertDatabaseMissing('articles', ['judul' => 'Artikel Dengan Shell']);
});

test('article store requires judul and konten', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();

    $this->actingAs($admin)->post(route('admin.articles.store'), [
        'judul' => '',
        'category_id' => $category->id,
        'konten' => '',
        'status' => 'draft',
    ])->assertSessionHasErrors(['judul', 'konten']);
});

test('article store requires a valid existing category_id', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->post(route('admin.articles.store'), [
        'judul' => 'Judul Test',
        'category_id' => 9999, // non-existent
        'konten' => '<p>Konten.</p>',
        'status' => 'draft',
    ])->assertSessionHasErrors('category_id');
});

// ─── XSS SANITIZATION ────────────────────────────────────────────────────────

test('HtmlSanitizer strips script tags from konten before saving', function () {
    $dirty = '<p>Teks aman.</p><script>alert("xss")</script>';
    $clean = HtmlSanitizer::clean($dirty);

    expect($clean)->toContain('Teks aman.');
    expect($clean)->not->toContain('<script>');
    expect($clean)->not->toContain('alert("xss")');
});

test('HtmlSanitizer removes on* event handler attributes', function () {
    $dirty = '<p onmouseover="alert(1)">Hover saya</p><img src="x" onerror="fetch(\'//evil.com\')">';
    $clean = HtmlSanitizer::clean($dirty);

    expect($clean)->not->toContain('onmouseover');
    expect($clean)->not->toContain('onerror');
    expect($clean)->toContain('Hover saya');
});

test('HtmlSanitizer strips javascript: URI from href attributes', function () {
    $dirty = '<a href="javascript:alert(\'xss\')">Klik sini</a>';
    $clean = HtmlSanitizer::clean($dirty);

    expect($clean)->not->toContain('javascript:');
    expect($clean)->toContain('Klik sini');
});

test('HtmlSanitizer strips unsafe iframe and embed tags', function () {
    $dirty = '<p>Konten aman</p><iframe src="https://evil.com"></iframe><embed src="bad.swf">';
    $clean = HtmlSanitizer::clean($dirty);

    expect($clean)->not->toContain('src="https://evil.com"');
    expect($clean)->not->toContain('<embed');
    expect($clean)->toContain('Konten aman');
});

test('konten is sanitized server-side before being stored in database', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();

    $this->actingAs($admin)->post(route('admin.articles.store'), [
        'judul' => 'Artikel XSS Test',
        'category_id' => $category->id,
        'konten' => '<p>Aman</p><script>alert("hack")</script>',
        'status' => 'draft',
    ]);

    $article = Article::where('judul', 'Artikel XSS Test')->first();
    expect($article)->not->toBeNull();
    expect($article->konten)->not->toContain('<script>');
    expect($article->konten)->toContain('Aman');
});

// ─── UPDATE / IDOR PROTECTION ─────────────────────────────────────────────────

test('admin can edit and update any article', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->create(['judul' => 'Judul Lama', 'category_id' => $category->id]);

    $response = $this->actingAs($admin)->put(route('admin.articles.update', $article), [
        'judul' => 'Judul Baru',
        'category_id' => $category->id,
        'konten' => '<p>Konten baru.</p>',
        'status' => 'published',
        'published_at' => now()->format('Y-m-d\TH:i'),
    ]);

    $response->assertRedirect(route('admin.articles.index'))
        ->assertSessionHas('success');

    expect($article->fresh()->judul)->toBe('Judul Baru');
});

test('guru can edit their own article (IDOR: own resource allowed)', function () {
    $guru = User::factory()->create();
    $guru->assignRole('guru');

    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->create([
        'user_id' => $guru->id,
        'judul' => 'Artikel Saya',
        'category_id' => $category->id,
    ]);

    $response = $this->actingAs($guru)->put(route('admin.articles.update', $article), [
        'judul' => 'Artikel Saya Diperbarui',
        'category_id' => $category->id,
        'konten' => '<p>Konten diperbarui.</p>',
        'status' => 'draft',
    ]);

    $response->assertRedirect(route('admin.articles.index'));
    expect($article->fresh()->judul)->toBe('Artikel Saya Diperbarui');
});

test('guru cannot edit an article owned by another user (IDOR protection)', function () {
    $guru = User::factory()->create();
    $guru->assignRole('guru');

    $otherGuru = User::factory()->create();
    $otherGuru->assignRole('guru');

    $category = ArticleCategory::factory()->create();
    $otherArticle = Article::factory()->create([
        'user_id' => $otherGuru->id,
        'category_id' => $category->id,
    ]);

    $response = $this->actingAs($guru)->put(route('admin.articles.update', $otherArticle), [
        'judul' => 'Diubah Paksa',
        'category_id' => $category->id,
        'konten' => '<p>Konten diubah paksa.</p>',
        'status' => 'draft',
    ]);

    $response->assertStatus(403);
    expect($otherArticle->fresh()->judul)->not->toBe('Diubah Paksa');
});

test('guru cannot access edit form of an article owned by another user', function () {
    $guru = User::factory()->create();
    $guru->assignRole('guru');

    $otherGuru = User::factory()->create();
    $otherGuru->assignRole('guru');

    $otherArticle = Article::factory()->create(['user_id' => $otherGuru->id]);

    $this->actingAs($guru)
        ->get(route('admin.articles.edit', $otherArticle))
        ->assertStatus(403);
});

// ─── DELETE ───────────────────────────────────────────────────────────────────

test('admin can soft-delete any article', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $article = Article::factory()->create();

    $response = $this->actingAs($admin)->delete(route('admin.articles.destroy', $article));

    $response->assertRedirect(route('admin.articles.index'))
        ->assertSessionHas('success');

    $this->assertSoftDeleted('articles', ['id' => $article->id]);
});

test('guru cannot delete an article they do not own (IDOR protection)', function () {
    $guru = User::factory()->create();
    $guru->assignRole('guru');

    $otherGuru = User::factory()->create();
    $otherGuru->assignRole('guru');

    $otherArticle = Article::factory()->create(['user_id' => $otherGuru->id]);

    $this->actingAs($guru)
        ->delete(route('admin.articles.destroy', $otherArticle))
        ->assertStatus(403);

    $this->assertDatabaseHas('articles', ['id' => $otherArticle->id, 'deleted_at' => null]);
});

// ─── THUMBNAIL UPDATE ─────────────────────────────────────────────────────────

test('updating an article with a new thumbnail deletes the old file from storage', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();

    // Simulate existing thumbnail
    $oldPath = 'articles/old_thumbnail_abc123.jpg';
    Storage::disk('public')->put($oldPath, 'fake old image content');

    $article = Article::factory()->create([
        'user_id' => $admin->id,
        'category_id' => $category->id,
        'thumbnail' => $oldPath,
    ]);

    $newImage = UploadedFile::fake()->create('baru.jpg', 100, 'image/jpeg');

    $this->actingAs($admin)->put(route('admin.articles.update', $article), [
        'judul' => $article->judul,
        'category_id' => $category->id,
        'konten' => '<p>Konten.</p>',
        'status' => 'draft',
        'thumbnail' => $newImage,
    ]);

    // Old file should be gone
    Storage::disk('public')->assertMissing($oldPath);

    // New file should exist
    $article->refresh();
    Storage::disk('public')->assertExists($article->thumbnail);
});

// ─── TEMPORARY THUMBNAIL UPLOADS ──────────────────────────────────────────────────

test('authorized user can upload temporary thumbnail', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $file = UploadedFile::fake()->create('temp_pic.jpg', 200, 'image/jpeg');

    $response = $this->actingAs($admin)
        ->postJson(route('admin.articles.upload-temp'), [
            'thumbnail' => $file,
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['path', 'url']);

    $path = $response->json('path');
    expect($path)->toStartWith('temp/');
    Storage::disk('public')->assertExists($path);
});

test('temporary upload rejects invalid file types', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

    $response = $this->actingAs($admin)
        ->postJson(route('admin.articles.upload-temp'), [
            'thumbnail' => $file,
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['thumbnail']);
});

test('storing an article using temp_thumbnail moves file to articles folder', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();

    // Create a fake temporary file
    $tempPath = 'temp/some_random_hash.jpg';
    Storage::disk('public')->put($tempPath, 'fake image content');

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'judul' => 'Artikel Dengan Temp Thumbnail',
        'category_id' => $category->id,
        'konten' => '<p>Isi artikel.</p>',
        'status' => 'draft',
        'temp_thumbnail' => $tempPath,
    ]);

    $response->assertRedirect(route('admin.articles.index'));

    $article = Article::where('judul', 'Artikel Dengan Temp Thumbnail')->first();
    expect($article)->not->toBeNull();
    expect($article->thumbnail)->not->toBeNull();
    expect($article->thumbnail)->not->toBe($tempPath);
    expect($article->thumbnail)->toStartWith('articles/');

    // Temp file should be deleted or moved
    Storage::disk('public')->assertMissing($tempPath);
    Storage::disk('public')->assertExists($article->thumbnail);
});

test('updating an article with temp_thumbnail moves file and deletes old thumbnail', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();

    $oldThumbnail = 'articles/old_image.jpg';
    Storage::disk('public')->put($oldThumbnail, 'old image content');

    $article = Article::factory()->create([
        'user_id' => $admin->id,
        'category_id' => $category->id,
        'thumbnail' => $oldThumbnail,
    ]);

    // Create a fake temporary file
    $tempPath = 'temp/another_random_hash.jpg';
    Storage::disk('public')->put($tempPath, 'new image content');

    $response = $this->actingAs($admin)->put(route('admin.articles.update', $article), [
        'judul' => $article->judul,
        'category_id' => $category->id,
        'konten' => '<p>Konten baru.</p>',
        'status' => 'draft',
        'temp_thumbnail' => $tempPath,
    ]);

    $response->assertRedirect(route('admin.articles.index'));

    $article->refresh();
    expect($article->thumbnail)->not->toBeNull();
    expect($article->thumbnail)->toStartWith('articles/');

    // Old file and temp file should be gone
    Storage::disk('public')->assertMissing($oldThumbnail);
    Storage::disk('public')->assertMissing($tempPath);
    Storage::disk('public')->assertExists($article->thumbnail);
});

test('temp_thumbnail path traversal prevention does not move file outside public disk', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();

    // Malicious traversal path
    $maliciousPath = 'temp/../../outside.jpg';

    $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
        'judul' => 'Artikel Jahat',
        'category_id' => $category->id,
        'konten' => '<p>Isi artikel.</p>',
        'status' => 'draft',
        'temp_thumbnail' => $maliciousPath,
    ]);

    // Should not create or move the file, the article thumbnail should remain null/empty
    $article = Article::where('judul', 'Artikel Jahat')->first();
    expect($article)->not->toBeNull();
    expect($article->thumbnail)->toBeNull();
});

// ─── NEW REVISI & TOLAK FLOWS ──────────────────────────────────────────────────

test('author can edit rejected article if rejection_count is 1', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->create([
        'user_id' => $siswa->id,
        'category_id' => $category->id,
        'status' => 'rejected',
        'rejection_count' => 1,
        'rejection_reason' => 'First rejection reason',
    ]);

    $response = $this->actingAs($siswa)->put(route('admin.articles.update', $article), [
        'judul' => 'Judul Baru',
        'category_id' => $category->id,
        'konten' => '<p>Konten revisi.</p>',
        'status' => 'pending',
    ]);

    $response->assertRedirect(route('admin.articles.index'));
    expect($article->fresh()->judul)->toBe('Judul Baru');
});

test('author is blocked from updating article if rejection_count >= 2', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->create([
        'user_id' => $siswa->id,
        'category_id' => $category->id,
        'status' => 'rejected',
        'rejection_count' => 2,
        'rejection_reason' => 'Second rejection reason',
    ]);

    $response = $this->actingAs($siswa)->put(route('admin.articles.update', $article), [
        'judul' => 'Judul Baru',
        'category_id' => $category->id,
        'konten' => '<p>Konten revisi.</p>',
        'status' => 'pending',
    ]);

    $response->assertStatus(403);
    expect($article->fresh()->judul)->not->toBe('Judul Baru');
});

test('submitting a revision auto-resolves pending revisions and sets status back to pending', function () {
    $siswa = User::factory()->create();
    $siswa->assignRole('siswa');

    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->create([
        'user_id' => $siswa->id,
        'category_id' => $category->id,
        'status' => 'revision',
    ]);

    // Create a pending revision request
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $revision = ArticleRevision::create([
        'article_id' => $article->id,
        'reviewer_id' => $admin->id,
        'revision_number' => 1,
        'notes' => 'Please fix spelling.',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($siswa)->put(route('admin.articles.update', $article), [
        'judul' => $article->judul,
        'category_id' => $category->id,
        'konten' => '<p>Fixed spelling.</p>',
        'status' => 'pending',
    ]);

    $response->assertRedirect(route('admin.articles.index'));
    expect($article->fresh()->status)->toBe('pending');
    expect($revision->fresh()->status)->toBe('resolved');
    expect($revision->fresh()->resolved_at)->not->toBeNull();
});

test('admin rejection increments rejection_count and sets status to rejected', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->create([
        'category_id' => $category->id,
        'status' => 'pending',
        'rejection_count' => 0,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.articles.reject', $article), [
        'rejection_reason' => 'Wrong format',
    ]);

    $response->assertRedirect(route('admin.articles.show', $article->slug));
    $article->refresh();
    expect($article->status)->toBe('rejected');
    expect($article->rejection_count)->toBe(1);
    expect($article->rejection_reason)->toBe('Wrong format');
});

test('admin approval resets rejection_count to 0', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $category = ArticleCategory::factory()->create();
    $article = Article::factory()->create([
        'category_id' => $category->id,
        'status' => 'pending',
        'rejection_count' => 1,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.articles.approve', $article));

    $response->assertRedirect(route('admin.articles.show', $article->slug));
    $article->refresh();
    expect($article->status)->toBe('published');
    expect($article->rejection_count)->toBe(0);
});
