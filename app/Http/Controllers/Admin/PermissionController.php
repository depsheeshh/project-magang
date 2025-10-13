<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\HistoryLog;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name',
        ]);

        $permission = Permission::create([
            'name' => strip_tags($validated['name']),
        ]);

        HistoryLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'create',
            'table_name' => 'permissions',
            'record_id'  => $permission->id,
            'reason'     => 'Membuat permission baru',
            'new_values' => json_encode(['name' => $permission->name]),
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil dibuat');
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name,' . $permission->id,
        ]);

        $old = $permission->toArray();

        $permission->update([
            'name' => strip_tags($validated['name']),
        ]);

        HistoryLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'update',
            'table_name' => 'permissions',
            'record_id'  => $permission->id,
            'reason'     => 'Memperbarui permission',
            'old_values' => json_encode($old),
            'new_values' => json_encode($permission->toArray()),
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil diperbarui');
    }

    public function destroy(Permission $permission)
    {
        $id = $permission->id;
        $old = $permission->toArray();

        $permission->delete();

        HistoryLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'delete',
            'table_name' => 'permissions',
            'record_id'  => $id,
            'reason'     => 'Menghapus permission',
            'old_values' => json_encode($old),
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil dihapus');
    }
}
