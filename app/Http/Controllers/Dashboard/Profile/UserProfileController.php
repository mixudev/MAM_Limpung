<?php

namespace App\Http\Controllers\Dashboard\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit(): View
    {
        return view('dashboard.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];

        // If new password is provided, validate password and current password
        if ($request->filled('new_password')) {
            $rules['current_password'] = ['required', 'string'];
            $rules['new_password'] = ['required', 'string', Password::defaults(), 'confirmed'];
        }

        $validated = $request->validate($rules, [
            'avatar.image' => 'File avatar harus berupa gambar.',
            'avatar.mimes' => 'Format file avatar harus jpeg, png, jpg, atau webp.',
            'avatar.max' => 'Ukuran gambar avatar maksimal 2MB.',
        ]);

        // Verify current password if changing password
        if ($request->filled('new_password')) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok dengan data kami.'])
                    ->withInput();
            }

            $user->password = Hash::make($validated['new_password']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Hapus berkas avatar lama jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Nama berkas acak yang aman
            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('avatars', $safeName, 'public');

            $user->avatar = $path;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->save();

        // Record security log
        SystemLogService::logSecurity(
            'profile_update',
            "Pengguna {$user->name} memperbarui data profil dirinya sendiri",
            $user
        );

        return redirect()->back()->with(
            'success',
            'Profil Berhasil Diperbarui!|Data profil diri Anda telah berhasil diperbarui dan disimpan secara aman.'
        );
    }
}
