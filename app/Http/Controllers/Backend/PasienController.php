<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::with('user')
            ->latest('id_pasien')
            ->get();

        return view('backend.admin.pasien.index', compact('pasiens'));
    }

    public function create()
    {
        return view('backend.admin.pasien.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoProfil = null;

        if ($request->hasFile('foto_profil')) {
            $fotoProfil = $request->file('foto_profil')
                ->store('foto_profil/pasien', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pasien',
        ]);

        Pasien::create([
            'user_id' => $user->id,
            'nama_lengkap' => $request->nama_lengkap,
            'no_telepon' => $request->no_telepon,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'foto_profil' => $fotoProfil,
        ]);

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function show(Pasien $pasien)
    {
        $pasien->load([
            'user',
            'konsultasis',
            'rujukanPsikiaters',
        ]);

        return view('backend.admin.pasien.show', compact('pasien'));
    }

    public function edit(Pasien $pasien)
    {
        $pasien->load('user');

        return view('backend.admin.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pasien->user_id,
            'password' => 'nullable|min:6',
            'no_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pasien->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $pasien->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'no_telepon' => $request->no_telepon,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ];

        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')
                ->store('foto_profil/pasien', 'public');
        }

        $pasien->update($data);

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Pasien $pasien)
    {
        if ($pasien->user) {
            $pasien->user->delete();
        } else {
            $pasien->delete();
        }

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }
}