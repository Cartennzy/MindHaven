<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Meditasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MeditasiController extends Controller
{
    public function index()
    {
        $meditasis = Meditasi::with('admin')
            ->latest('id_meditasi')
            ->get();

        return view('backend.admin.meditasi.index', compact('meditasis'));
    }

    public function create()
    {
        $kategoris = [
            'Tidur',
            'Stres',
            'Kecemasan',
            'Fokus',
            'Relaksasi',
            'Percaya Diri',
            'Pernapasan',
            'Overthinking',
        ];

        return view('backend.admin.meditasi.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:100',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_meditasi' => 'required|file|mimes:mp3,wav,m4a,mp4,mov,webm|max:51200',
            'durasi' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
        ], [
            'kategori.required' => 'Kategori meditasi wajib dipilih.',
            'kategori.string' => 'Kategori meditasi harus berupa teks.',
            'kategori.max' => 'Kategori meditasi maksimal 100 karakter.',

            'judul.required' => 'Judul meditasi wajib diisi.',
            'judul.string' => 'Judul meditasi harus berupa teks.',
            'judul.max' => 'Judul meditasi maksimal 255 karakter.',

            'deskripsi.required' => 'Deskripsi meditasi wajib diisi.',
            'deskripsi.string' => 'Deskripsi meditasi harus berupa teks.',

            'file_meditasi.required' => 'Link audio atau file meditasi wajib diunggah.',
            'file_meditasi.file' => 'File meditasi harus berupa file.',
            'file_meditasi.mimes' => 'File meditasi harus berformat MP3, WAV, M4A, MP4, MOV, atau WEBM.',
            'file_meditasi.max' => 'Ukuran file meditasi maksimal 50 MB.',

            'durasi.required' => 'Durasi meditasi wajib diisi.',
            'durasi.integer' => 'Durasi meditasi harus berupa angka.',
            'durasi.min' => 'Durasi meditasi minimal 1 menit.',

            'status.required' => 'Status meditasi wajib dipilih.',
            'status.in' => 'Status meditasi tidak valid.',
        ]);

        $admin = Admin::where('email', Auth::user()->email)->first();

        if (!$admin) {
            return back()
                ->with('error', 'Data admin tidak ditemukan.')
                ->withInput();
        }

        $filePath = null;

        if ($request->hasFile('file_meditasi')) {
            $filePath = $request->file('file_meditasi')->store('meditasi', 'public');
        }

        Meditasi::create([
            'id_admin' => $admin->id_admin,
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'audio' => $filePath,
            'durasi' => $request->durasi,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.meditasi.index')
            ->with('success', 'Data meditasi berhasil ditambahkan.');
    }

    public function show(Meditasi $meditasi)
    {
        $meditasi->load('admin');

        return view('backend.admin.meditasi.show', compact('meditasi'));
    }

    public function edit(Meditasi $meditasi)
    {
        $kategoris = [
            'Tidur',
            'Stres',
            'Kecemasan',
            'Fokus',
            'Relaksasi',
            'Percaya Diri',
            'Pernapasan',
            'Overthinking',
        ];

        return view('backend.admin.meditasi.edit', compact('meditasi', 'kategoris'));
    }

    public function update(Request $request, Meditasi $meditasi)
    {
        $request->validate([
            'kategori' => 'required|string|max:100',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_meditasi' => 'nullable|file|mimes:mp3,wav,m4a,mp4,mov,webm|max:51200',
            'durasi' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
        ], [
            'kategori.required' => 'Kategori meditasi wajib dipilih.',
            'kategori.string' => 'Kategori meditasi harus berupa teks.',
            'kategori.max' => 'Kategori meditasi maksimal 100 karakter.',

            'judul.required' => 'Judul meditasi wajib diisi.',
            'judul.string' => 'Judul meditasi harus berupa teks.',
            'judul.max' => 'Judul meditasi maksimal 255 karakter.',

            'deskripsi.required' => 'Deskripsi meditasi wajib diisi.',
            'deskripsi.string' => 'Deskripsi meditasi harus berupa teks.',

            'file_meditasi.file' => 'File meditasi harus berupa file.',
            'file_meditasi.mimes' => 'File meditasi harus berformat MP3, WAV, M4A, MP4, MOV, atau WEBM.',
            'file_meditasi.max' => 'Ukuran file meditasi maksimal 50 MB.',

            'durasi.required' => 'Durasi meditasi wajib diisi.',
            'durasi.integer' => 'Durasi meditasi harus berupa angka.',
            'durasi.min' => 'Durasi meditasi minimal 1 menit.',

            'status.required' => 'Status meditasi wajib dipilih.',
            'status.in' => 'Status meditasi tidak valid.',
        ]);

        $filePath = $meditasi->audio;

        if ($request->hasFile('file_meditasi')) {
            if ($meditasi->audio && Storage::disk('public')->exists($meditasi->audio)) {
                Storage::disk('public')->delete($meditasi->audio);
            }

            $filePath = $request->file('file_meditasi')->store('meditasi', 'public');
        }

        $meditasi->update([
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'audio' => $filePath,
            'durasi' => $request->durasi,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.meditasi.index')
            ->with('success', 'Data meditasi berhasil diperbarui.');
    }

    public function destroy(Meditasi $meditasi)
    {
        if ($meditasi->audio && Storage::disk('public')->exists($meditasi->audio)) {
            Storage::disk('public')->delete($meditasi->audio);
        }

        $meditasi->delete();

        return redirect()->route('admin.meditasi.index')
            ->with('success', 'Data meditasi berhasil dihapus.');
    }
}