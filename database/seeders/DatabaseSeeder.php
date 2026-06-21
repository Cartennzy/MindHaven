<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Pasien;
use App\Models\Psikiater;
use App\Models\Psikolog;
use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | USER ADMIN
            |--------------------------------------------------------------------------
            |
            */

            User::updateOrCreate(
                ['email' => 'admin@mindhaven.com'],
                [
                    'name' => 'Admin MindHaven',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                ]
            );

            Admin::updateOrCreate(
                ['email' => 'admin@mindhaven.com'],
                [
                    'nama' => 'Admin MindHaven',
                    'password' => Hash::make('password'),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | USER PASIEN
            |--------------------------------------------------------------------------
            |
            */

            $pasienUser = User::updateOrCreate(
                ['email' => 'pasien@mindhaven.com'],
                [
                    'name' => 'Pasien MindHaven',
                    'password' => Hash::make('password'),
                    'role' => 'pasien',
                ]
            );

            Pasien::updateOrCreate(
                ['user_id' => $pasienUser->id],
                [
                    'nama_lengkap' => 'Najwan Muyassar',
                    'no_telepon' => '081111222333',
                    'tanggal_lahir' => '2004-01-16',
                    'jenis_kelamin' => 'laki-laki',
                    'alamat' => 'Bekasi, Jawa Barat',
                    'foto_profil' => null,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | DATA RUMAH SAKIT
            |--------------------------------------------------------------------------
            |
            */

            RumahSakit::updateOrCreate(
                ['nama_rumahsakit' => 'RS Mitra Keluarga Bekasi'],
                [
                    'alamat' => 'Jl. Ahmad Yani, Bekasi',
                    'no_telepon' => '02188960400',
                ]
            );

            $idRumahSakit = DB::table('rumah_sakits')
                ->where('nama_rumahsakit', 'RS Mitra Keluarga Bekasi')
                ->value('id_rumahsakit');

            if (!$idRumahSakit) {
                throw new \Exception('Seeder gagal: id_rumahsakit tidak ditemukan. Cek migration tabel rumah_sakits.');
            }

            /*
            |--------------------------------------------------------------------------
            | DATA PSIKIATER
            |--------------------------------------------------------------------------
            |
            */

            Psikiater::updateOrCreate(
                ['email' => 'budi.psikiater@example.com'],
                [
                    'id_rumahsakit' => $idRumahSakit,
                    'nama_lengkap' => 'Dr. Budi Santoso, Sp.KJ',
                    'spesialisasi' => 'Psikiatri Dewasa dan Depresi Berat',
                    'no_telepon' => '081234009988',
                    'jadwal_praktik' => 'Senin - Jumat, 09.00 - 15.00',
                    'str_psikiater' => 'STR-PSIKIATER-2026-001',
                    'status' => true,
                    'pengalaman' => 8,
                    'foto_profil' => null,
                    'alamat_praktik' => 'RS Mitra Keluarga Bekasi',
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | DATA DUMMY PSIKOLOG PER TOPIK
            |--------------------------------------------------------------------------
            |
            */

            $dataPsikolog = [
                'Stres' => [
                    [
                        'nama' => 'Dr. Amanda Putri, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'stres stress management burnout work stress manajemen stres',
                        'pengalaman' => 6,
                        'biaya' => 75000,
                    ],
                    [
                        'nama' => 'Rizky Aditya, S.Psi., M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'stres overthinking manajemen emosi burnout',
                        'pengalaman' => 5,
                        'biaya' => 65000,
                    ],
                    [
                        'nama' => 'Nadia Larasati, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'stres stres akademik self esteem burnout',
                        'pengalaman' => 4,
                        'biaya' => 60000,
                    ],
                    [
                        'nama' => 'Fajar Pratama, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'stres burnout stres kerja work life balance',
                        'pengalaman' => 7,
                        'biaya' => 80000,
                    ],
                    [
                        'nama' => 'Dewi Anggraini, S.Psi., M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'stres manajemen stres emosi adaptasi diri',
                        'pengalaman' => 5,
                        'biaya' => 70000,
                    ],
                ],

                'Gangguan Kecemasan' => [
                    [
                        'nama' => 'Dr. Rania Safitri, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'gangguan_kecemasan anxiety panic attack overthinking kecemasan',
                        'pengalaman' => 8,
                        'biaya' => 90000,
                    ],
                    [
                        'nama' => 'Bagas Mahendra, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'gangguan_kecemasan kecemasan sosial overthinking anxiety',
                        'pengalaman' => 5,
                        'biaya' => 70000,
                    ],
                    [
                        'nama' => 'Sinta Aulia, S.Psi., M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'gangguan_kecemasan anxiety self confidence kecemasan remaja',
                        'pengalaman' => 4,
                        'biaya' => 65000,
                    ],
                    [
                        'nama' => 'Yoga Firmansyah, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'gangguan_kecemasan relaksasi panic attack anxiety',
                        'pengalaman' => 6,
                        'biaya' => 75000,
                    ],
                    [
                        'nama' => 'Maya Permatasari, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'gangguan_kecemasan anxiety emotional regulation overthinking',
                        'pengalaman' => 7,
                        'biaya' => 85000,
                    ],
                ],

                'Depresi' => [
                    [
                        'nama' => 'Dr. Sarah Amelia, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'depresi mood disorder emotional support gangguan mood',
                        'pengalaman' => 8,
                        'biaya' => 95000,
                    ],
                    [
                        'nama' => 'Andika Saputra, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'depresi self esteem motivasi diri kehilangan semangat',
                        'pengalaman' => 6,
                        'biaya' => 80000,
                    ],
                    [
                        'nama' => 'Citra Maharani, S.Psi., M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'depresi depresi remaja kesehatan mental remaja',
                        'pengalaman' => 5,
                        'biaya' => 75000,
                    ],
                    [
                        'nama' => 'Reza Maulana, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'depresi burnout kehilangan semangat stress',
                        'pengalaman' => 7,
                        'biaya' => 85000,
                    ],
                    [
                        'nama' => 'Intan Wulandari, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'depresi konseling emosional self acceptance',
                        'pengalaman' => 4,
                        'biaya' => 70000,
                    ],
                ],

                'Keluarga & Hubungan' => [
                    [
                        'nama' => 'Dr. Melati Kirana, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'keluarga_hubungan relationship family counseling keluarga hubungan',
                        'pengalaman' => 9,
                        'biaya' => 100000,
                    ],
                    [
                        'nama' => 'Ardiansyah Putra, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'keluarga_hubungan konflik keluarga relationship hubungan',
                        'pengalaman' => 6,
                        'biaya' => 80000,
                    ],
                    [
                        'nama' => 'Laras Ayuningtyas, S.Psi., M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'keluarga_hubungan relationship komunikasi pasangan keluarga',
                        'pengalaman' => 5,
                        'biaya' => 75000,
                    ],
                    [
                        'nama' => 'Dimas Ramadhan, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'keluarga_hubungan konseling keluarga parenting hubungan',
                        'pengalaman' => 7,
                        'biaya' => 85000,
                    ],
                    [
                        'nama' => 'Putri Anindya, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'keluarga_hubungan relationship broken home keluarga',
                        'pengalaman' => 6,
                        'biaya' => 80000,
                    ],
                ],

                'Trauma' => [
                    [
                        'nama' => 'Dr. Farah Nabila, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'trauma trauma healing inner child emotional healing',
                        'pengalaman' => 8,
                        'biaya' => 95000,
                    ],
                    [
                        'nama' => 'Hendra Wijaya, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'trauma pengalaman masa lalu emotional healing',
                        'pengalaman' => 6,
                        'biaya' => 85000,
                    ],
                    [
                        'nama' => 'Ayu Maharani, S.Psi., M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'trauma trauma remaja emotional healing',
                        'pengalaman' => 5,
                        'biaya' => 75000,
                    ],
                    [
                        'nama' => 'Iqbal Hakim, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'trauma stress recovery trauma healing',
                        'pengalaman' => 7,
                        'biaya' => 90000,
                    ],
                    [
                        'nama' => 'Niken Prameswari, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'trauma self acceptance inner child',
                        'pengalaman' => 6,
                        'biaya' => 80000,
                    ],
                ],

                'Gangguan Mood' => [
                    [
                        'nama' => 'Dr. Vina Oktaviani, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'gangguan_mood mood disorder emotional regulation gangguan mood',
                        'pengalaman' => 8,
                        'biaya' => 95000,
                    ],
                    [
                        'nama' => 'Raka Nugraha, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'gangguan_mood emosi tidak stabil mood swing',
                        'pengalaman' => 6,
                        'biaya' => 80000,
                    ],
                    [
                        'nama' => 'Tiara Lestari, S.Psi., M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'gangguan_mood mood swing self control',
                        'pengalaman' => 5,
                        'biaya' => 75000,
                    ],
                    [
                        'nama' => 'Bima Santoso, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'gangguan_mood regulasi emosi mood disorder',
                        'pengalaman' => 7,
                        'biaya' => 85000,
                    ],
                    [
                        'nama' => 'Anisa Rahmawati, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'gangguan_mood konseling emosional mood disorder',
                        'pengalaman' => 4,
                        'biaya' => 70000,
                    ],
                ],

                'Lainnya' => [
                    [
                        'nama' => 'Dr. Kevin Alexander, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'lainnya kesehatan mental umum self growth',
                        'pengalaman' => 8,
                        'biaya' => 90000,
                    ],
                    [
                        'nama' => 'Riska Maulida, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'lainnya self esteem pengembangan diri',
                        'pengalaman' => 5,
                        'biaya' => 70000,
                    ],
                    [
                        'nama' => 'Taufik Hidayat, S.Psi., M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'lainnya konseling umum motivasi diri',
                        'pengalaman' => 6,
                        'biaya' => 75000,
                    ],
                    [
                        'nama' => 'Nabila Putri, M.Psi., Psikolog',
                        'gender' => 'perempuan',
                        'spesialisasi' => 'lainnya remaja self improvement',
                        'pengalaman' => 4,
                        'biaya' => 65000,
                    ],
                    [
                        'nama' => 'Gilang Ramadhan, M.Psi., Psikolog',
                        'gender' => 'laki-laki',
                        'spesialisasi' => 'lainnya konseling umum adaptasi diri',
                        'pengalaman' => 5,
                        'biaya' => 70000,
                    ],
                ],
            ];

            foreach ($dataPsikolog as $topik => $psikologs) {
                foreach ($psikologs as $index => $item) {

                    $email = Str::slug($item['nama'] . '-' . $topik . '-' . $index) . '@mindhaven.test';

                    $user = User::updateOrCreate(
                        ['email' => $email],
                        [
                            'name' => $item['nama'],
                            'password' => Hash::make('psikolog123'),
                            'role' => 'psikolog',
                        ]
                    );

                    Psikolog::updateOrCreate(
                        ['email' => $email],
                        [
                            'user_id' => $user->id,
                            'nama_lengkap' => $item['nama'],
                            'password' => Hash::make('psikolog123'),
                            'no_telepon' => '0812' . random_int(10000000, 99999999),
                            'tanggal_lahir' => now()->subYears(random_int(28, 45))->format('Y-m-d'),
                            'jenis_kelamin' => $item['gender'],
                            'alamat' => 'Klinik MindHaven, Bekasi, Jawa Barat.',
                            'foto_profil' => null,

                            'spesialisasi' => $item['spesialisasi'],
                            'pengalaman' => $item['pengalaman'],
                            'biaya_konsultasi' => $item['biaya'],

                            'pendidikan' => 'S1 Psikologi — Universitas Indonesia. S2 Profesi Psikolog — Universitas Padjadjaran.',
                            'dokumen_pendidikan' => null,

                            'str_psikolog' => 'STR-PSI-2026-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
                            'dokumen_str_psikolog' => null,

                            'sip_psikolog' => 'SIP-PSI-2026-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
                            'dokumen_sip_psikolog' => null,

                            'jadwal_praktik' => 'Senin - Jumat, 09.00 - 17.00',

                            'bio' => 'Berpengalaman membantu pasien dengan topik ' . $topik . ' melalui pendekatan konsultasi suportif dan profesional. Rating: 4.9/5. Review: Psikolognya nyaman diajak cerita. Total pasien: 85 pasien. Total konsultasi: 120+ konsultasi selesai.',
                            'metode_konsultasi' => 'online,offline',

                            'dokumen_verifikasi' => null,
                            'catatan_verifikasi' => 'Data dummy terverifikasi untuk testing sistem.',

                            'is_active' => true,
                            'status_verifikasi' => 'verified',
                        ]
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | MENYINKRONKAN CALL SEEDER FITUR SELF-ASSESSMENT
            |--------------------------------------------------------------------------
            |
            */
            $this->call(SelfAssessmentSeeder::class);

        });
    }
}