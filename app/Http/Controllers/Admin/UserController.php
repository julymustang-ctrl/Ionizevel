<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::active()->orderBy('role_level', 'desc')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:120',
            'password' => 'required|string|min:6|confirmed',
            'id_role' => 'required|exists:roles,id_role',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $request->id_role,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'screen_name' => $request->screen_name,
            'join_date' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı oluşturuldu.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::active()->orderBy('role_level', 'desc')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $id . ',id_user',
            'email' => 'required|email|max:120',
            'id_role' => 'required|exists:roles,id_role',
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'id_role' => $request->id_role,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'screen_name' => $request->screen_name,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı güncellendi.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id_user === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Kendinizi silemezsiniz.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı silindi.');
    }
}
