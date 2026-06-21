<?php

namespace App\Http\Controllers\Frontend\Psikolog;

use App\Http\Controllers\Controller;
use App\Models\Psikiater;
use Illuminate\Http\Request;

class PsikiaterController extends Controller
{
    public function index(Request $request)
    {
        $query = Psikiater::with('rumahSakit')
            ->where('status', true);

        if ($request->filled('spesialisasi')) {
            $query->where('spesialisasi', 'like', '%' . $request->spesialisasi . '%');
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->keyword . '%')
                    ->orWhere('spesialisasi', 'like', '%' . $request->keyword . '%')
                    ->orWhereHas('rumahSakit', function ($rumahSakit) use ($request) {
                        $rumahSakit->where('nama_rumahsakit', 'like', '%' . $request->keyword . '%');
                    });
            });
        }

        $psikiaters = $query->latest('id_psikiater')->get();

        return view('frontend.psikolog.psikiater.index', compact('psikiaters'));
    }

    public function show(Psikiater $psikiater)
    {
        $psikiater->load('rumahSakit');

        return view('frontend.psikolog.psikiater.show', compact('psikiater'));
    }
}