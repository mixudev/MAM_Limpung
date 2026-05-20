<?php

namespace App\Http\Controllers\Dashboard\Announcement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Announcement\StoreAnnounceAdRequest;
use App\Http\Requests\Dashboard\Announcement\UpdateAnnounceAdRequest;
use App\Models\AnnounceAd;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnnounceAdController extends Controller
{
    public function create(): View
    {
        return view('dashboard.admin.announcement.ad.create');
    }

    public function store(StoreAnnounceAdRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $file = $data['image'];
            $filename = 'ad_'.uniqid().'.'.$file->getClientOriginalExtension();
            $data['image'] = $file->storeAs('announcements/ads', $filename, 'public');
        }

        AnnounceAd::create($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Iklan banner baru berhasil dibuat.');
    }

    public function edit(AnnounceAd $announceAd): View
    {
        return view('dashboard.admin.announcement.ad.edit', compact('announceAd'));
    }

    public function update(UpdateAnnounceAdRequest $request, AnnounceAd $announceAd): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            if ($announceAd->image) {
                Storage::disk('public')->delete($announceAd->image);
            }
            $file = $data['image'];
            $filename = 'ad_'.uniqid().'.'.$file->getClientOriginalExtension();
            $data['image'] = $file->storeAs('announcements/ads', $filename, 'public');
        }

        $announceAd->update($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Iklan banner berhasil diperbarui.');
    }

    public function destroy(AnnounceAd $announceAd): RedirectResponse
    {
        if ($announceAd->image) {
            Storage::disk('public')->delete($announceAd->image);
        }
        $announceAd->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Iklan banner berhasil dihapus.');
    }

    public function toggleActive(AnnounceAd $announceAd): RedirectResponse
    {
        $announceAd->update(['is_active' => ! $announceAd->is_active]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Status keaktifan iklan banner berhasil diubah.');
    }
}
