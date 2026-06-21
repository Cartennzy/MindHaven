<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::with('admin')
            ->latest('id_artikel')
            ->get();

        return view('backend.admin.artikel.index', compact('artikels'));
    }

    public function create()
    {
        $kategoris = [
            'Kesehatan Mental',
            'Kecemasan',
            'Stres',
            'Depresi',
            'Hubungan Sosial',
            'Self Development',
            'Tidur',
            'Produktivitas',
        ];

        return view('backend.admin.artikel.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:100',
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:3048',
            'penulis' => 'nullable|string|max:255',
            'sumber_artikel' => 'nullable|string|max:255',
            'tanggal_publish' => 'nullable|date',
            'status' => 'required|boolean',
        ], [
            'kategori.required' => 'Kategori artikel wajib dipilih.',
            'kategori.string' => 'Kategori artikel harus berupa teks.',
            'kategori.max' => 'Kategori artikel maksimal 100 karakter.',

            'judul.required' => 'Judul artikel wajib diisi.',
            'judul.string' => 'Judul artikel harus berupa teks.',
            'judul.max' => 'Judul artikel maksimal 255 karakter.',

            'konten.required' => 'Konten artikel wajib diisi.',
            'konten.string' => 'Konten artikel harus berupa teks.',

            'gambar.image' => 'Gambar artikel harus berupa gambar.',
            'gambar.mimes' => 'Gambar artikel harus berformat JPG, JPEG, atau PNG.',
            'gambar.max' => 'Ukuran gambar artikel maksimal 3 MB.',

            'penulis.string' => 'Penulis harus berupa teks.',
            'penulis.max' => 'Penulis maksimal 255 karakter.',

            'sumber_artikel.string' => 'Sumber artikel harus berupa teks.',
            'sumber_artikel.max' => 'Sumber artikel maksimal 255 karakter.',

            'tanggal_publish.date' => 'Tanggal publish tidak valid.',

            'status.required' => 'Status artikel wajib dipilih.',
            'status.boolean' => 'Status artikel tidak valid.',
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('artikel', 'public');
        }

        $admin = Admin::where('email', Auth::user()->email)->first();

        if (!$admin) {
            return back()
                ->with('error', 'Data admin tidak ditemukan. Pastikan email admin sama dengan email akun login.')
                ->withInput();
        }

        Artikel::create([
            'id_admin' => $admin->id_admin,
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'gambar' => $gambar,
            'penulis' => $request->penulis ?: 'Tim MindHaven',
            'sumber_artikel' => $request->sumber_artikel,
            'tanggal_publish' => $request->tanggal_publish,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function show(Artikel $artikel)
    {
        $artikel->load('admin');

        return view('backend.admin.artikel.show', compact('artikel'));
    }

    public function edit(Artikel $artikel)
    {
        $kategoris = [
            'Kesehatan Mental',
            'Kecemasan',
            'Stres',
            'Depresi',
            'Hubungan Sosial',
            'Self Development',
            'Tidur',
            'Produktivitas',
        ];

        return view('backend.admin.artikel.edit', compact('artikel', 'kategoris'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $request->validate([
            'kategori' => 'required|string|max:100',
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'penulis' => 'nullable|string|max:255',
            'sumber_artikel' => 'nullable|string|max:255',
            'tanggal_publish' => 'nullable|date',
            'status' => 'required|boolean',
        ], [
            'kategori.required' => 'Kategori artikel wajib dipilih.',
            'kategori.string' => 'Kategori artikel harus berupa teks.',
            'kategori.max' => 'Kategori artikel maksimal 100 karakter.',

            'judul.required' => 'Judul artikel wajib diisi.',
            'judul.string' => 'Judul artikel harus berupa teks.',
            'judul.max' => 'Judul artikel maksimal 255 karakter.',

            'konten.required' => 'Konten artikel wajib diisi.',
            'konten.string' => 'Konten artikel harus berupa teks.',

            'gambar.image' => 'Gambar artikel harus berupa gambar.',
            'gambar.mimes' => 'Gambar artikel harus berformat JPG, JPEG, atau PNG.',
            'gambar.max' => 'Ukuran gambar artikel maksimal 2 MB.',

            'penulis.string' => 'Penulis harus berupa teks.',
            'penulis.max' => 'Penulis maksimal 255 karakter.',

            'sumber_artikel.string' => 'Sumber artikel harus berupa teks.',
            'sumber_artikel.max' => 'Sumber artikel maksimal 255 karakter.',

            'tanggal_publish.date' => 'Tanggal publish tidak valid.',

            'status.required' => 'Status artikel wajib dipilih.',
            'status.boolean' => 'Status artikel tidak valid.',
        ]);

        $data = [
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'penulis' => $request->penulis ?: 'Tim MindHaven',
            'sumber_artikel' => $request->sumber_artikel,
            'tanggal_publish' => $request->tanggal_publish,
            'status' => $request->status,
        ];

        if ($request->hasFile('gambar')) {
            if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
                Storage::disk('public')->delete($artikel->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('artikel', 'public');
        }

        $artikel->update($data);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Artikel $artikel)
    {
        if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
            Storage::disk('public')->delete($artikel->gambar);
        }

        $artikel->delete();

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}