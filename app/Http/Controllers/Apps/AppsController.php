<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Mail\User\ForgotPasswordMail;
use App\Models\Article;
use App\Models\Galeri;
use App\Services\SmtpService;
use App\Services\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AppsController extends Controller
{
    /**
     * Mobile app homepage / dashboard
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Enforce student role
        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        // Enforce mobile device check
        $userAgent = $request->header('User-Agent', '');
        $isMobile = (bool) preg_match('/(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini)/i', $userAgent);

        if (! $isMobile) {
            return redirect()->route('dashboard');
        }

        // Fetch user statistics
        $stats = [
            'total_galeri' => Galeri::where('user_id', $user->id)->count(),
            'total_artikel' => Article::where('user_id', $user->id)->count(),
            'total_tugas' => 3, // Mock tasks count
        ];

        // Fetch recent user galleries
        $recentGalleries = Galeri::where('user_id', $user->id)
            ->with('photos')
            ->latest()
            ->take(3)
            ->get();

        // Fetch recent user articles
        $recentArticles = Article::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        return view('mobile_apps.index', compact('stats', 'recentGalleries', 'recentArticles'));
    }

    /**
     * Mobile app user profile page
     */
    public function profile(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        return view('mobile_apps.profile', compact('user'));
    }

    /**
     * Update student profile (name, email, avatar)
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'avatar.image' => 'File avatar harus berupa gambar.',
            'avatar.max' => 'Ukuran gambar avatar maksimal 1MB.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Kelola file unggahan avatar dengan nama acak aman
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Hapus berkas avatar lama jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Nama berkas acak yang aman
            $safeName = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('avatars', $safeName, 'public');

            $data['avatar'] = $path;
        }

        $user->update($data);

        SystemLogService::logSecurity('profile_updated_mobile', 'Siswa memperbarui profil mereka melalui mobile app', $user);

        return redirect()->route('apps.profile')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Send password reset link to student
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return redirect()->route('dashboard');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        app(SmtpService::class)->sendQuiet(
            new ForgotPasswordMail($user, $resetUrl),
            $user->email,
            $user->name
        );

        SystemLogService::logSecurity('profile_password_reset_link_sent', 'Pengguna meminta tautan reset password dari profil mobile', $user);

        return redirect()->back()->with('success_password', 'Tautan untuk mengatur ulang kata sandi telah dikirim ke email Anda.');
    }
}
