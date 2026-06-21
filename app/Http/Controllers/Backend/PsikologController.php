<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\PsikologApprovedMail;
use App\Models\Psikolog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PsikologController extends Controller
{
    public function index()
    {
        $psikologs = Psikolog::with('user')
            ->latest('id_psikolog')
            ->get();

        return view('backend.admin.psikolog.index', compact('psikologs'));
    }

    public function create()
    {
        return view('backend.admin.psikolog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:psikologs,email',

            'no_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',

            'spesialisasi' => 'required|string|max:255',
            'pengalaman' => 'required|integer|min:0',
            'biaya_konsultasi' => 'required|numeric|min:0',

            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'dokumen_verifikasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_pendidikan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_str_psikolog' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_sip_psikolog' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

            'bio' => 'nullable|string|max:1000',
            'pendidikan' => 'nullable|string|max:1000',
            'str_psikolog' => 'nullable|string|max:255',
            'sip_psikolog' => 'nullable|string|max:255',
            'metode_konsultasi' => 'nullable|string|max:255',
            'jadwal_praktik' => 'nullable|string|max:255',
            'total_pasien' => 'nullable|string|max:255',
            'total_konsultasi' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {

            $fotoProfil = null;
            $dokumenVerifikasi = null;
            $dokumenPendidikan = null;
            $dokumenStrPsikolog = null;
            $dokumenSipPsikolog = null;

            if ($request->hasFile('foto_profil')) {
                $fotoProfil = $request->file('foto_profil')
                    ->store('foto_profil/psikolog', 'public');
            }

            if ($request->hasFile('dokumen_verifikasi')) {
                $dokumenVerifikasi = $request->file('dokumen_verifikasi')
                    ->store('dokumen_verifikasi/psikolog', 'public');
            }

            if ($request->hasFile('dokumen_pendidikan')) {
                $dokumenPendidikan = $request->file('dokumen_pendidikan')
                    ->store('dokumen_pendidikan/psikolog', 'public');
            }

            if ($request->hasFile('dokumen_str_psikolog')) {
                $dokumenStrPsikolog = $request->file('dokumen_str_psikolog')
                    ->store('dokumen_str_psikolog/psikolog', 'public');
            }

            if ($request->hasFile('dokumen_sip_psikolog')) {
                $dokumenSipPsikolog = $request->file('dokumen_sip_psikolog')
                    ->store('dokumen_sip_psikolog/psikolog', 'public');
            }

            $plainPassword = 'psikolog123';

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($plainPassword),
                'role' => 'psikolog',
            ]);

            $data = [
                'user_id' => $user->id,

                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($plainPassword),

                'no_telepon' => $request->no_telepon,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,

                'foto_profil' => $fotoProfil,

                'spesialisasi' => $request->spesialisasi,
                'pengalaman' => $request->pengalaman,
                'biaya_konsultasi' => $request->biaya_konsultasi,

                'pendidikan' => $request->pendidikan,
                'str_psikolog' => $request->str_psikolog,
                'sip_psikolog' => $request->sip_psikolog,
                'jadwal_praktik' => $request->jadwal_praktik,

                'status_verifikasi' => 'verified',
            ];

            if (Schema::hasColumn('psikologs', 'bio')) {
                $data['bio'] = $request->bio;
            }

            if (Schema::hasColumn('psikologs', 'metode_konsultasi')) {
                $data['metode_konsultasi'] = $request->metode_konsultasi;
            }

            if (Schema::hasColumn('psikologs', 'total_pasien')) {
                $data['total_pasien'] = $request->total_pasien;
            }

            if (Schema::hasColumn('psikologs', 'total_konsultasi')) {
                $data['total_konsultasi'] = $request->total_konsultasi;
            }

            if (Schema::hasColumn('psikologs', 'dokumen_verifikasi')) {
                $data['dokumen_verifikasi'] = $dokumenVerifikasi;
            }

            if (Schema::hasColumn('psikologs', 'dokumen_pendidikan')) {
                $data['dokumen_pendidikan'] = $dokumenPendidikan;
            }

            if (Schema::hasColumn('psikologs', 'dokumen_str_psikolog')) {
                $data['dokumen_str_psikolog'] = $dokumenStrPsikolog;
            }

            if (Schema::hasColumn('psikologs', 'dokumen_sip_psikolog')) {
                $data['dokumen_sip_psikolog'] = $dokumenSipPsikolog;
            }

            if (Schema::hasColumn('psikologs', 'catatan_verifikasi')) {
                $data['catatan_verifikasi'] = null;
            }

            if (Schema::hasColumn('psikologs', 'is_active')) {
                $data['is_active'] = true;
            }

            $psikolog = Psikolog::create($data);

            Mail::to($user->email)
                ->send(new PsikologApprovedMail(
                    $psikolog,
                    $plainPassword
                ));
        });

        return redirect()
            ->route('admin.psikolog.index')
            ->with(
                'success',
                'Psikolog berhasil ditambahkan dan password login telah dikirim ke email.'
            );
    }

    public function show(Psikolog $psikolog)
    {
        $psikolog->load([
            'user',
            'jadwalPraktiks'
        ]);

        return view(
            'backend.admin.psikolog.show',
            compact('psikolog')
        );
    }

    public function edit(Psikolog $psikolog)
    {
        $psikolog->load('user');

        return view(
            'backend.admin.psikolog.edit',
            compact('psikolog')
        );
    }

    public function update(Request $request, Psikolog $psikolog)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $psikolog->user_id . '|unique:psikologs,email,' . $psikolog->id_psikolog . ',id_psikolog',
            'password' => 'nullable|min:6',

            'no_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',

            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'spesialisasi' => 'required|string|max:255',
            'pengalaman' => 'required|integer|min:0',
            'biaya_konsultasi' => 'required|numeric|min:0',

            'dokumen_verifikasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_pendidikan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_str_psikolog' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_sip_psikolog' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

            'bio' => 'nullable|string|max:1000',
            'pendidikan' => 'nullable|string|max:1000',
            'str_psikolog' => 'nullable|string|max:255',
            'sip_psikolog' => 'nullable|string|max:255',
            'metode_konsultasi' => 'nullable|string|max:255',
            'jadwal_praktik' => 'nullable|string|max:255',
            'total_pasien' => 'nullable|string|max:255',
            'total_konsultasi' => 'nullable|string|max:255',
        ]);

        $psikolog->load('user');

        if ($psikolog->user) {
            $psikolog->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $psikolog->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        }

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,

            'no_telepon' => $request->no_telepon,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,

            'spesialisasi' => $request->spesialisasi,
            'pengalaman' => $request->pengalaman,
            'biaya_konsultasi' => $request->biaya_konsultasi,

            'pendidikan' => $request->pendidikan,
            'str_psikolog' => $request->str_psikolog,
            'sip_psikolog' => $request->sip_psikolog,
            'jadwal_praktik' => $request->jadwal_praktik,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if (Schema::hasColumn('psikologs', 'bio')) {
            $data['bio'] = $request->bio;
        }

        if (Schema::hasColumn('psikologs', 'metode_konsultasi')) {
            $data['metode_konsultasi'] = $request->metode_konsultasi;
        }

        if (Schema::hasColumn('psikologs', 'total_pasien')) {
            $data['total_pasien'] = $request->total_pasien;
        }

        if (Schema::hasColumn('psikologs', 'total_konsultasi')) {
            $data['total_konsultasi'] = $request->total_konsultasi;
        }

        if ($request->hasFile('foto_profil')) {
            if ($psikolog->foto_profil && Storage::disk('public')->exists($psikolog->foto_profil)) {
                Storage::disk('public')->delete($psikolog->foto_profil);
            }

            $data['foto_profil'] = $request->file('foto_profil')
                ->store('foto_profil/psikolog', 'public');
        }

        if ($request->hasFile('dokumen_verifikasi') && Schema::hasColumn('psikologs', 'dokumen_verifikasi')) {
            if ($psikolog->dokumen_verifikasi && Storage::disk('public')->exists($psikolog->dokumen_verifikasi)) {
                Storage::disk('public')->delete($psikolog->dokumen_verifikasi);
            }

            $data['dokumen_verifikasi'] = $request->file('dokumen_verifikasi')
                ->store('dokumen_verifikasi/psikolog', 'public');
        }

        if ($request->hasFile('dokumen_pendidikan') && Schema::hasColumn('psikologs', 'dokumen_pendidikan')) {
            if ($psikolog->dokumen_pendidikan && Storage::disk('public')->exists($psikolog->dokumen_pendidikan)) {
                Storage::disk('public')->delete($psikolog->dokumen_pendidikan);
            }

            $data['dokumen_pendidikan'] = $request->file('dokumen_pendidikan')
                ->store('dokumen_pendidikan/psikolog', 'public');
        }

        if ($request->hasFile('dokumen_str_psikolog') && Schema::hasColumn('psikologs', 'dokumen_str_psikolog')) {
            if ($psikolog->dokumen_str_psikolog && Storage::disk('public')->exists($psikolog->dokumen_str_psikolog)) {
                Storage::disk('public')->delete($psikolog->dokumen_str_psikolog);
            }

            $data['dokumen_str_psikolog'] = $request->file('dokumen_str_psikolog')
                ->store('dokumen_str_psikolog/psikolog', 'public');
        }

        if ($request->hasFile('dokumen_sip_psikolog') && Schema::hasColumn('psikologs', 'dokumen_sip_psikolog')) {
            if ($psikolog->dokumen_sip_psikolog && Storage::disk('public')->exists($psikolog->dokumen_sip_psikolog)) {
                Storage::disk('public')->delete($psikolog->dokumen_sip_psikolog);
            }

            $data['dokumen_sip_psikolog'] = $request->file('dokumen_sip_psikolog')
                ->store('dokumen_sip_psikolog/psikolog', 'public');
        }

        $psikolog->update($data);

        return redirect()
            ->route('admin.psikolog.index')
            ->with(
                'success',
                'Data psikolog berhasil diperbarui.'
            );
    }

    public function verifikasi(Request $request, Psikolog $psikolog)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:pending,verified,rejected',
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $psikolog) {

            $psikolog->load('user');

            if ($request->status_verifikasi === 'verified') {

                $plainPassword = 'psikolog123';

                if ($psikolog->user) {
                    $psikolog->user->update([
                        'password' => Hash::make($plainPassword),
                        'role' => 'psikolog',
                    ]);
                }

                $data = [
                    'password' => Hash::make($plainPassword),
                    'status_verifikasi' => 'verified',
                ];

                if (Schema::hasColumn('psikologs', 'catatan_verifikasi')) {
                    $data['catatan_verifikasi'] = $request->catatan_verifikasi;
                }

                if (Schema::hasColumn('psikologs', 'is_active')) {
                    $data['is_active'] = true;
                }

                $psikolog->update($data);

                if ($psikolog->user) {
                    Mail::to($psikolog->user->email)
                        ->send(new PsikologApprovedMail(
                            $psikolog,
                            $plainPassword
                        ));
                }
            } else {
                $data = [
                    'status_verifikasi' => $request->status_verifikasi,
                ];

                if (Schema::hasColumn('psikologs', 'catatan_verifikasi')) {
                    $data['catatan_verifikasi'] = $request->catatan_verifikasi;
                }

                if (Schema::hasColumn('psikologs', 'is_active')) {
                    $data['is_active'] = false;
                }

                $psikolog->update($data);
            }
        });

        return redirect()
            ->route(
                'admin.psikolog.show',
                $psikolog->id_psikolog
            )
            ->with(
                'success',
                'Status psikolog berhasil diperbarui dan email login berhasil dikirim.'
            );
    }

    public function destroy(Psikolog $psikolog)
    {
        $psikolog->load('user');

        if ($psikolog->foto_profil && Storage::disk('public')->exists($psikolog->foto_profil)) {
            Storage::disk('public')->delete($psikolog->foto_profil);
        }

        if (
            Schema::hasColumn('psikologs', 'dokumen_verifikasi') &&
            $psikolog->dokumen_verifikasi &&
            Storage::disk('public')->exists($psikolog->dokumen_verifikasi)
        ) {
            Storage::disk('public')->delete($psikolog->dokumen_verifikasi);
        }

        if (
            Schema::hasColumn('psikologs', 'dokumen_pendidikan') &&
            $psikolog->dokumen_pendidikan &&
            Storage::disk('public')->exists($psikolog->dokumen_pendidikan)
        ) {
            Storage::disk('public')->delete($psikolog->dokumen_pendidikan);
        }

        if (
            Schema::hasColumn('psikologs', 'dokumen_str_psikolog') &&
            $psikolog->dokumen_str_psikolog &&
            Storage::disk('public')->exists($psikolog->dokumen_str_psikolog)
        ) {
            Storage::disk('public')->delete($psikolog->dokumen_str_psikolog);
        }

        if (
            Schema::hasColumn('psikologs', 'dokumen_sip_psikolog') &&
            $psikolog->dokumen_sip_psikolog &&
            Storage::disk('public')->exists($psikolog->dokumen_sip_psikolog)
        ) {
            Storage::disk('public')->delete($psikolog->dokumen_sip_psikolog);
        }

        if ($psikolog->user) {
            $psikolog->user->delete();
        } else {
            $psikolog->delete();
        }

        return redirect()
            ->route('admin.psikolog.index')
            ->with(
                'success',
                'Data psikolog berhasil dihapus.'
            );
    }
}