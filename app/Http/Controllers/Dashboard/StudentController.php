<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreStudentRequest;
use App\Http\Requests\Dashboard\UpdateStudentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view-students');

        $query = Student::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $students = $query->orderBy('nama')->paginate(15)->withQueryString();

        return view('dashboard.admin.academic.students.index', compact('students'));
    }

    public function create(): View
    {
        Gate::authorize('create-students');

        return view('dashboard.admin.academic.students.form', [
            'student' => null,
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);
        $user->assignRole('siswa');

        $studentData = [
            'user_id' => $user->id,
            'nis' => $data['nis'] ?? null,
            'nisn' => $data['nisn'] ?? null,
            'nama' => $data['nama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'no_telepon' => $data['no_telepon'] ?? null,
            'nama_ayah' => $data['nama_ayah'] ?? null,
            'nama_ibu' => $data['nama_ibu'] ?? null,
            'pekerjaan_ayah' => $data['pekerjaan_ayah'] ?? null,
            'pekerjaan_ibu' => $data['pekerjaan_ibu'] ?? null,
            'alamat_orang_tua' => $data['alamat_orang_tua'] ?? null,
            'no_telepon_orang_tua' => $data['no_telepon_orang_tua'] ?? null,
            'tanggal_masuk' => $data['tanggal_masuk'] ?? null,
            'status' => $data['status'],
        ];

        if ($request->hasFile('foto')) {
            $studentData['foto'] = $request->file('foto')->store('students', 'public');
        }

        Student::create($studentData);

        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa '.$data['nama'].' berhasil ditambahkan.');
    }

    public function edit(Student $student): View
    {
        Gate::authorize('edit-students');

        $student->load('user');

        return view('dashboard.admin.academic.students.form', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $data = $request->validated();

        $userData = [
            'name' => $data['nama'],
            'email' => $data['email'],
        ];
        if (! empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }
        $student->user->update($userData);

        $studentData = [
            'nis' => $data['nis'] ?? null,
            'nisn' => $data['nisn'] ?? null,
            'nama' => $data['nama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'no_telepon' => $data['no_telepon'] ?? null,
            'nama_ayah' => $data['nama_ayah'] ?? null,
            'nama_ibu' => $data['nama_ibu'] ?? null,
            'pekerjaan_ayah' => $data['pekerjaan_ayah'] ?? null,
            'pekerjaan_ibu' => $data['pekerjaan_ibu'] ?? null,
            'alamat_orang_tua' => $data['alamat_orang_tua'] ?? null,
            'no_telepon_orang_tua' => $data['no_telepon_orang_tua'] ?? null,
            'tanggal_masuk' => $data['tanggal_masuk'] ?? null,
            'status' => $data['status'],
        ];

        if ($request->hasFile('foto')) {
            if ($student->foto) {
                Storage::disk('public')->delete($student->foto);
            }
            $studentData['foto'] = $request->file('foto')->store('students', 'public');
        }

        $student->update($studentData);

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa '.$data['nama'].' berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        Gate::authorize('delete-students');

        $nama = $student->nama;

        if ($student->foto) {
            Storage::disk('public')->delete($student->foto);
        }

        $student->user->delete();
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa '.$nama.' berhasil dihapus.');
    }
}
