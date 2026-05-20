<?php

namespace App\Http\Controllers\Dashboard\Announcement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Announcement\StoreAnnounceTextRequest;
use App\Http\Requests\Dashboard\Announcement\UpdateAnnounceTextRequest;
use App\Models\AnnounceText;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnounceTextController extends Controller
{
    public function create(): View
    {
        return view('dashboard.admin.announcement.text.create');
    }

    public function store(StoreAnnounceTextRequest $request): RedirectResponse
    {
        AnnounceText::create($request->validated());

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman teks berjalan berhasil dibuat.');
    }

    public function edit(AnnounceText $announceText): View
    {
        return view('dashboard.admin.announcement.text.edit', compact('announceText'));
    }

    public function update(UpdateAnnounceTextRequest $request, AnnounceText $announceText): RedirectResponse
    {
        $announceText->update($request->validated());

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman teks berjalan berhasil diperbarui.');
    }

    public function destroy(AnnounceText $announceText): RedirectResponse
    {
        $announceText->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman teks berjalan berhasil dihapus.');
    }

    public function toggleActive(AnnounceText $announceText): RedirectResponse
    {
        $announceText->update(['is_active' => ! $announceText->is_active]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Status keaktifan teks berjalan berhasil diubah.');
    }
}
