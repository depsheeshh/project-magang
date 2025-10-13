<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users','roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols(),
            ],
            'role'     => 'required|exists:roles,name',
            'status'   => 'required|in:0,1',
        ]);

        // Sanitasi input
        $validated['name']  = strip_tags($validated['name']);
        $validated['email'] = strip_tags($validated['email']);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'status'            => (int) $validated['status'],
            'email_verified_at' => now(),
            'created_id'        => Auth::id(),
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique('users','email')->ignore($user->id),
            ],
            'role'     => 'required|exists:roles,name',
            'status'   => 'required|in:0,1',
            'old_password' => 'nullable|required_with:new_password',
            'new_password' => [
                'nullable',
                'confirmed',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols(),
            ],
        ]);

        // Sanitasi input
        $validated['name']  = strip_tags($validated['name']);
        $validated['email'] = strip_tags($validated['email']);

        $data = [
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'status'     => (int) $validated['status'],
            'updated_id' => Auth::id(),
        ];

        // Validasi password lama & baru
        if ($request->filled('new_password')) {
            if (!$request->filled('old_password')) {
                return back()->withErrors(['old_password' => 'Password lama wajib diisi untuk mengganti password.']);
            }

            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password lama tidak sesuai.']);
            }

            if (Hash::check($request->new_password, $user->password)) {
                return back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password lama.']);
            }

            $data['password'] = Hash::make($request->new_password);
        }

        $user->update($data);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        // Isi deleted_id sebelum soft delete
        $user->update(['deleted_id' => Auth::id()]);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
