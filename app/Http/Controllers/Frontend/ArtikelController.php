<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Artikel;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::where('status', true)
            ->latest('id_artikel')
            ->paginate(9);

        return view('frontend.artikel.index', compact('artikels'));
    }

    public function show(Artikel $artikel)
    {
        if (!$artikel->status) {
            abort(404);
        }

        $relatedArtikels = Artikel::where('status', true)
            ->where('id_artikel', '!=', $artikel->id_artikel)
            ->where('kategori', $artikel->kategori)
            ->latest('id_artikel')
            ->limit(4)
            ->get();

        return view('frontend.artikel.show', compact('artikel', 'relatedArtikels'));
    }
}