<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

//     public function __construct()
// {
//     $this->middleware(['auth', 'role:admin']);
// }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // eager load relasi roles supaya data role selalu fresh
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users','roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => [
                'required',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols(),
            ],
            'role'     => 'required|exists:roles,name',
            'status'   => 'required|in:0,1',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'status'   => (int) $request->status, // langsung ambil nilainya
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|exists:roles,name',
            'status'   => 'required|in:0,1',
            'password' => [
                'sometimes',
                'nullable',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols(),
            ],
        ]);

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'status' => (int) $request->status, // jangan kasih default 1
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
