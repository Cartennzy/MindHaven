<?php

namespace App\Http\Controllers\Frontend\Psikolog;

use App\Http\Controllers\Controller;
use App\Models\RumahSakit;
use Illuminate\Http\Request;

class RumahSakitController extends Controller
{
    public function index(Request $request)
    {
        $query = RumahSakit::with('psikiaters');

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_rumahsakit', 'like', '%' . $request->keyword . '%')
                    ->orWhere('alamat', 'like', '%' . $request->keyword . '%')
                    ->orWhere('no_telepon', 'like', '%' . $request->keyword . '%');
            });
        }

        $rumahSakits = $query->latest('id_rumahsakit')->get();

        return view('frontend.psikolog.rumah_sakit.index', compact('rumahSakits'));
    }

    public function show(RumahSakit $rumahSakit)
    {
        $rumahSakit->load('psikiaters');

        return view('frontend.psikolog.rumah_sakit.show', compact('rumahSakit'));
    }
}