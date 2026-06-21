<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RujukanPsikiater;
use Illuminate\Http\Request;

class RujukanPsikiaterController extends Controller
{
    public function index()
    {
        $rujukans = RujukanPsikiater::with([
            'pasien',
            'psikolog',
            'psikiater',
            'konsultasi'
        ])
            ->latest('id_rujukan')
            ->get();

        return view('backend.admin.rujukan_psikiater.index', compact('rujukans'));
    }

    public function show(RujukanPsikiater $rujukanPsikiater)
    {
        $rujukanPsikiater->load([
            'pasien',
            'psikolog',
            'psikiater',
            'konsultasi'
        ]);

        return view('backend.admin.rujukan_psikiater.show', compact('rujukanPsikiater'));
    }

    public function update(Request $request, RujukanPsikiater $rujukanPsikiater)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai',
        ]);

        $rujukanPsikiater->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.rujukan-psikiater.index')
            ->with('success', 'Status rujukan berhasil diperbarui.');
    }

    public function destroy(RujukanPsikiater $rujukanPsikiater)
    {
        $rujukanPsikiater->delete();

        return redirect()->route('admin.rujukan-psikiater.index')
            ->with('success', 'Data rujukan berhasil dihapus.');
    }
}