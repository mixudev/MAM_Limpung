<?php

namespace App\Http\Controllers\Dashboard\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Security\StoreUserRequest;
use App\Http\Requests\Dashboard\Security\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserAccountController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view-users', User::class);

        $query = User::with('roles');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role Filter
        if ($request->filled('role')) {
            $query->role($request->input('role'));
        }

        // Sort & Paginate
        $users = $query->orderBy('name')->paginate(10)->withQueryString();
        $roles = Role::orderBy('level', 'desc')->get();

        return view('dashboard.admin.security.users.index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;

        /** @var User $user */
        $user = User::create($data);
        $user->syncRoles($request->input('roles'));

        return redirect()->back()
            ->with('success', "Akun untuk '{$user->name}' berhasil ditambahkan.");
    }

    public function show(User $user): View
    {
        Gate::authorize('view-users', User::class);

        $user->load('roles');
        $roles = Role::orderBy('level', 'desc')->get();

        return view('dashboard.admin.security.users.show', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        // Safety check: prevent self-deactivation
        if ($user->id === $request->user()->id && ! $request->boolean('is_active', true)) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);
        $user->syncRoles($request->input('roles'));

        return redirect()->back()
            ->with('success', "Akun '{$user->name}' berhasil diperbarui.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('delete-users', User::class);

        // Safety check: prevent self-deletion
        if ($user->id === $request->user()->id) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->back()
            ->with('success', "Akun '{$user->name}' berhasil dihapus.");
    }
}
