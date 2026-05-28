<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Galeri\StoreGaleriRequest;
use App\Http\Requests\Dashboard\Galeri\UpdateGaleriRequest;
use App\Models\Galeri;
use App\Models\GaleriPhoto;
use App\Services\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GaleriController extends Controller
{
    /**
     * Display a listing of the galleries.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Galeri::class);

        $user = $request->user();
        $query = Galeri::query()->with(['pengunggah', 'photos']);

        // Non-admin can only see their own submissions
        if (! $user->hasRole('admin') && ! $user->hasRole('super-admin')) {
            $query->where('user_id', $user->id);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Apply category filter
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        $galeris = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.admin.galeri.index', compact('galeris'));
    }

    /**
     * Show the form for creating a new gallery.
     */
    public function create(): View
    {
        Gate::authorize('create', Galeri::class);

        return view('dashboard.admin.galeri.create');
    }

    /**
     * Store a newly created gallery.
     */
    public function store(StoreGaleriRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $galeriData = [
            'user_id' => $user->id,
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'kategori' => $data['kategori'],
            'tahun' => $data['tahun'],
        ];

        // Approval workflow: Admin auto-approves, student requires manual approval
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            $galeriData['status'] = 'approved';
            $galeriData['approved_by'] = $user->id;
            $galeriData['approved_at'] = now();
            $galeriData['is_visible'] = true;
        } else {
            $galeriData['status'] = 'pending';
            $galeriData['is_visible'] = false;
        }

        $galeri = Galeri::create($galeriData);

        // Process file uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $safeName = Str::random(40).'.'.$file->guessExtension();
                $path = $file->storeAs('galeri', $safeName, 'public');

                $isCover = ($data['cover_type'] === 'file' && (int) $data['cover_index'] === $index);

                GaleriPhoto::create([
                    'galeri_id' => $galeri->id,
                    'file_path' => $path,
                    'tipe' => 'file',
                    'is_cover' => $isCover,
                    'order' => $index,
                ]);
            }
        }

        // Process external links
        if ($request->filled('links')) {
            $orderOffset = $request->hasFile('photos') ? count($request->file('photos')) : 0;
            foreach ($data['links'] as $index => $link) {
                if (empty($link)) {
                    continue;
                }

                $isCover = ($data['cover_type'] === 'link' && (int) $data['cover_index'] === $index);

                GaleriPhoto::create([
                    'galeri_id' => $galeri->id,
                    'file_path' => $link,
                    'tipe' => 'link',
                    'is_cover' => $isCover,
                    'order' => $orderOffset + $index,
                ]);
            }
        }

        // Ensure there is at least one cover photo
        if ($galeri->photos()->where('is_cover', true)->count() === 0) {
            $firstPhoto = $galeri->photos()->first();
            if ($firstPhoto) {
                $firstPhoto->update(['is_cover' => true]);
            }
        }

        $message = ($user->hasRole('admin') || $user->hasRole('super-admin'))
            ? 'Galeri berhasil diterbitkan.'
            : 'Galeri berhasil diusulkan dan menunggu persetujuan admin.';

        return redirect()->route('admin.galeri.index')->with('success', $message);
    }

    /**
     * Display the specified gallery.
     */
    public function show(Galeri $galeri): View
    {
        Gate::authorize('view', $galeri);

        $galeri->load(['pengunggah', 'approvedBy', 'photos']);

        return view('dashboard.admin.galeri.show', compact('galeri'));
    }

    /**
     * Show the form for editing the specified gallery.
     */
    public function edit(Galeri $galeri): View
    {
        Gate::authorize('update', $galeri);

        $galeri->load('photos');

        return view('dashboard.admin.galeri.edit', compact('galeri'));
    }

    /**
     * Update the specified gallery.
     */
    public function update(UpdateGaleriRequest $request, Galeri $galeri): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $galeriData = [
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'kategori' => $data['kategori'],
            'tahun' => $data['tahun'],
        ];

        // Reset approval status to pending if updated by non-admin
        if (! $user->hasRole('admin') && ! $user->hasRole('super-admin')) {
            $galeriData['status'] = 'pending';
            $galeriData['is_visible'] = false;
            $galeriData['approved_by'] = null;
            $galeriData['approved_at'] = null;
            $galeriData['rejected_reason'] = null;
        }

        $galeri->update($galeriData);

        // Track photo IDs that should remain
        $keptPhotoIds = $data['existing_photos'] ?? [];

        // Delete removed files from disk and DB
        $photosToDelete = GaleriPhoto::where('galeri_id', $galeri->id)
            ->whereNotIn('id', $keptPhotoIds)
            ->get();

        foreach ($photosToDelete as $photo) {
            if ($photo->tipe === 'file') {
                Storage::disk('public')->delete($photo->file_path);
            }
            $photo->delete();
        }

        // Reset is_cover status first
        GaleriPhoto::where('galeri_id', $galeri->id)->update(['is_cover' => false]);

        // Keep track of new photos and new links
        $newPhotoModels = [];
        $newLinkModels = [];

        // Add new file uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $safeName = Str::random(40).'.'.$file->guessExtension();
                $path = $file->storeAs('galeri', $safeName, 'public');

                $photoModel = GaleriPhoto::create([
                    'galeri_id' => $galeri->id,
                    'file_path' => $path,
                    'tipe' => 'file',
                    'is_cover' => false,
                    'order' => 100 + $index, // Temporary high order, will be sorted
                ]);

                $newPhotoModels[$index] = $photoModel;
            }
        }

        // Add new external links
        if ($request->filled('links')) {
            foreach ($data['links'] as $index => $link) {
                if (empty($link)) {
                    continue;
                }

                $linkModel = GaleriPhoto::create([
                    'galeri_id' => $galeri->id,
                    'file_path' => $link,
                    'tipe' => 'link',
                    'is_cover' => false,
                    'order' => 200 + $index,
                ]);

                $newLinkModels[$index] = $linkModel;
            }
        }

        // Update cover photo
        if ($data['cover_type'] === 'existing') {
            GaleriPhoto::where('id', $data['cover_index'])->update(['is_cover' => true]);
        } elseif ($data['cover_type'] === 'file' && isset($newPhotoModels[$data['cover_index']])) {
            $newPhotoModels[$data['cover_index']]->update(['is_cover' => true]);
        } elseif ($data['cover_type'] === 'link' && isset($newLinkModels[$data['cover_index']])) {
            $newLinkModels[$data['cover_index']]->update(['is_cover' => true]);
        }

        // Re-order remaining photos
        $allPhotos = $galeri->photos()->orderBy('id')->get();
        foreach ($allPhotos as $index => $p) {
            $p->update(['order' => $index]);
        }

        // Ensure at least one cover exists
        if ($galeri->photos()->where('is_cover', true)->count() === 0) {
            $firstPhoto = $galeri->photos()->first();
            if ($firstPhoto) {
                $firstPhoto->update(['is_cover' => true]);
            }
        }

        $message = (! $user->hasRole('admin') && ! $user->hasRole('super-admin'))
            ? 'Galeri berhasil diperbarui dan status kembali ditinjau.'
            : 'Galeri berhasil diperbarui.';

        return redirect()->route('admin.galeri.index')->with('success', $message);
    }

    /**
     * Remove the specified gallery.
     */
    public function destroy(Galeri $galeri): RedirectResponse
    {
        Gate::authorize('delete', $galeri);

        // Delete associated files
        foreach ($galeri->photos as $photo) {
            if ($photo->tipe === 'file') {
                Storage::disk('public')->delete($photo->file_path);
            }
        }

        $galeri->delete(); // Soft deletes is used, but we deleted the files anyway to save disk space since softdelete is for audit.

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil dihapus.');
    }

    /**
     * Approve a gallery.
     */
    public function approve(Request $request, Galeri $galeri): RedirectResponse
    {
        Gate::authorize('approve', Galeri::class);

        $galeri->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'is_visible' => true,
            'rejected_reason' => null,
        ]);

        SystemLogService::logSecurity(
            'galeri_approval',
            "Pengguna {$request->user()->name} menyetujui unggahan galeri: '{$galeri->judul}' oleh {$galeri->pengunggah->name}",
            $request->user()
        );

        return redirect()->back()->with('success', 'Galeri berhasil disetujui dan dipublikasikan.');
    }

    /**
     * Reject a gallery.
     */
    public function reject(Request $request, Galeri $galeri): RedirectResponse
    {
        Gate::authorize('approve', Galeri::class);

        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $galeri->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'is_visible' => false,
            'rejected_reason' => $request->input('reason'),
        ]);

        SystemLogService::logSecurity(
            'galeri_rejection',
            "Pengguna {$request->user()->name} menolak unggahan galeri: '{$galeri->judul}' oleh {$galeri->pengunggah->name}",
            $request->user()
        );

        return redirect()->back()->with('success', 'Galeri berhasil ditolak.');
    }
}
