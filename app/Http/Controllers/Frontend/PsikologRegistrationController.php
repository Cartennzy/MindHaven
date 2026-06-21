<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Psikolog;
use App\Models\User;
use App\Models\JadwalPsikolog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PsikologRegistrationController extends Controller
{
    public function index()
    {
        return view('frontend.auth.daftar-psikolog');
    }

    public function success()
    {
        return view('frontend.auth.daftar-psikolog-success');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email|unique:psikologs,email',
            'no_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string|max:1000',
            'spesialisasi' => 'required|string|max:255',
            'pengalaman' => 'required|integer|min:0',
            'biaya_konsultasi' => 'required|numeric|min:0',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'pendidikan' => 'nullable|string|max:1000',
            'str_psikolog' => 'nullable|string|max:255',
            'sip_psikolog' => 'nullable|string|max:255',
            'jadwal_praktik' => 'nullable|string|max:255',

            'hari_praktik' => 'required|array',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ];

        if (Schema::hasColumn('psikologs', 'dokumen_verifikasi')) {
            $rules['dokumen_verifikasi'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        } else {
            $rules['dokumen_verifikasi'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        if (Schema::hasColumn('psikologs', 'dokumen_pendidikan')) {
            $rules['dokumen_pendidikan'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        if (Schema::hasColumn('psikologs', 'dokumen_str_psikolog')) {
            $rules['dokumen_str_psikolog'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        if (Schema::hasColumn('psikologs', 'dokumen_sip_psikolog')) {
            $rules['dokumen_sip_psikolog'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        if (Schema::hasColumn('psikologs', 'bio')) {
            $rules['bio'] = 'nullable|string|max:1000';
        }

        if (Schema::hasColumn('psikologs', 'metode_konsultasi')) {
            $rules['metode_konsultasi'] = 'nullable|string|max:255';
        }

        if (Schema::hasColumn('psikologs', 'total_pasien')) {
            $rules['total_pasien'] = 'nullable|string|max:255';
        }

        if (Schema::hasColumn('psikologs', 'total_konsultasi')) {
            $rules['total_konsultasi'] = 'nullable|string|max:255';
        }

        $messages = [
            'name.required' => 'Nama akun wajib diisi.',
            'name.string' => 'Nama akun harus berupa teks.',
            'name.max' => 'Nama akun maksimal 255 karakter.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',

            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.string' => 'Nomor telepon harus berupa teks.',
            'no_telepon.max' => 'Nomor telepon maksimal 20 karakter.',

            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat maksimal 1000 karakter.',

            'spesialisasi.required' => 'Spesialisasi wajib diisi.',
            'spesialisasi.string' => 'Spesialisasi harus berupa teks.',
            'spesialisasi.max' => 'Spesialisasi maksimal 255 karakter.',

            'pengalaman.required' => 'Pengalaman wajib diisi.',
            'pengalaman.integer' => 'Pengalaman harus berupa angka.',
            'pengalaman.min' => 'Pengalaman tidak boleh kurang dari 0.',

            'biaya_konsultasi.required' => 'Biaya konsultasi wajib diisi.',
            'biaya_konsultasi.numeric' => 'Biaya konsultasi harus berupa angka.',
            'biaya_konsultasi.min' => 'Biaya konsultasi tidak boleh kurang dari 0.',

            'foto_profil.image' => 'Foto profil harus berupa gambar.',
            'foto_profil.mimes' => 'Foto profil harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto_profil.max' => 'Ukuran foto profil maksimal 2 MB.',

            'dokumen_verifikasi.required' => 'Dokumen verifikasi wajib diunggah.',
            'dokumen_verifikasi.file' => 'Dokumen verifikasi harus berupa file.',
            'dokumen_verifikasi.mimes' => 'Dokumen verifikasi harus berformat PDF, JPG, JPEG, atau PNG.',
            'dokumen_verifikasi.max' => 'Ukuran dokumen verifikasi maksimal 5 MB.',

            'dokumen_pendidikan.file' => 'Dokumen pendidikan harus berupa file.',
            'dokumen_pendidikan.mimes' => 'Dokumen pendidikan harus berformat PDF, JPG, JPEG, atau PNG.',
            'dokumen_pendidikan.max' => 'Ukuran dokumen pendidikan maksimal 5 MB.',

            'dokumen_str_psikolog.file' => 'Dokumen STR psikolog harus berupa file.',
            'dokumen_str_psikolog.mimes' => 'Dokumen STR psikolog harus berformat PDF, JPG, JPEG, atau PNG.',
            'dokumen_str_psikolog.max' => 'Ukuran dokumen STR psikolog maksimal 5 MB.',

            'dokumen_sip_psikolog.file' => 'Dokumen SIP psikolog harus berupa file.',
            'dokumen_sip_psikolog.mimes' => 'Dokumen SIP psikolog harus berformat PDF, JPG, JPEG, atau PNG.',
            'dokumen_sip_psikolog.max' => 'Ukuran dokumen SIP psikolog maksimal 5 MB.',

            'pendidikan.string' => 'Pendidikan harus berupa teks.',
            'pendidikan.max' => 'Pendidikan maksimal 1000 karakter.',

            'str_psikolog.string' => 'Nomor STR psikolog harus berupa teks.',
            'str_psikolog.max' => 'Nomor STR psikolog maksimal 255 karakter.',

            'sip_psikolog.string' => 'Nomor SIP psikolog harus berupa teks.',
            'sip_psikolog.max' => 'Nomor SIP psikolog maksimal 255 karakter.',

            'jadwal_praktik.string' => 'Jadwal praktik harus berupa teks.',
            'jadwal_praktik.max' => 'Jadwal praktik maksimal 255 karakter.',

            'bio.string' => 'Bio harus berupa teks.',
            'bio.max' => 'Bio maksimal 1000 karakter.',

            'metode_konsultasi.string' => 'Metode konsultasi harus berupa teks.',
            'metode_konsultasi.max' => 'Metode konsultasi maksimal 255 karakter.',
        ];

        $validated = $request->validate($rules, $messages);

        DB::transaction(function () use ($request, $validated) {
            $fotoProfil = null;
            $dokumenVerifikasi = null;
            $dokumenPendidikan = null;
            $dokumenStrPsikolog = null;
            $dokumenSipPsikolog = null;

            if ($request->hasFile('foto_profil')) {
                $fotoProfil = $request->file('foto_profil')->store('foto_profil/psikolog', 'public');
            }

            if ($request->hasFile('dokumen_verifikasi')) {
                $dokumenVerifikasi = $request->file('dokumen_verifikasi')->store('dokumen_verifikasi/psikolog', 'public');
            }

            if ($request->hasFile('dokumen_pendidikan')) {
                $dokumenPendidikan = $request->file('dokumen_pendidikan')->store('dokumen_pendidikan/psikolog', 'public');
            }

            if ($request->hasFile('dokumen_str_psikolog')) {
                $dokumenStrPsikolog = $request->file('dokumen_str_psikolog')->store('dokumen_str_psikolog/psikolog', 'public');
            }

            if ($request->hasFile('dokumen_sip_psikolog')) {
                $dokumenSipPsikolog = $request->file('dokumen_sip_psikolog')->store('dokumen_sip_psikolog/psikolog', 'public');
            }

            $plainPassword = Str::random(32);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($plainPassword),
                'role' => 'psikolog',
            ]);

            $dataPsikolog = [
                'user_id' => $user->id,
                'nama_lengkap' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => Hash::make($plainPassword),
                'no_telepon' => $validated['no_telepon'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'],
                'foto_profil' => $fotoProfil,
                'spesialisasi' => $validated['spesialisasi'],
                'pengalaman' => $validated['pengalaman'],
                'biaya_konsultasi' => $validated['biaya_konsultasi'],
                'pendidikan' => $validated['pendidikan'] ?? null,
                'str_psikolog' => $validated['str_psikolog'] ?? null,
                'sip_psikolog' => $validated['sip_psikolog'] ?? null,
                'jadwal_praktik' => $validated['jadwal_praktik'] ?? null,
                'status_verifikasi' => 'pending',
            ];

            if (Schema::hasColumn('psikologs', 'bio')) {
                $dataPsikolog['bio'] = $validated['bio'] ?? null;
            }

            if (Schema::hasColumn('psikologs', 'metode_konsultasi')) {
                $dataPsikolog['metode_konsultasi'] = $validated['metode_konsultasi'] ?? null;
            }

            if (Schema::hasColumn('psikologs', 'total_pasien')) {
                $dataPsikolog['total_pasien'] = $validated['total_pasien'] ?? null;
            }

            if (Schema::hasColumn('psikologs', 'total_konsultasi')) {
                $dataPsikolog['total_konsultasi'] = $validated['total_konsultasi'] ?? null;
            }

            if (Schema::hasColumn('psikologs', 'dokumen_verifikasi')) {
                $dataPsikolog['dokumen_verifikasi'] = $dokumenVerifikasi;
            }

            if (Schema::hasColumn('psikologs', 'dokumen_pendidikan')) {
                $dataPsikolog['dokumen_pendidikan'] = $dokumenPendidikan;
            }

            if (Schema::hasColumn('psikologs', 'dokumen_str_psikolog')) {
                $dataPsikolog['dokumen_str_psikolog'] = $dokumenStrPsikolog;
            }

            if (Schema::hasColumn('psikologs', 'dokumen_sip_psikolog')) {
                $dataPsikolog['dokumen_sip_psikolog'] = $dokumenSipPsikolog;
            }

            if (Schema::hasColumn('psikologs', 'catatan_verifikasi')) {
                $dataPsikolog['catatan_verifikasi'] = null;
            }

            if (Schema::hasColumn('psikologs', 'is_active')) {
                $dataPsikolog['is_active'] = false;
            }

            $psikolog = Psikolog::create($dataPsikolog);

            foreach ($request->hari_praktik as $hari) {
                JadwalPsikolog::create([
                    'id_psikolog' => $psikolog->id_psikolog,
                    'hari' => $hari,
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    ]);
            }

            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                $dataNotifikasi = [
                    'user_id' => $admin->id,
                    'judul' => 'Pengajuan Kerja Sama Psikolog Baru',
                    'pesan' => $validated['nama_lengkap'] . ' mengajukan kerja sama sebagai psikolog MindHaven dan menunggu verifikasi admin.',
                    'tipe' => 'psikolog_baru',
                    'is_read' => false,
                ];

                if (Schema::hasColumn('notifications', 'psikolog_id')) {
                    $dataNotifikasi['psikolog_id'] = $psikolog->id_psikolog;
                }

                if (Schema::hasColumn('notifications', 'id_konsultasi')) {
                    $dataNotifikasi['id_konsultasi'] = null;
                }

                if (Schema::hasColumn('notifications', 'id_rujukan')) {
                    $dataNotifikasi['id_rujukan'] = null;
                }

                Notifikasi::create($dataNotifikasi);
            }
        });

        return redirect()
            ->route('psikolog.register.success')
            ->with('success', 'Pengajuan kerja sama psikolog berhasil dikirim. Password login akan dikirim melalui email setelah akun disetujui admin.');
    }
}