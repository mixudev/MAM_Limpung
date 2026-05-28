<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(): View
    {
        $galeris = Galeri::visible()
            ->approved()
            ->with(['photos' => function ($query) {
                $query->orderBy('is_cover', 'desc')->orderBy('order', 'asc');
            }])
            ->latest()
            ->get();

        $albums = $galeris->map(function ($galeri) {
            return [
                'id' => $galeri->id,
                'title' => $galeri->judul,
                'category' => $galeri->kategori ?? 'Umum',
                'date' => $galeri->tahun ?: $galeri->created_at->translatedFormat('M Y'),
                'description' => $galeri->deskripsi,
                'images' => $galeri->photos->map(function ($photo) {
                    return $photo->imageUrl();
                })->toArray(),
            ];
        });

        // Unique categories list for filters (Always prepend 'Semua')
        $categories = collect(['Semua'])
            ->concat($galeris->pluck('kategori')->filter()->unique())
            ->values()
            ->toArray();

        // Fallback categories if database is empty
        if (count($categories) === 1) {
            $categories = ['Semua', 'Belajar', 'Ekskul', 'Fasilitas', 'Event Seru'];
        }

        return view('front.galeri.index', compact('albums', 'categories'));
    }
}
