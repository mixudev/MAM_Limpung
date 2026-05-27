<?php

namespace App\Http\Controllers\Dashboard\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        ];

        // If new password is provided, validate password and current password
        if ($request->filled('new_password')) {
            $rules['current_password'] = ['required', 'string'];
            $rules['new_password'] = ['required', 'string', Password::defaults(), 'confirmed'];
        }

        $validated = $request->validate($rules);

        // Verify current password if changing password
        if ($request->filled('new_password')) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok dengan data kami.'])
                    ->withInput();
            }

            $user->password = Hash::make($validated['new_password']);
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
