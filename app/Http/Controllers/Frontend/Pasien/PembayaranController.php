<?php

namespace App\Http\Controllers\Frontend\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool) config('services.midtrans.is_3ds');
    }

    public function index()
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun pasien tidak ditemukan.');
        }

        $pembayarans = Pembayaran::with(['konsultasi.psikolog'])
            ->whereHas('konsultasi', function ($query) use ($pasien) {
                $query->where('id_pasien', $pasien->id_pasien);
            })
            ->latest('id_pembayaran')
            ->get();

        return view('frontend.pasien.pembayaran.index', compact('pembayarans'));
    }

    public function create(Request $request)
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun pasien tidak ditemukan.');
        }

        $konsultasi = Konsultasi::with(['psikolog', 'pasien.user', 'pembayaran'])
            ->where('id_pasien', $pasien->id_pasien)
            ->findOrFail($request->konsultasi_id);

        $biayaAdmin = 5000;
        $biayaPsikolog = (float) ($konsultasi->harga ?? $konsultasi->psikolog->biaya_konsultasi ?? 0);
        $totalBayar = $biayaAdmin + $biayaPsikolog;

        $pembayaran = $konsultasi->pembayaran;

        if (!$pembayaran) {
            $pembayaran = Pembayaran::create([
                'id_konsultasi' => $konsultasi->id_konsultasi,
                'id_order' => 'MH-' . time() . '-' . $konsultasi->id_konsultasi,
                'metode_pembayaran' => $this->tentukanMetodePembayaranDefault($konsultasi),
                'biaya_admin' => $biayaAdmin,
                'biaya_psikolog' => $biayaPsikolog,
                'total_pembayaran' => $totalBayar,
                'status_pembayaran' => 'pending',
            ]);
        } elseif (!$pembayaran->metode_pembayaran) {
            $pembayaran->update([
                'metode_pembayaran' => $this->tentukanMetodePembayaranDefault($konsultasi),
            ]);
        }

        if ($pembayaran->status_pembayaran === 'diterima' && !$pembayaran->bukti_pembayaran) {
            return redirect()->route('pasien.konsultasi.metode', $konsultasi->id_konsultasi);
        }

        if ($pembayaran->status_pembayaran === 'diterima' && $pembayaran->bukti_pembayaran) {
            return redirect()
                ->route('pasien.konsultasi.metode', $konsultasi->id_konsultasi)
                ->with('success', 'Pembayaran sudah berhasil. Silakan pilih metode konsultasi.');
        }

        if (!$pembayaran->snap_token) {
            $params = [
                'transaction_details' => [
                    'order_id' => $pembayaran->id_order,
                    'gross_amount' => (int) $pembayaran->total_pembayaran,
                ],
                'customer_details' => [
                    'first_name' => $pasien->nama_lengkap ?? Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => $pasien->no_telepon ?? '',
                ],
                'item_details' => [
                    [
                        'id' => 'PSI-' . $konsultasi->psikolog->id_psikolog,
                        'price' => (int) $pembayaran->biaya_psikolog,
                        'quantity' => 1,
                        'name' => 'Konsultasi ' . ($konsultasi->psikolog->nama_lengkap ?? 'Psikolog'),
                    ],
                    [
                        'id' => 'ADM-001',
                        'price' => (int) $pembayaran->biaya_admin,
                        'quantity' => 1,
                        'name' => 'Biaya Admin MindHaven',
                    ],
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            $pembayaran->update([
                'snap_token' => $snapToken,
            ]);
        }

        return view('frontend.pasien.pembayaran.create', compact('konsultasi', 'pembayaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'konsultasi_id' => 'required|exists:konsultasis,id_konsultasi',
            'metode_pembayaran' => 'nullable|in:transfer_bank,bayar_langsung',
        ], [
            'konsultasi_id.required' => 'Data konsultasi wajib diisi.',
            'konsultasi_id.exists' => 'Data konsultasi tidak valid.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
        ]);

        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun pasien tidak ditemukan.');
        }

        $konsultasi = Konsultasi::with('pembayaran')->where('id_pasien', $pasien->id_pasien)
            ->findOrFail($request->konsultasi_id);

        $metodePembayaran = $request->metode_pembayaran
            ?? $this->tentukanMetodePembayaranDefault($konsultasi);

        if ($konsultasi->pembayaran) {
            $konsultasi->pembayaran->update([
                'metode_pembayaran' => $metodePembayaran,
            ]);
        }

        return redirect()->route('pasien.pembayaran.create', [
            'konsultasi_id' => $request->konsultasi_id,
        ]);
    }

    public function show(Pembayaran $pembayaran)
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien || $pembayaran->konsultasi->id_pasien !== $pasien->id_pasien) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        $pembayaran->load(['konsultasi.psikolog']);

        return view('frontend.pasien.pembayaran.show', compact('pembayaran'));
    }

    public function finish(Request $request, Pembayaran $pembayaran)
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien || $pembayaran->konsultasi->id_pasien !== $pasien->id_pasien) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        $pembayaran->update([
            'status_pembayaran' => 'diterima',
            'metode_pembayaran' => 'transfer_bank',
        ]);

        $pembayaran->konsultasi->update([
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('pasien.konsultasi.metode', $pembayaran->id_konsultasi),
        ]);
    }

    public function uploadBukti(Pembayaran $pembayaran)
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien || $pembayaran->konsultasi->id_pasien !== $pasien->id_pasien) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        $pembayaran->load(['konsultasi.psikolog']);

        return view('frontend.pasien.pembayaran.upload-bukti', compact('pembayaran'));
    }

    public function storeBukti(Request $request, Pembayaran $pembayaran)
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien || $pembayaran->konsultasi->id_pasien !== $pasien->id_pasien) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'metode_pembayaran' => 'nullable|in:transfer_bank,bayar_langsung',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.file' => 'Bukti pembayaran harus berupa file.',
            'bukti_pembayaran.mimes' => 'Format bukti pembayaran harus JPG, JPEG, PNG, atau PDF.',
            'bukti_pembayaran.max' => 'Ukuran bukti pembayaran maksimal 5 MB.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
        ]);

        $buktiPembayaran = $request->file('bukti_pembayaran')
            ->store('bukti_pembayaran', 'public');

        $metodePembayaran = $request->metode_pembayaran
            ?? $pembayaran->metode_pembayaran
            ?? $this->tentukanMetodePembayaranDefault($pembayaran->konsultasi);

        $pembayaran->update([
            'bukti_pembayaran' => $buktiPembayaran,
            'status_pembayaran' => 'diterima',
            'metode_pembayaran' => $metodePembayaran,
        ]);

        $pembayaran->konsultasi->update([
            'status' => 'pending',
        ]);

        return redirect()
            ->route('pasien.konsultasi.metode', $pembayaran->id_konsultasi)
            ->with('success', 'Bukti pembayaran berhasil diupload. Silakan pilih metode konsultasi.');
    }

    public function notification(Request $request)
    {
        $notification = new Notification();

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;

        $pembayaran = Pembayaran::where('id_order', $orderId)->first();

        if (!$pembayaran) {
            return response()->json([
                'message' => 'Pembayaran tidak ditemukan.',
            ], 404);
        }

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $this->setPembayaranBerhasil($pembayaran);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->setPembayaranBerhasil($pembayaran);
        } elseif ($transactionStatus === 'pending') {
            $pembayaran->update([
                'status_pembayaran' => 'pending',
                'metode_pembayaran' => 'transfer_bank',
            ]);
        } elseif (in_array($transactionStatus, ['cancel', 'deny'])) {
            $pembayaran->update([
                'status_pembayaran' => 'gagal',
                'metode_pembayaran' => 'transfer_bank',
            ]);
        } elseif ($transactionStatus === 'expire') {
            $pembayaran->update([
                'status_pembayaran' => 'expired',
                'metode_pembayaran' => 'transfer_bank',
            ]);
        }

        return response()->json([
            'message' => 'Notification processed.',
        ]);
    }

    private function setPembayaranBerhasil(Pembayaran $pembayaran): void
    {
        $pembayaran->update([
            'status_pembayaran' => 'diterima',
            'metode_pembayaran' => 'transfer_bank',
        ]);

        $pembayaran->konsultasi->update([
            'status' => 'pending',
        ]);
    }

    private function tentukanMetodePembayaranDefault(Konsultasi $konsultasi): string
    {
        $metodeKonsultasi = $konsultasi->metode_komunikasi
            ?? $konsultasi->metode_konsultasi
            ?? null;

        if (in_array($metodeKonsultasi, ['offline', 'tatap_muka'], true)) {
            return 'bayar_langsung';
        }

        return 'transfer_bank';
    }
}