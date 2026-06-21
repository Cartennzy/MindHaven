<?php

namespace App\Http\Controllers\Frontend\Psikolog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $psikolog = $user->psikolog;

        if (!$psikolog) {
            abort(403, 'Akun ini belum terhubung dengan data psikolog.');
        }

        return view('frontend.psikolog.profile', compact('user', 'psikolog'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $psikolog = $user->psikolog;

        if (!$psikolog) {
            abort(403, 'Akun ini belum terhubung dengan data psikolog.');
        }

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:30',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',
            'spesialisasi' => 'required|string|max:255',
            'pengalaman' => 'nullable|integer|min:0',
            'biaya_konsultasi' => 'nullable|numeric|min:0',
            'pendidikan' => 'nullable|string',
            'str_psikolog' => 'nullable|string|max:255',
            'sip_psikolog' => 'nullable|string|max:255',
            'jadwal_praktik' => 'required|array|min:1', // MODIFIKASI: Validasi diubah ke array untuk menampung multi-checkbox hari
            'bio' => 'nullable|string',
            'metode_konsultasi' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // KONDISIONAL SINKRONISASI: Konversi data array input checkbox hari menjadi string terpisah koma sebelum disimpan ke database
        if (is_array($request->jadwal_praktik)) {
            $validated['jadwal_praktik'] = implode(', ', $request->jadwal_praktik);
        }

        if ($request->hasFile('foto_profil')) {
            if ($psikolog->foto_profil && Storage::disk('public')->exists($psikolog->foto_profil)) {
                Storage::disk('public')->delete($psikolog->foto_profil);
            }

            $validated['foto_profil'] = $request->file('foto_profil')->store('psikolog/foto-profil', 'public');
        }

        $psikolog->update($validated);

        return redirect()
            ->route('psikolog.profile')
            ->with('success', 'Profile berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}