<?php

namespace App\Http\Controllers\Dashboard\Announcement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Announcement\StoreAnnounceAlertRequest;
use App\Http\Requests\Dashboard\Announcement\UpdateAnnounceAlertRequest;
use App\Models\AnnounceAlert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnnounceAlertController extends Controller
{
    public function create(): View
    {
        return view('dashboard.admin.announcement.alert.create');
    }

    public function store(StoreAnnounceAlertRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('images')) {
            $paths = [];
            $mainPath = null;
            $mainOriginalName = $request->input('main_image_name');

            foreach ($request->file('images') as $file) {
                $storedPath = $file->store('announcements/alerts', 'public');
                if ($mainOriginalName && $file->getClientOriginalName() === $mainOriginalName) {
                    $mainPath = $storedPath;
                }
                $paths[] = $storedPath;
            }

            if ($mainPath && in_array($mainPath, $paths)) {
                $paths = array_filter($paths, fn ($p) => $p !== $mainPath);
                array_unshift($paths, $mainPath);
                $paths = array_values($paths);
            }

            $data['image'] = $paths;
        }

        unset($data['main_image_path'], $data['main_image_name']);

        AnnounceAlert::create($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Popup Alert berhasil dibuat.');
    }

    public function edit(AnnounceAlert $announceAlert): View
    {
        return view('dashboard.admin.announcement.alert.edit', compact('announceAlert'));
    }

    public function update(UpdateAnnounceAlertRequest $request, AnnounceAlert $announceAlert): RedirectResponse
    {
        $data = $request->validated();

        $retainedImages = $request->input('retained_images', []);
        $currentImages = is_array($announceAlert->image) ? $announceAlert->image : [];

        // Delete images that are no longer retained
        foreach ($currentImages as $oldPath) {
            if (! in_array($oldPath, $retainedImages)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $paths = $retainedImages;
        $mainPath = null;
        $mainOriginalName = $request->input('main_image_name');

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $storedPath = $file->store('announcements/alerts', 'public');
                if ($mainOriginalName && $file->getClientOriginalName() === $mainOriginalName) {
                    $mainPath = $storedPath;
                }
                $paths[] = $storedPath;
            }
        }

        if ($request->filled('main_image_path')) {
            $mainPath = $request->input('main_image_path');
        }

        if ($mainPath && in_array($mainPath, $paths)) {
            $paths = array_filter($paths, fn ($p) => $p !== $mainPath);
            array_unshift($paths, $mainPath);
            $paths = array_values($paths);
        }

        $data['image'] = ! empty($paths) ? $paths : null;

        unset($data['retained_images'], $data['images'], $data['main_image_path'], $data['main_image_name']);

        $announceAlert->update($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Popup Alert berhasil diperbarui.');
    }

    public function destroy(AnnounceAlert $announceAlert): RedirectResponse
    {
        // Delete all images
        if (is_array($announceAlert->image)) {
            foreach ($announceAlert->image as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $announceAlert->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Popup Alert berhasil dihapus.');
    }

    public function toggleActive(AnnounceAlert $announceAlert): RedirectResponse
    {
        $announceAlert->update(['is_active' => ! $announceAlert->is_active]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Status keaktifan Popup Alert berhasil diubah.');
    }
}
