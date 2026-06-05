<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\GaleriPhoto;
use App\Services\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AppsGaleriController extends Controller
{
    /**
     * Display student's gallery list and upload page
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        // Get student's own uploads
        $galleries = Galeri::where('user_id', $user->id)
            ->with('photos')
            ->latest()
            ->get();

        return view('mobile_apps.galeri.index', compact('galleries'));
    }

    /**
     * Show create form for student gallery
     */
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        return view('mobile_apps.galeri.create');
    }

    /**
     * Store a new student gallery upload
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'kategori' => ['required', 'string', 'in:Kegiatan,Prestasi,Fasilitas,Umum,Pramuka,Kelas'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:'.(date('Y') + 1)],
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'judul.required' => 'Judul galeri wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'photos.required' => 'Harap unggah minimal satu foto.',
            'photos.*.image' => 'File harus berupa gambar.',
            'photos.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $galeri = Galeri::create([
            'user_id' => $user->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'tahun' => $request->tahun,
            'status' => 'pending',
            'is_visible' => false,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $safeName = Str::random(40).'.'.$file->guessExtension();
                $path = $file->storeAs('galeri', $safeName, 'public');

                GaleriPhoto::create([
                    'galeri_id' => $galeri->id,
                    'file_path' => $path,
                    'tipe' => 'file',
                    'is_cover' => $index === 0,
                    'order' => $index,
                ]);
            }
        }

        SystemLogService::logSecurity(
            'galeri_siswa_upload',
            "Siswa {$user->name} mengunggah galeri baru: '{$galeri->judul}' (menunggu persetujuan)",
            $user
        );

        return redirect()->route('apps.galeri')
            ->with('success', 'Galeri berhasil diusulkan! Menunggu verifikasi dari admin.');
    }

    /**
     * Display the specified gallery details
     */
    public function show(Request $request, Galeri $galeri): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa') || $galeri->user_id !== $user->id) {
            return redirect()->route('dashboard');
        }

        $galeri->load('photos');

        return view('mobile_apps.galeri.show', compact('galeri'));
    }

    /**
     * Show edit form for student gallery
     */
    public function edit(Request $request, Galeri $galeri): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa') || $galeri->user_id !== $user->id) {
            return redirect()->route('dashboard');
        }

        return view('mobile_apps.galeri.edit', compact('galeri'));
    }

    /**
     * Update the student gallery details
     */
    public function update(Request $request, Galeri $galeri): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa') || $galeri->user_id !== $user->id) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'kategori' => ['required', 'string', 'in:Kegiatan,Prestasi,Fasilitas,Umum,Pramuka,Kelas'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:'.(date('Y') + 1)],
            'photos' => ['nullable', 'array', 'min:1'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'judul.required' => 'Judul galeri wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'photos.*.image' => 'File harus berupa gambar.',
            'photos.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $galeri->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'tahun' => $request->tahun,
            'status' => 'pending', // reset to pending for admin re-verification
        ]);

        if ($request->hasFile('photos')) {
            // Delete old photos
            foreach ($galeri->photos as $photo) {
                Storage::disk('public')->delete($photo->file_path);
                $photo->delete();
            }

            // Store new photos
            foreach ($request->file('photos') as $index => $file) {
                $safeName = Str::random(40).'.'.$file->guessExtension();
                $path = $file->storeAs('galeri', $safeName, 'public');

                GaleriPhoto::create([
                    'galeri_id' => $galeri->id,
                    'file_path' => $path,
                    'tipe' => 'file',
                    'is_cover' => $index === 0,
                    'order' => $index,
                ]);
            }
        }

        SystemLogService::logSecurity(
            'galeri_siswa_update',
            "Siswa {$user->name} memperbarui galeri: '{$galeri->judul}' (menunggu persetujuan ulang)",
            $user
        );

        return redirect()->route('apps.galeri')
            ->with('success', 'Galeri berhasil diperbarui! Menunggu verifikasi ulang dari admin.');
    }

    /**
     * Delete the student gallery upload
     */
    public function destroy(Request $request, Galeri $galeri): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa') || $galeri->user_id !== $user->id) {
            return redirect()->route('dashboard');
        }

        // Delete files from storage
        foreach ($galeri->photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }

        $judul = $galeri->judul;
        $galeri->delete();

        SystemLogService::logSecurity(
            'galeri_siswa_delete',
            "Siswa {$user->name} menghapus galeri mereka: '{$judul}'",
            $user
        );

        return redirect()->route('apps.galeri')
            ->with('success', 'Galeri berhasil dihapus.');
    }
}
