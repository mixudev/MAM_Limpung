<?php

namespace App\Http\Controllers\Dashboard\Prestasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Prestasi\StorePrestasiRequest;
use App\Http\Requests\Dashboard\Prestasi\UpdatePrestasiRequest;
use App\Models\Prestasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPrestasiController extends Controller
{
    /**
     * Display a listing of the achievements.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Prestasi::class);

        $query = Prestasi::query()->with('pelapor');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('peraih', 'like', "%{$search}%")
                    ->orWhere('penyelenggara', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->input('tingkat'));
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        $prestasis = $query->latest('tanggal_prestasi')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.admin.prestasi.index', compact('prestasis'));
    }

    /**
     * Show the form for creating a new achievement.
     */
    public function create(): View
    {
        Gate::authorize('create', Prestasi::class);

        return view('dashboard.admin.prestasi.create');
    }

    /**
     * Upload a temporary photo for achievement.
     */
    public function uploadTemp(Request $request): JsonResponse
    {
        Gate::authorize('create', Prestasi::class);

        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('temp', $safeName, 'public');

            return response()->json([
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
            ]);
        }

        return response()->json(['error' => 'Berkas tidak ditemukan.'], 400);
    }

    /**
     * Store a newly created achievement in storage.
     */
    public function store(StorePrestasiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['is_featured'] = $request->boolean('is_featured', false);

        // Handle File Upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('prestasi', $safeName, 'public');
            $data['foto'] = $path;
        } elseif ($request->filled('temp_foto')) {
            $tempPath = $request->input('temp_foto');
            if (str_starts_with($tempPath, 'temp/') && ! str_contains($tempPath, '..')) {
                if (Storage::disk('public')->exists($tempPath)) {
                    $filename = basename($tempPath);
                    $newPath = 'prestasi/'.$filename;
                    Storage::disk('public')->move($tempPath, $newPath);
                    $data['foto'] = $newPath;
                }
            }
        }

        Prestasi::create($data);

        return redirect()->route('admin.prestasi.index')
            ->with('success', 'Data prestasi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified achievement.
     */
    public function edit(Prestasi $prestasi): View
    {
        Gate::authorize('update', $prestasi);

        return view('dashboard.admin.prestasi.edit', compact('prestasi'));
    }

    /**
     * Update the specified achievement in storage.
     */
    public function update(UpdatePrestasiRequest $request, Prestasi $prestasi): RedirectResponse
    {
        $data = $request->validated();
        $data['is_featured'] = $request->boolean('is_featured', false);

        // Handle File Upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');

            // Delete old file if exists
            if ($prestasi->foto) {
                Storage::disk('public')->delete($prestasi->foto);
            }

            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('prestasi', $safeName, 'public');
            $data['foto'] = $path;
        } elseif ($request->filled('temp_foto')) {
            $tempPath = $request->input('temp_foto');
            if (str_starts_with($tempPath, 'temp/') && ! str_contains($tempPath, '..')) {
                if (Storage::disk('public')->exists($tempPath)) {
                    // Delete old file if exists
                    if ($prestasi->foto) {
                        Storage::disk('public')->delete($prestasi->foto);
                    }

                    $filename = basename($tempPath);
                    $newPath = 'prestasi/'.$filename;
                    Storage::disk('public')->move($tempPath, $newPath);
                    $data['foto'] = $newPath;
                }
            }
        }

        $prestasi->update($data);

        return redirect()->route('admin.prestasi.index')
            ->with('success', 'Data prestasi berhasil diperbarui.');
    }

    /**
     * Remove the specified achievement from storage.
     */
    public function destroy(Prestasi $prestasi): RedirectResponse
    {
        Gate::authorize('delete', $prestasi);

        $prestasi->delete();

        return redirect()->route('admin.prestasi.index')
            ->with('success', 'Data prestasi berhasil dihapus.');
    }
}
