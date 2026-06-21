<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::latest('id_admin')->get();

        return view('backend.admin.admin.index', compact('admins'));
    }

    public function create()
    {
        return view('backend.admin.admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:admins,email',
            'password' => 'required|min:6',
            'no_telepon' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoProfil = null;

        if ($request->hasFile('foto_profil')) {
            $fotoProfil = $request->file('foto_profil')->store('foto_profil/admin', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        $data = [
            'nama' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        if (Schema::hasColumn('admins', 'user_id')) {
            $user = User::where('email', $request->email)->first();
            $data['user_id'] = $user?->id;
        }

        if (Schema::hasColumn('admins', 'nama_lengkap')) {
            $data['nama_lengkap'] = $request->nama_lengkap;
        }

        if (Schema::hasColumn('admins', 'no_telepon')) {
            $data['no_telepon'] = $request->no_telepon;
        }

        if (Schema::hasColumn('admins', 'foto_profil')) {
            $data['foto_profil'] = $fotoProfil;
        }

        Admin::create($data);

        return redirect()->route('admin.admin.index')
            ->with('success', 'Data admin berhasil ditambahkan.');
    }

    public function show(Admin $admin)
    {
        return view('backend.admin.admin.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('backend.admin.admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id_admin . ',id_admin',
            'password' => 'nullable|min:6',
            'no_telepon' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::where('email', $admin->email)->first();

        if ($user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        }

        $data = [
            'nama' => $request->nama_lengkap,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if (Schema::hasColumn('admins', 'nama_lengkap')) {
            $data['nama_lengkap'] = $request->nama_lengkap;
        }

        if (Schema::hasColumn('admins', 'no_telepon')) {
            $data['no_telepon'] = $request->no_telepon;
        }

        if ($request->hasFile('foto_profil') && Schema::hasColumn('admins', 'foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')->store('foto_profil/admin', 'public');
        }

        $admin->update($data);

        return redirect()->route('admin.admin.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        $user = User::where('email', $admin->email)->first();

        if ($user) {
            $user->delete();
        }

        $admin->delete();

        return redirect()->route('admin.admin.index')
            ->with('success', 'Data admin berhasil dihapus.');
    }
}