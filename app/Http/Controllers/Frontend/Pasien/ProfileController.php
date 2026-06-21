<?php

namespace App\Http\Controllers\Frontend\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun ini belum terhubung dengan data pasien.');
        }

        return view('frontend.pasien.profile', compact('pasien'));
    }

    public function update(Request $request)
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun ini belum terhubung dengan data pasien.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'nama_lengkap',
            'no_telepon',
            'tanggal_lahir',
            'jenis_kelamin',
            'alamat',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($pasien->foto_profil && Storage::disk('public')->exists($pasien->foto_profil)) {
                Storage::disk('public')->delete($pasien->foto_profil);
            }

            $data['foto_profil'] = $request->file('foto_profil')->store('foto_profil/pasien', 'public');
        }

        $pasien->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}