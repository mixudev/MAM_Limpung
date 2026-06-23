<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreTeacherRequest;
use App\Http\Requests\Dashboard\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Models\TeacherCategory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view-teachers');

        $query = Teacher::with(['user', 'category']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('teacher_category_id', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $teachers = $query->orderBy('nama')->paginate(15)->withQueryString();
        $categories = TeacherCategory::orderBy('name')->get();

        return view('dashboard.admin.academic.teachers.index', compact('teachers', 'categories'));
    }

    public function create(): View
    {
        Gate::authorize('create-teachers');

        $categories = TeacherCategory::orderBy('name')->get();

        return view('dashboard.admin.academic.teachers.form', [
            'teacher' => null,
            'categories' => $categories,
        ]);
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);
        $user->assignRole('guru');

        $teacherData = [
            'user_id' => $user->id,
            'nip' => $data['nip'] ?? null,
            'nama' => $data['nama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'no_telepon' => $data['no_telepon'] ?? null,
            'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? null,
            'jurusan' => $data['jurusan'] ?? null,
            'tanggal_masuk' => $data['tanggal_masuk'] ?? null,
            'status' => $data['status'],
            'quote' => $data['quote'] ?? null,
            'teacher_category_id' => $data['teacher_category_id'] ?? null,
        ];

        if ($request->hasFile('foto')) {
            $teacherData['foto'] = $request->file('foto')->store('teachers', 'public');
        }

        Teacher::create($teacherData);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Guru '.$data['nama'].' berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher): View
    {
        Gate::authorize('edit-teachers');

        $teacher->load(['user', 'category']);
        $categories = TeacherCategory::orderBy('name')->get();

        return view('dashboard.admin.academic.teachers.form', compact('teacher', 'categories'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $data = $request->validated();

        $userData = [
            'name' => $data['nama'],
            'email' => $data['email'],
        ];
        if (! empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }
        $teacher->user->update($userData);

        $teacherData = [
            'nip' => $data['nip'] ?? null,
            'nama' => $data['nama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'no_telepon' => $data['no_telepon'] ?? null,
            'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? null,
            'jurusan' => $data['jurusan'] ?? null,
            'tanggal_masuk' => $data['tanggal_masuk'] ?? null,
            'status' => $data['status'],
            'quote' => $data['quote'] ?? null,
            'teacher_category_id' => $data['teacher_category_id'] ?? null,
        ];

        if ($request->hasFile('foto')) {
            if ($teacher->foto) {
                Storage::disk('public')->delete($teacher->foto);
            }
            $teacherData['foto'] = $request->file('foto')->store('teachers', 'public');
        }

        $teacher->update($teacherData);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Data guru '.$data['nama'].' berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        Gate::authorize('delete-teachers');

        $nama = $teacher->nama;

        if ($teacher->foto) {
            Storage::disk('public')->delete($teacher->foto);
        }

        $teacher->user->delete();
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Guru '.$nama.' berhasil dihapus.');
    }
}
