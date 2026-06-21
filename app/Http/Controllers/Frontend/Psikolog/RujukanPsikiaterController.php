<?php

namespace App\Http\Controllers\Frontend\Psikolog;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Psikiater;
use App\Models\RujukanPsikiater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RujukanPsikiaterController extends Controller
{
    public function index()
    {
        $psikolog = Auth::user()->psikolog;

        if (!$psikolog) {
            abort(403, 'Akun ini belum terhubung dengan data psikolog.');
        }

        $rujukans = RujukanPsikiater::with([
                'pasien.user',
                'psikolog.user',
                'konsultasi.detailKonsultasi',
                'psikiater.rumahSakit',
            ])
            ->where('id_psikolog', $psikolog->id_psikolog)
            ->latest('id_rujukan')
            ->get();

        return view('frontend.psikolog.rujukan_psikiater.index', compact('rujukans'));
    }

    public function create(Request $request)
    {
        $psikolog = Auth::user()->psikolog;

        if (!$psikolog) {
            abort(403, 'Akun ini belum terhubung dengan data psikolog.');
        }

        $selectedKonsultasiId = old(
            'konsultasi_id',
            $request->query('konsultasi_id', $request->query('konsultasi'))
        );

        $konsultasis = Konsultasi::with([
                'pasien.user',
                'detailKonsultasi',
                'rujukanPsikiater',
            ])
            ->where('id_psikolog', $psikolog->id_psikolog)
            ->whereDoesntHave('rujukanPsikiater')
            ->whereHas('detailKonsultasi', function ($query) {
                $query->whereNotNull('diagnosis_awal')
                    ->where('diagnosis_awal', '!=', '')
                    ->where('perlu_rujukan', true);
            })
            ->when($selectedKonsultasiId, function ($query) use ($selectedKonsultasiId) {
                $query->orderByRaw('CASE WHEN id_konsultasi = ? THEN 0 ELSE 1 END', [$selectedKonsultasiId]);
            })
            ->latest('id_konsultasi')
            ->get();

        $psikiaters = Psikiater::with('rumahSakit')
            ->where(function ($query) {
                $query->where('status', true)
                    ->orWhere('status', 1)
                    ->orWhere('status', 'aktif');
            })
            ->latest('id_psikiater')
            ->get();

        return view('frontend.psikolog.rujukan_psikiater.create', compact(
            'konsultasis',
            'psikiaters',
            'selectedKonsultasiId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'konsultasi_id' => 'required|exists:konsultasis,id_konsultasi|unique:rujukan_psikiaters,id_konsultasi',
            'psikiater_id' => 'required|exists:psikiaters,id_psikiater',
            'id_rumahsakit' => 'required|exists:rumah_sakits,id_rumahsakit',
            'diagnosa_awal' => 'nullable|string',
            'alasan_rujukan' => 'required|string',
            'catatan_rujukan' => 'nullable|string',
            'tanggal_rujukan' => 'nullable|date',
        ], [
            'konsultasi_id.required' => 'Konsultasi pasien wajib dipilih.',
            'konsultasi_id.exists' => 'Data konsultasi tidak ditemukan.',
            'konsultasi_id.unique' => 'Konsultasi ini sudah memiliki surat rujukan.',

            'psikiater_id.required' => 'Psikiater tujuan wajib dipilih.',
            'psikiater_id.exists' => 'Data psikiater tidak ditemukan.',

            'id_rumahsakit.required' => 'Rumah sakit wajib dipilih.',
            'id_rumahsakit.exists' => 'Data rumah sakit tidak ditemukan.',

            'diagnosa_awal.string' => 'Diagnosa awal harus berupa teks.',

            'alasan_rujukan.required' => 'Alasan rujukan wajib diisi.',
            'alasan_rujukan.string' => 'Alasan rujukan harus berupa teks.',

            'catatan_rujukan.string' => 'Catatan rujukan harus berupa teks.',
            'tanggal_rujukan.date' => 'Tanggal rujukan tidak valid.',
        ]);

        $psikolog = Auth::user()->psikolog;

        if (!$psikolog) {
            abort(403, 'Akun ini belum terhubung dengan data psikolog.');
        }

        $konsultasi = Konsultasi::with([
                'pasien.user',
                'detailKonsultasi',
            ])
            ->where('id_psikolog', $psikolog->id_psikolog)
            ->where('id_konsultasi', $request->konsultasi_id)
            ->firstOrFail();

        $detail = $konsultasi->detailKonsultasi;

        if (!$detail || empty($detail->diagnosis_awal)) {
            return back()
                ->withInput()
                ->with('error', 'Konsultasi ini belum memiliki diagnosis awal. Isi hasil konsultasi terlebih dahulu.');
        }

        if (!$detail->perlu_rujukan) {
            return back()
                ->withInput()
                ->with('error', 'Konsultasi ini belum ditandai membutuhkan surat rujukan psikiater.');
        }

        $psikiater = Psikiater::with('rumahSakit')
            ->where('id_psikiater', $request->psikiater_id)
            ->firstOrFail();

        if ((int) $psikiater->id_rumahsakit !== (int) $request->id_rumahsakit) {
            return back()
                ->withInput()
                ->withErrors([
                    'id_rumahsakit' => 'Rumah sakit tidak sesuai dengan psikiater yang dipilih.',
                ]);
        }

        $data = [
            'id_konsultasi' => $konsultasi->id_konsultasi,
            'id_pasien' => $konsultasi->id_pasien,
            'id_psikolog' => $psikolog->id_psikolog,
            'id_psikiater' => $psikiater->id_psikiater,
            'diagnosa_awal' => $request->diagnosa_awal ?: $detail->diagnosis_awal,
            'alasan_rujukan' => $request->alasan_rujukan,
            'catatan_psikolog' => $request->catatan_rujukan,
            'status' => 'menunggu',
            'tanggal_rujukan' => $request->tanggal_rujukan ?: now()->toDateString(),
        ];

        if (Schema::hasColumn('rujukan_psikiaters', 'id_rumahsakit')) {
            $data['id_rumahsakit'] = $request->id_rumahsakit;
        }

        if (Schema::hasColumn('rujukan_psikiaters', 'catatan_rujukan')) {
            $data['catatan_rujukan'] = $request->catatan_rujukan;
        }

        if (Schema::hasColumn('rujukan_psikiaters', 'status_rujukan')) {
            $data['status_rujukan'] = 'menunggu';
        }

        if (Schema::hasColumn('rujukan_psikiaters', 'nomor_rujukan')) {
            $data['nomor_rujukan'] = $this->generateNomorRujukan();
        }

        RujukanPsikiater::create($data);

        return redirect()
            ->route('psikolog.rujukan-psikiater.index')
            ->with('success', 'Surat rujukan psikiater berhasil dibuat.');
    }

    public function show(RujukanPsikiater $rujukanPsikiater)
    {
        $this->authorizeAccess($rujukanPsikiater);

        $rujukanPsikiater->load([
            'pasien.user',
            'psikolog.user',
            'konsultasi.detailKonsultasi',
            'psikiater.rumahSakit',
        ]);

        return view('frontend.psikolog.rujukan_psikiater.show', compact('rujukanPsikiater'));
    }

    public function update(Request $request, RujukanPsikiater $rujukanPsikiater)
    {
        $this->authorizeAccess($rujukanPsikiater);

        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,dibatalkan',
        ], [
            'status.required' => 'Status rujukan wajib dipilih.',
            'status.in' => 'Status rujukan tidak valid.',
        ]);

        $data = [];

        if (Schema::hasColumn('rujukan_psikiaters', 'status')) {
            $data['status'] = $request->status;
        }

        if (Schema::hasColumn('rujukan_psikiaters', 'status_rujukan')) {
            $data['status_rujukan'] = $request->status;
        }

        $rujukanPsikiater->update($data);

        return back()->with('success', 'Status rujukan berhasil diperbarui.');
    }

    private function authorizeAccess(RujukanPsikiater $rujukanPsikiater): void
    {
        $psikolog = Auth::user()->psikolog;

        if (!$psikolog || $rujukanPsikiater->id_psikolog !== $psikolog->id_psikolog) {
            abort(403, 'Anda tidak memiliki akses ke surat rujukan ini.');
        }
    }

    private function generateNomorRujukan(): string
    {
        do {
            $nomor = 'RJ-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (RujukanPsikiater::where('nomor_rujukan', $nomor)->exists());

        return $nomor;
    }
}