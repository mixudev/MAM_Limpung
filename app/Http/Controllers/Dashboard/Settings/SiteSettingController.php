<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\UpdateSiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        $siteSetting = SiteSetting::first() ?? SiteSetting::create([
            'school_name' => 'MAM Limpung',
            'about_short' => 'MA Muhammadiyah Limpung adalah lembaga pendidikan Islam yang berkomitmen untuk membentuk generasi yang berakhlak mulia, cerdas, dan siap menghadapi tantangan masa depan dengan landasan nilai-nilai Islam dan kemajuan teknologi.',
            'email' => 'info@mamlimpung.sch.id',
            'phone' => '+62 21 1234 5678',
            'whatsapp' => '+62 812 3456 789',
            'address' => 'Jl. Cokronegoro No.34, Gepor, Limpung, Kabupaten Batang, Jawa Tengah 51271',
            'facebook_url' => 'https://facebook.com',
            'instagram_url' => 'https://instagram.com',
            'youtube_url' => 'https://youtube.com',
            'twitter_url' => 'https://twitter.com',
        ]);

        return view('dashboard.admin.settings.edit', compact('siteSetting'));
    }

    public function update(UpdateSiteSettingRequest $request): RedirectResponse
    {
        $siteSetting = SiteSetting::first() ?? SiteSetting::create(['school_name' => 'MAM Limpung']);
        $data = $request->validated();

        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            if ($siteSetting->logo_path) {
                Storage::disk('public')->delete($siteSetting->logo_path);
            }
            $file = $data['logo'];
            $filename = 'logo_'.uniqid().'.'.$file->getClientOriginalExtension();
            $data['logo_path'] = $file->storeAs('settings', $filename, 'public');
        }
        unset($data['logo']);

        $siteSetting->update($data);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Pengaturan informasi website berhasil diperbarui.');
    }
}
