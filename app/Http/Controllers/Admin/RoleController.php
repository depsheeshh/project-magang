<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth','role:admin']);
    // }

    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        $permissions = Permission::all(); // untuk modal create/edit
        return view('admin.roles.index', compact('roles','permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dibuat');
    }

    public function show(Role $role)
    {
        $role->load('permissions'); // eager load permissions
        return view('admin.roles.show', compact('role'));
    }


    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'        => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus');
    }
}
