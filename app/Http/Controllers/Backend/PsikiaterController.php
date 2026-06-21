<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Psikiater;
use App\Models\RumahSakit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PsikiaterController extends Controller
{
    public function index()
    {
        $psikiaters = Psikiater::with('rumahSakit')
            ->latest('id_psikiater')
            ->get();

        return view('backend.admin.psikiater.index', compact('psikiaters'));
    }

    public function create()
    {
        $rumahSakits = RumahSakit::latest('id_rumahsakit')->get();

        return view('backend.admin.psikiater.create', compact('rumahSakits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_rumahsakit' => 'required|exists:rumah_sakits,id_rumahsakit',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:psikiaters,email',
            'spesialisasi' => 'required|string|max:255',
            'str_psikiater' => 'nullable|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'jadwal_praktik' => 'nullable|string|max:255',
            'alamat_praktik' => 'nullable|string',
            'pengalaman' => 'nullable|integer|min:0',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $fotoProfil = null;

        if ($request->hasFile('foto_profil')) {
            $fotoProfil = $request->file('foto_profil')
                ->store('foto_profil/psikiater', 'public');
        }

        Psikiater::create([
            'id_rumahsakit' => $request->id_rumahsakit,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'spesialisasi' => $request->spesialisasi,
            'str_psikiater' => $request->str_psikiater,
            'no_telepon' => $request->no_telepon,
            'jadwal_praktik' => $request->jadwal_praktik,
            'alamat_praktik' => $request->alamat_praktik,
            'pengalaman' => $request->pengalaman ?? 0,
            'foto_profil' => $fotoProfil,
            'status' => $request->status ?? true,
        ]);

        return redirect()
            ->route('admin.psikiater.index')
            ->with('success', 'Data psikiater berhasil ditambahkan.');
    }

    public function show(Psikiater $psikiater)
    {
        $psikiater->load(['rumahSakit', 'rujukanPsikiaters']);

        return view('backend.admin.psikiater.show', compact('psikiater'));
    }

    public function edit(Psikiater $psikiater)
    {
        $rumahSakits = RumahSakit::latest('id_rumahsakit')->get();

        return view('backend.admin.psikiater.edit', compact('psikiater', 'rumahSakits'));
    }

    public function update(Request $request, Psikiater $psikiater)
    {
        $request->validate([
            'id_rumahsakit' => 'required|exists:rumah_sakits,id_rumahsakit',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:psikiaters,email,' . $psikiater->id_psikiater . ',id_psikiater',
            'spesialisasi' => 'required|string|max:255',
            'str_psikiater' => 'nullable|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'jadwal_praktik' => 'nullable|string|max:255',
            'alamat_praktik' => 'nullable|string',
            'pengalaman' => 'nullable|integer|min:0',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $data = [
            'id_rumahsakit' => $request->id_rumahsakit,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'spesialisasi' => $request->spesialisasi,
            'str_psikiater' => $request->str_psikiater,
            'no_telepon' => $request->no_telepon,
            'jadwal_praktik' => $request->jadwal_praktik,
            'alamat_praktik' => $request->alamat_praktik,
            'pengalaman' => $request->pengalaman ?? 0,
            'status' => $request->status ?? false,
        ];

        if ($request->hasFile('foto_profil')) {
            if ($psikiater->foto_profil && Storage::disk('public')->exists($psikiater->foto_profil)) {
                Storage::disk('public')->delete($psikiater->foto_profil);
            }

            $data['foto_profil'] = $request->file('foto_profil')
                ->store('foto_profil/psikiater', 'public');
        }

        $psikiater->update($data);

        return redirect()
            ->route('admin.psikiater.index')
            ->with('success', 'Data psikiater berhasil diperbarui.');
    }

    public function destroy(Psikiater $psikiater)
    {
        if ($psikiater->foto_profil && Storage::disk('public')->exists($psikiater->foto_profil)) {
            Storage::disk('public')->delete($psikiater->foto_profil);
        }

        $psikiater->delete();

        return redirect()
            ->route('admin.psikiater.index')
            ->with('success', 'Data psikiater berhasil dihapus.');
    }
}