<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InstrumenTes;
use App\Models\PertanyaanTes;

class SelfAssessmentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED DATA KATEGORI INSTRUMEN BESERTA RULES SKOR (JSON)
        $instrumens = [
            [
                'nama_tes' => 'Stres',
                'slug' => 'stres',
                'deskripsi' => 'Mengukur tingkat tekanan mental, kelelahan pikiran, dan beban emosional yang Anda rasakan.',
                'rules_skor' => [
                    ['status' => 'Normal', 'min' => 0, 'max' => 7, 'warna' => 'emerald', 'saran' => 'Tingkat stres Anda normal. Pertahankan pola hidup sehat dan manajemen waktu yang baik.'],
                    ['status' => 'Stres Sedang', 'min' => 8, 'max' => 14, 'warna' => 'amber', 'saran' => 'Anda mengalami stres tingkat sedang. Luangkan waktu untuk relaksasi, istirahat cukup, atau cerita ke orang terdekat.'],
                    ['status' => 'Stres Tinggi', 'min' => 15, 'max' => 21, 'warna' => 'rose', 'saran' => 'Tingkat stres Anda cukup tinggi. Sangat disarankan untuk menjadwalkan sesi konseling dengan psikolog di MindHaven.']
                ]
            ],
            [
                'nama_tes' => 'Burnout',
                'slug' => 'burnout',
                'deskripsi' => 'Mengukur tingkat kejenuhan ekstrem, hilangnya motivasi, dan kelelahan fisik-mental akibat pekerjaan atau rutinitas.',
                'rules_skor' => [
                    ['status' => 'Normal', 'min' => 0, 'max' => 7, 'warna' => 'emerald', 'saran' => 'Anda memiliki motivasi kerja/belajar yang sehat. Pertahankan batasan kehidupan pribadi dan pekerjaan (work-life balance).'],
                    ['status' => 'Cenderung Burnout', 'min' => 8, 'max' => 14, 'warna' => 'amber', 'saran' => 'Anda mulai mengalami kejenuhan. Cobalah untuk mengambil jeda istirahat (break), kurangi lembur, dan lakukan hobi Anda.'],
                    ['status' => 'Burnout Parah', 'min' => 15, 'max' => 21, 'warna' => 'rose', 'saran' => 'Anda mengalami burnout kronis. Segera komunikasikan beban kerja Anda dan konsultasikan dengan ahli di MindHaven untuk pemulihan emosional.']
                ]
            ],
            [
                'nama_tes' => 'Kecemasan',
                'slug' => 'kecemasan',
                'deskripsi' => 'Mengukur tingkat rasa cemas berlebih, kekhawatiran masa depan, overthinking, serta gejala panik.',
                'rules_skor' => [
                    ['status' => 'Normal', 'min' => 0, 'max' => 7, 'warna' => 'emerald', 'saran' => 'Tingkat kecemasan Anda berada pada batas wajar manusiawi. Anda mampu mengontrol emosi dengan baik.'],
                    ['status' => 'Kecemasan Sedang', 'min' => 8, 'max' => 14, 'warna' => 'amber', 'saran' => 'Anda mengalami kecemasan tingkat sedang. Latih pernapasan (mindfulness) saat overthinking mulai melanda.'],
                    ['status' => 'Kecemasan Tinggi', 'min' => 15, 'max' => 21, 'warna' => 'rose', 'saran' => 'Anda mengalami kecemasan intens atau gejala panic attack. Dianjurkan untuk berkonsultasi dengan psikolog demi mendapatkan terapi regulasi emosi.']
                ]
            ],
            [
                'nama_tes' => 'Depresi',
                'slug' => 'depresi',
                'deskripsi' => 'Mengukur tingkat kesedihan mendalam yang berkepanjangan, kehilangan minat pada hobi, serta menurunnya energi emosional.',
                'rules_skor' => [
                    ['status' => 'Normal', 'min' => 0, 'max' => 7, 'warna' => 'emerald', 'saran' => 'Suasana hati (mood) Anda stabil dan sehat. Tetap jaga relasi sosial yang positif di sekitar Anda.'],
                    ['status' => 'Depresi Ringan/Sedang', 'min' => 8, 'max' => 14, 'warna' => 'amber', 'saran' => 'Anda menunjukkan gejala kesedihan berkepanjangan. Jangan memendam masalah sendiri, bicarakan dengan psikolog profesional MindHaven.'],
                    ['status' => 'Depresi Berat', 'min' => 15, 'max' => 21, 'warna' => 'rose', 'saran' => 'Anda mengalami penurunan mood yang sangat berat. Jangan ragu untuk segera mencari bantuan profesional psikolog/psikiater untuk pendampingan intensif.']
                ]
            ]
        ];

        // Opsi pilihan jawaban default Skala Likert (0-3)
        $opsiDefault = [
            ['teks' => 'Tidak pernah', 'poin' => 0],
            ['teks' => 'Kadang-hari / Jarang', 'poin' => 1],
            ['teks' => 'Sering terjadi', 'poin' => 2],
            ['teks' => 'Hampir setiap waktu', 'poin' => 3]
        ];

        // 2. SEED DATA PERTANYAAN MASING-MASING KATEGORI
        $pertanyaans = [
            'stres' => [
                'Saya merasa sulit untuk beristirahat dengan tenang.',
                'Saya menyadari bahwa mulut saya sering merasa kering atau tegang.',
                'Saya merasa tidak melihat adanya hal positif di masa depan.',
                'Saya mengalami kesulitan bernapas (misal: napas cepat tanpa alasan fisik).',
                'Saya merasa sulit untuk berinisiatif melakukan sesuatu.',
                'Saya cenderung bereaksi berlebihan terhadap suatu situasi.',
                'Saya merasa gemetar atau goyah (misal: pada kaki atau tangan).'
            ],
            'burnout' => [
                'Saya merasa sangat lelah secara fisik dan emosional setelah selesai beraktivitas harian.',
                'Saya merasa kurang bersemangat dan sinis terhadap pekerjaan/rutinitas saya.',
                'Saya merasa performa atau produktivitas saya menurun drastis akhir-akhir ini.',
                'Saya merasa frustrasi dan ingin mengisolasi diri dari lingkungan kerja/belajar.',
                'Saya merasa beban pikiran tugas sehari-hari menghantui tidur malam saya.',
                'Saya merasa kehilangan rasa pencapaian atas apa yang sudah saya kerjakan.',
                'Saya merasa cepat marah atau tersinggung karena masalah kecil di tempat aktivitas.'
            ],
            'kecemasan' => [
                'Saya merasa cemas dalam situasi yang membuat saya panik atau menjadi pusat perhatian.',
                'Saya merasa khawatir saya akan terpuruk oleh rasa takut yang tidak beralasan.',
                'Saya merasa ketakutan tanpa mengetahui apa penyebab pastinya.',
                'Saya merasa jantung saya berdebar kencang padahal tidak sedang berolahraga.',
                'Saya sering merasa gelisah, tidak tenang, atau overthinking berlebihan.',
                'Saya merasa takut kehilangan kendali atas diri saya sendiri.',
                'Saya merasakan ketegangan otot yang hebat atau tubuh terasa kaku.'
            ],
            'depresi' => [
                'Saya merasa tidak bisa merasakan perasaan senang atau gembira sama sekali.',
                'Saya merasa sedih, murung, dan putus asa sepanjang hari.',
                'Saya merasa kehilangan minat pada semua aktivitas atau hobi yang biasa saya sukai.',
                'Saya merasa diri saya tidak berharga atau tidak berguna lagi.',
                'Saya merasa energi saya habis dan sangat sulit untuk fokus melakukan hal sepele.',
                'Saya merasa tidak memiliki motivasi untuk menjalani hari-hari seperti biasa.',
                'Saya merasa terasingkan atau kesepian meskipun berada di keramaian.'
            ]
        ];

        foreach ($instrumens as $ins) {
            $createdInstrumen = InstrumenTes::create([
                'nama_tes' => $ins['nama_tes'],
                'slug' => $ins['slug'],
                'deskripsi' => $ins['deskripsi'],
                'rules_skor' => $ins['rules_skor'],
            ]);

            $slug = $ins['slug'];
            if (isset($pertanyaans[$slug])) {
                foreach ($pertanyaans[$slug] as $itemPertanyaan) {
                    PertanyaanTes::create([
                        'id_instrumen' => $createdInstrumen->id_instrumen,
                        'teks_pertanyaan' => $itemPertanyaan,
                        'pilihan_opsi' => $opsiDefault,
                    ]);
                }
            }
        }
    }
}