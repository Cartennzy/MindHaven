<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with([
            'konsultasi.pasien',
            'konsultasi.psikolog',
        ])
        ->latest('id_pembayaran')
        ->get();

        return view('backend.admin.pembayaran.index', compact('pembayarans'));
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load([
            'konsultasi.pasien',
            'konsultasi.psikolog',
        ]);

        return view('backend.admin.pembayaran.show', compact('pembayaran'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:pending,diterima,gagal,expired',
        ]);

        $adminId = optional(Auth::user()->admin)->id_admin;

        $data = [
            'status_pembayaran' => $request->status_pembayaran,
        ];

        if (Schema::hasColumn('pembayarans', 'id_admin')) {
            $data['id_admin'] = $adminId;
        }

        $pembayaran->update($data);

        if ($request->status_pembayaran === 'diterima') {

            $pembayaran->konsultasi->update([
                'status' => 'diproses',
            ]);

        }

        if ($request->status_pembayaran === 'gagal') {

            $pembayaran->konsultasi->update([
                'status' => 'dibatalkan',
            ]);

        }

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}