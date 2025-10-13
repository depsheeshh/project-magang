<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\HistoryLog;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles','permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => strip_tags($validated['name']),
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        HistoryLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'create',
            'table_name' => 'roles',
            'record_id'  => $role->id,
            'reason'     => 'Membuat role baru',
            'new_values' => json_encode(['name' => $role->name]),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dibuat');
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return view('admin.roles.show', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $old = $role->toArray();

        $role->update([
            'name' => strip_tags($validated['name']),
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        HistoryLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'update',
            'table_name' => 'roles',
            'record_id'  => $role->id,
            'reason'     => 'Memperbarui role',
            'old_values' => json_encode($old),
            'new_values' => json_encode($role->toArray()),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        $id = $role->id;
        $old = $role->toArray();

        $role->delete();

        HistoryLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'delete',
            'table_name' => 'roles',
            'record_id'  => $id,
            'reason'     => 'Menghapus role',
            'old_values' => json_encode($old),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus');
    }
}
