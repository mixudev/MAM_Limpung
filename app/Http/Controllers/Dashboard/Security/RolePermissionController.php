<?php

namespace App\Http\Controllers\Dashboard\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Security\StoreRoleRequest;
use App\Http\Requests\Dashboard\Security\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $roles = Role::orderBy('level', 'desc')->get();
        $permissionsGrouped = Permission::orderBy('group')->get()->groupBy('group');

        return view('dashboard.admin.security.index', compact('roles', 'permissionsGrouped'));
    }

    public function storeRole(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'level' => $request->level,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('super-admin.roles-permissions.index')
            ->with('success', "Role '{$role->display_name}' berhasil ditambahkan.");
    }

    public function updateRole(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $isSystemRole = in_array($role->name, ['super-admin', 'admin', 'guru', 'siswa']);

        $data = $request->validated();
        if ($isSystemRole) {
            unset($data['name'], $data['level']);
        }

        $role->update($data);

        if ($role->name !== 'super-admin') {
            $role->syncPermissions($request->permissions ?? []);
        } else {
            $allPermissions = Permission::where('guard_name', 'web')->pluck('name')->toArray();
            $role->syncPermissions($allPermissions);
        }

        return redirect()->route('super-admin.roles-permissions.index')
            ->with('success', "Role '{$role->display_name}' berhasil diperbarui.");
    }

    public function destroyRole(Role $role): RedirectResponse
    {
        if (in_array($role->name, ['super-admin', 'admin', 'guru', 'siswa'])) {
            return redirect()->route('super-admin.roles-permissions.index')
                ->with('error', 'Role sistem tidak dapat dihapus.');
        }

        $roleName = $role->display_name;
        $role->delete();

        return redirect()->route('super-admin.roles-permissions.index')
            ->with('success', "Role '{$roleName}' berhasil dihapus.");
    }
}
