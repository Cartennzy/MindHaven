<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RumahSakit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class RumahSakitController extends Controller
{
    public function index()
    {
        $rumahSakits = RumahSakit::with(['psikiaters'])
            ->latest('id_rumahsakit')
            ->get();

        return view('backend.admin.rumah_sakit.index', compact('rumahSakits'));
    }

    public function create()
    {
        return view('backend.admin.rumah_sakit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rumahsakit' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:30',
            'status' => 'nullable|in:aktif,nonaktif',
            'website' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('rumah-sakit', 'public');
        }

        $data = [
            'nama_rumahsakit' => $request->nama_rumahsakit,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
        ];

        if (Schema::hasColumn('rumah_sakits', 'status')) {
            $data['status'] = $request->status ?? 'aktif';
        }

        if (Schema::hasColumn('rumah_sakits', 'website')) {
            $data['website'] = $request->website;
        }

        if (Schema::hasColumn('rumah_sakits', 'foto')) {
            $data['foto'] = $fotoPath;
        }

        RumahSakit::create($data);

        return redirect()
            ->route('admin.rumah-sakit.index')
            ->with('success', 'Data rumah sakit berhasil ditambahkan.');
    }

    public function show(RumahSakit $rumahSakit)
    {
        $rumahSakit->load(['psikiaters']);

        return view('backend.admin.rumah_sakit.show', compact('rumahSakit'));
    }

    public function edit(RumahSakit $rumahSakit)
    {
        return view('backend.admin.rumah_sakit.edit', compact('rumahSakit'));
    }

    public function update(Request $request, RumahSakit $rumahSakit)
    {
        $request->validate([
            'nama_rumahsakit' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:30',
            'status' => 'nullable|in:aktif,nonaktif',
            'website' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = $rumahSakit->foto ?? null;

        if ($request->hasFile('foto')) {
            if (
                Schema::hasColumn('rumah_sakits', 'foto') &&
                $rumahSakit->foto &&
                Storage::disk('public')->exists($rumahSakit->foto)
            ) {
                Storage::disk('public')->delete($rumahSakit->foto);
            }

            $fotoPath = $request->file('foto')->store('rumah-sakit', 'public');
        }

        $data = [
            'nama_rumahsakit' => $request->nama_rumahsakit,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
        ];

        if (Schema::hasColumn('rumah_sakits', 'status')) {
            $data['status'] = $request->status ?? 'aktif';
        }

        if (Schema::hasColumn('rumah_sakits', 'website')) {
            $data['website'] = $request->website;
        }

        if (Schema::hasColumn('rumah_sakits', 'foto')) {
            $data['foto'] = $fotoPath;
        }

        $rumahSakit->update($data);

        return redirect()
            ->route('admin.rumah-sakit.index')
            ->with('success', 'Data rumah sakit berhasil diperbarui.');
    }

    public function destroy(RumahSakit $rumahSakit)
    {
        if (
            Schema::hasColumn('rumah_sakits', 'foto') &&
            $rumahSakit->foto &&
            Storage::disk('public')->exists($rumahSakit->foto)
        ) {
            Storage::disk('public')->delete($rumahSakit->foto);
        }

        $rumahSakit->delete();

        return redirect()
            ->route('admin.rumah-sakit.index')
            ->with('success', 'Data rumah sakit berhasil dihapus.');
    }
}