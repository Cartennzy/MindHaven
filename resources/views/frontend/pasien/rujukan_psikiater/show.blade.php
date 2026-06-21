@extends('frontend.layouts.app')

@section('title', 'Detail Surat Rujukan')
@section('page_title', 'Detail Surat Rujukan')
@section('page_subtitle', 'Surat rujukan psikiater dari hasil konsultasi Anda.')

@section('content')

@php
    $pasien = $rujukanPsikiater->pasien;
    $userPasien = $pasien->user ?? null;

    $psikolog = $rujukanPsikiater->psikolog;
    $userPsikolog = $psikolog->user ?? null;

    $psikiater = $rujukanPsikiater->psikiater;

    $rumahSakit = $rujukanPsikiater->rumahSakit
        ?? $rujukanPsikiater->psikiater?->rumahSakit
        ?? null;

    $konsultasi = $rujukanPsikiater->konsultasi;
    $detail = $konsultasi->detailKonsultasi ?? null;

    $logoMindHaven = asset('assets/images/logo_polos.png');

    $namaPasien = $pasien->nama_lengkap ?? $userPasien->name ?? '-';
    $emailPasien = $userPasien->email ?? '-';

    $namaPsikolog = $psikolog->nama_lengkap ?? $userPsikolog->name ?? '-';

    $umurPasien = '-';
    if (!empty($pasien->tanggal_lahir)) {
        try {
            $umurPasien = \Carbon\Carbon::parse($pasien->tanggal_lahir)->age . ' tahun';
        } catch (\Exception $e) {
            $umurPasien = '-';
        }
    }

    $tanggalRujukan = !empty($rujukanPsikiater->tanggal_rujukan)
        ? \Carbon\Carbon::parse($rujukanPsikiater->tanggal_rujukan)->translatedFormat('d F Y')
        : '-';

    $tanggalKonsultasi = !empty($konsultasi->tanggal_konsultasi)
        ? \Carbon\Carbon::parse($konsultasi->tanggal_konsultasi)->format('d-m-Y')
        : '-';

    $jamKonsultasi = !empty($konsultasi->jam_konsultasi)
        ? \Carbon\Carbon::parse($konsultasi->jam_konsultasi)->format('H:i')
        : '-';
@endphp

<div class="space-y-6">

    @if(session('success'))
        <div class="rounded-3xl border border-green-100 bg-green-50 px-6 py-5 text-sm font-bold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-3xl border border-red-100 bg-red-50 px-6 py-5 text-sm font-bold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-[34px] bg-white p-6 shadow-soft md:p-8">
        <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-black text-slate-800">
                    Detail Surat Rujukan Psikiater
                </h2>

                <p class="mt-2 text-sm font-semibold text-slate-500">
                    Nomor Surat: {{ $rujukanPsikiater->nomor_rujukan ?? '-' }}
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="button"
                        onclick="printSuratRujukan()"
                        class="inline-flex items-center justify-center rounded-2xl bg-[#01588E] px-7 py-4 text-sm font-black text-white shadow-lg transition hover:-translate-y-1 hover:bg-[#01446e]">
                    <i class="fas fa-print mr-3"></i>
                    Cetak Surat Resmi
                </button>

                <a href="{{ route('pasien.rujukan-psikiater.index') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-slate-100 px-7 py-4 text-sm font-black text-slate-700 transition hover:-translate-y-1 hover:bg-slate-200">
                    <i class="fas fa-arrow-left mr-3"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- TAMPILAN CONTAINER UTAMA SURAT RUJUKAN ELEKTRONIK -->
    <div class="rounded-[34px] bg-slate-100 p-4 md:p-8 overflow-x-auto flex justify-center">
        <div id="surat-rujukan-print" class="surat">
            
            <!-- WATERMARK LATAR BELAKANG UNTUK KEASLIAN DOKUMEN KLINIS -->
            <div class="watermark-bg">MINDHAVEN</div>

            <div class="kop-surat">
                <div class="kop-container">
                    <div class="kop-logo">
                        <img src="{{ $logoMindHaven }}" alt="MindHaven" class="logo-img">
                    </div>
                    <div class="kop-text">
                        <h1>MINDHAVEN CLINICAL CENTER</h1>
                        <p class="kop-subtitle">Layanan Konsultasi Integrasi & Solusi Kesehatan Mental Elektronik</p>
                        <p class="kop-contact">Metropolitan Tambun, Bekasi, Jawa Barat | Email: mindhaven@gmail.com | Website: www.mindhaven.id</p>
                    </div>
                </div>
            </div>

            <div class="kop-line"></div>

            <div class="judul-surat">
                <h2>SURAT RUJUKAN PSIKIATER</h2>
                <p>Nomor: {{ $rujukanPsikiater->nomor_rujukan ?? '-' }}</p>
            </div>

            <p class="paragraf">
                Yang bertanda tangan di bawah ini, psikolog pemeriksa pada platform kesehatan mental terintegrasi MindHaven, menerangkan bahwa setelah dilakukan proses penilaian psikologis berkala, pasien berikut memerlukan pemeriksaan, tindakan medis, serta penanganan psikofarmaka lanjutan oleh dokter spesialis kedokteran jiwa (psikiater).
            </p>

            <div class="subjudul">Identitas Pasien</div>

            <table class="tabel-data">
                <tr>
                    <td class="label">Nama Pasien</td>
                    <td class="colon">:</td>
                    <td><strong>{{ $namaPasien }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Email Pengguna</td>
                    <td class="colon">:</td>
                    <td>{{ $emailPasien }}</td>
                </tr>
                <tr>
                    <td class="label">No. Telepon / WA</td>
                    <td class="colon">:</td>
                    <td>{{ $pasien->no_telepon ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="colon">:</td>
                    <td>{{ $pasien->jenis_kelamin ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Lahir / Umur</td>
                    <td class="colon">:</td>
                    <td>{{ $pasien->tanggal_lahir ?? '-' }} / {{ $umurPasien }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Domisili</td>
                    <td class="colon">:</td>
                    <td>{{ $pasien->alamat ?? '-' }}</td>
                </tr>
            </table>

            <div class="subjudul">Informasi Konsultasi Asal</div>

            <table class="tabel-data">
                <tr>
                    <td class="label">Topik Konseling</td>
                    <td class="colon">:</td>
                    <td>{{ $konsultasi->topik_konsultasi ?? $konsultasi->topik_konseling ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Metode Komunikasi</td>
                    <td class="colon">:</td>
                    <td>{{ $konsultasi->metode_konsultasi ?? $konsultasi->metode_komunikasi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal / Jam Sesi</td>
                    <td class="colon">:</td>
                    <td>{{ $tanggalKonsultasi }} / {{ $jamKonsultasi }} WIB</td>
                </tr>
                <tr>
                    <td class="label">Keluhan Utama</td>
                    <td class="colon">:</td>
                    <td>{{ $konsultasi->keluhan ?? $detail->keluhan_utama ?? '-' }}</td>
                </tr>
            </table>

            <div class="subjudul">Tujuan Faskes Rujukan</div>

            <table class="tabel-data">
                <tr>
                    <td class="label">Nama Dokter Psikiater</td>
                    <td class="colon">:</td>
                    <td><strong>{{ $psikiater->nama_lengkap ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Spesialisasi Klinis</td>
                    <td class="colon">:</td>
                    <td>{{ $psikiater->spesialisasi ?? 'Spesialis Kedokteran Jiwa (Sp.KJ)' }}</td>
                </tr>
                <tr>
                    <td class="label">Rumah Sakit / Faskes</td>
                    <td class="colon">:</td>
                    <td>{{ $rumahSakit->nama_rumahsakit ?? $rumahSakit->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Faskes</td>
                    <td class="colon">:</td>
                    <td>{{ $rumahSakit->alamat ?? $rumahSakit->alamat_rumahsakit ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Konten Telepon Faskes</td>
                    <td class="colon">:</td>
                    <td>{{ $rumahSakit->telepon ?? $rumahSakit->no_telepon ?? '-' }}</td>
                </tr>
            </table>

            <div class="subjudul">Dasar Analisis Klinis</div>

            <table class="tabel-data">
                <tr>
                    <td class="label">Diagnosis Indikasi Awal</td>
                    <td class="colon">:</td>
                    <td><span style="color:#b91c1c; font-weight:bold;">{{ $rujukanPsikiater->diagnosa_awal ?? $detail->diagnosis_awal ?? '-' }}</span></td>
                </tr>
                <tr>
                    <td class="label">Alasan Utama Rujukan</td>
                    <td class="colon">:</td>
                    <td>{{ $rujukanPsikiater->alasan_rujukan ?? $detail->rencana_penanganan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Catatan Rekomendasi</td>
                    <td class="colon">:</td>
                    <td>{{ $rujukanPsikiater->catatan_rujukan ?? $rujukanPsikiater->catatan_psikolog ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Hasil Observasi Lapangan</td>
                    <td class="colon">:</td>
                    <td>{{ $detail->hasil_observasi ?? $detail->laporan_asesmen_psikologis ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Rencana Intervensi</td>
                    <td class="colon">:</td>
                    <td>{{ $detail->rencana_penanganan ?? '-' }}</td>
                </tr>
            </table>

            <p class="paragraf penutup">
                Demikian surat rujukan ini diterbitkan secara elektronik oleh MindHaven demi kelancaran proses pemulihan kondisi kejiwaan pasien. Mohon pemeriksaan serta penanganan klinis lebih lanjut sebagaimana mestinya.
            </p>

            <div class="ttd-wrapper">
                <!-- VALIDASI BARCODE VALIDATOR SISTEM INFORMASI (KIRI) -->
                <div class="barcode-container">
                    <svg class="barcode-img" viewBox="0 0 100 30" xmlns="http://www.w3.org/2000/svg">
                        <line x1="5" y1="2" x2="5" y2="25" stroke="black" stroke-width="2"/>
                        <line x1="10" y1="2" x2="10" y2="25" stroke="black" stroke-width="1"/>
                        <line x1="15" y1="2" x2="15" y2="25" stroke="black" stroke-width="3"/>
                        <line x1="22" y1="2" x2="22" y2="25" stroke="black" stroke-width="1"/>
                        <line x1="28" y1="2" x2="28" y2="25" stroke="black" stroke-width="2"/>
                        <line x1="35" y1="2" x2="35" y2="25" stroke="black" stroke-width="4"/>
                        <line x1="42" y1="2" x2="42" y2="25" stroke="black" stroke-width="1"/>
                        <line x1="48" y1="2" x2="48" y2="25" stroke="black" stroke-width="2"/>
                        <line x1="55" y1="2" x2="55" y2="25" stroke="black" stroke-width="3"/>
                        <line x1="62" y1="2" x2="62" y2="25" stroke="black" stroke-width="1"/>
                        <line x1="70" y1="2" x2="70" y2="25" stroke="black" stroke-width="2"/>
                        <line x1="78" y1="2" x2="78" y2="25" stroke="black" stroke-width="4"/>
                        <line x1="85" y1="2" x2="85" y2="25" stroke="black" stroke-width="1"/>
                        <line x1="92" y1="2" x2="92" y2="25" stroke="black" stroke-width="2"/>
                        <text x="50" y="29" font-size="3" text-anchor="middle" font-family="monospace">MH-E-VERIFIED-{{ $rujukanPsikiater->id_rujukan ?? $rujukanPsikiater->id ?? '0' }}</text>
                    </svg>
                    <p class="barcode-text">Sistem Sertifikasi Rujukan Elektronik Valid</p>
                </div>

                <!-- TANDA TANGAN PSIKOLOG (KANAN) -->
                <div class="ttd">
                    <p>Bekasi, {{ $tanggalRujukan }}</p>
                    <p>Psikolog Pemeriksa,</p>

                    <div class="ruang-ttd">
                        <div class="digital-stamp">E-SIGNED BY MINDHAVEN</div>
                    </div>

                    <p class="nama-ttd">{{ $namaPsikolog }}</p>
                    <p style="margin:0; font-size:9.5px; color:#64748b; font-weight:700;">Surat Lisensi Praktik Terverifikasi</p>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- INTERFACE COMPONENT STYLES -->
<style>
    .surat {
        position: relative;
        width: 210mm;
        min-width: 210mm;
        min-height: 297mm;
        margin: 0 auto;
        background: #ffffff;
        color: #111827;
        padding: 40px 50px;
        border-radius: 24px;
        border: 1px solid #e5e7eb;
        font-family: 'Times New Roman', Times, serif;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* WATERMARK PREMIUM */
    .watermark-bg {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-35deg);
        font-size: 85px;
        font-weight: 900;
        color: rgba(1, 88, 142, 0.04);
        letter-spacing: 0.2em;
        z-index: 0;
        pointer-events: none;
        user-select: none;
    }

    .surat * {
        position: relative;
        z-index: 1;
    }

    .kop-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        text-align: left;
    }

    .kop-logo {
        flex-shrink: 0;
    }

    .logo-img {
        width: 85px;
        height: 85px;
        object-fit: contain;
    }

    .kop-text h1 {
        margin: 0;
        color: #01588E;
        font-size: 24px;
        font-weight: bold;
        line-height: 1.2;
        font-family: Arial, Helvetica, sans-serif;
    }

    .kop-subtitle {
        margin: 4px 0 0;
        color: #1e293b;
        font-size: 12px;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
    }

    .kop-contact {
        margin: 4px 0 0;
        color: #475569;
        font-size: 10px;
        font-weight: 500;
        font-family: Arial, Helvetica, sans-serif;
    }

    .kop-line {
        margin: 15px 0 20px;
        border-top: 3px solid #01588E;
        border-bottom: 1px solid #01588E;
        height: 5px;
    }

    .judul-surat {
        text-align: center;
        margin-bottom: 25px;
    }

    .judul-surat h2 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
        text-decoration: underline;
        letter-spacing: 0.05em;
    }

    .judul-surat p {
        margin: 6px 0 0;
        font-size: 12px;
        font-weight: bold;
        color: #334155;
    }

    .paragraf {
        margin: 0 0 15px;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.6;
        text-align: justify;
        text-indent: 45px;
    }

    .subjudul {
        margin: 15px 0 10px;
        padding: 5px 12px;
        background: #f0f7fc;
        border-left: 4px solid #01588E;
        font-size: 11px;
        font-weight: bold;
        color: #01588E;
        text-transform: uppercase;
        font-family: Arial, Helvetica, sans-serif;
        letter-spacing: 0.05em;
    }

    .tabel-data {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    .tabel-data td {
        padding: 4px 6px;
        vertical-align: top;
        font-size: 13px;
        line-height: 1.5;
    }

    .tabel-data .label {
        width: 180px;
        color: #334155;
        font-weight: bold;
    }

    .tabel-data .colon {
        width: 15px;
        text-align: center;
        font-weight: bold;
    }

    .penutup {
        margin-top: 15px;
        text-indent: 45px;
    }

    .ttd-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 35px;
        padding: 0 10px;
    }

    .barcode-container {
        display: flex;
        flex-col;
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }

    .barcode-img {
        width: 140px;
        height: 45px;
    }

    .barcode-text {
        margin: 0;
        font-size: 9px;
        color: #64748b;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: bold;
    }

    .ttd {
        text-align: center;
        font-size: 13px;
        width: 220px;
    }

    .ttd p {
        margin: 0 0 4px;
    }

    .ruang-ttd {
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .digital-stamp {
        border: 2px dashed rgba(16, 185, 129, 0.4);
        color: rgba(16, 185, 129, 0.7);
        font-weight: bold;
        font-size: 9px;
        padding: 6px 12px;
        transform: rotate(-5deg);
        font-family: Arial, Helvetica, sans-serif;
        border-radius: 6px;
        text-transform: uppercase;
        background: rgba(16, 185, 129, 0.02);
    }

    .nama-ttd {
        font-weight: bold !important;
        text-decoration: underline;
    }
</style>

<!-- SINKRONISASI JAVASCRIPT PRINT (LAYOUT SINKRONISASI) -->
<script>
    function printSuratRujukan() {
        const suratElement = document.getElementById('surat-rujukan-print');
        const suratHtml = suratElement.outerHTML;

        // Buka popup window printing baru
        const printWindow = window.open('', '_blank', 'width=950,height=1000');

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Surat Rujukan Resmi Psikiater - MindHaven</title>
                <style>
                    @page {
                        size: A4 portrait;
                        margin: 15mm 12mm;
                    }

                    * {
                        box-sizing: border-box;
                    }

                    body {
                        margin: 0;
                        padding: 0;
                        background: #ffffff;
                        color: #111827;
                        font-family: 'Times New Roman', Times, serif;
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }

                    .surat {
                        position: relative;
                        width: 100%;
                        margin: 0;
                        padding: 0;
                        background: #ffffff;
                        border: none;
                        box-shadow: none;
                    }

                    .watermark-bg {
                        position: absolute;
                        top: 45%;
                        left: 50%;
                        transform: translate(-50%, -50%) rotate(-35deg);
                        font-size: 72px;
                        font-weight: 900;
                        color: rgba(1, 88, 142, 0.03);
                        letter-spacing: 0.2em;
                        z-index: 0;
                    }

                    .kop-container {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 20px;
                    }

                    .logo-img {
                        width: 75px;
                        height: 75px;
                        object-fit: contain;
                    }

                    .kop-text h1 {
                        margin: 0;
                        color: #01588E;
                        font-size: 22px;
                        font-weight: bold;
                        font-family: Arial, Helvetica, sans-serif;
                    }

                    .kop-subtitle {
                        margin: 3px 0 0;
                        color: #1e293b;
                        font-size: 11px;
                        font-weight: bold;
                        font-family: Arial, Helvetica, sans-serif;
                    }

                    .kop-contact {
                        margin: 3px 0 0;
                        color: #475569;
                        font-size: 9px;
                        font-family: Arial, Helvetica, sans-serif;
                    }

                    .kop-line {
                        margin: 12px 0 18px;
                        border-top: 3px solid #01588E;
                        border-bottom: 1px solid #01588E;
                        height: 5px;
                    }

                    .judul-surat {
                        text-align: center;
                        margin-bottom: 20px;
                    }

                    .judul-surat h2 {
                        margin: 0;
                        font-size: 16px;
                        font-weight: bold;
                        text-decoration: underline;
                    }

                    .judul-surat p {
                        margin: 4px 0 0;
                        font-size: 11px;
                        font-weight: bold;
                    }

                    .paragraf {
                        margin: 0 0 12px;
                        font-size: 12.5px;
                        line-height: 1.55;
                        text-align: justify;
                        text-indent: 40px;
                    }

                    .subjudul {
                        margin: 14px 0 8px;
                        padding: 4px 10px;
                        background: #f0f7fc;
                        border-left: 4px solid #01588E;
                        font-size: 10.5px;
                        font-weight: bold;
                        color: #01588E;
                        text-transform: uppercase;
                        font-family: Arial, Helvetica, sans-serif;
                    }

                    .tabel-data {
                        width: 100%;
                        margin-bottom: 12px;
                    }

                    .tabel-data td {
                        padding: 3.5px 5px;
                        font-size: 12.5px;
                    }

                    .tabel-data .label {
                        width: 170px;
                        font-weight: bold;
                    }

                    .tabel-data .colon {
                        width: 12px;
                        text-align: center;
                    }

                    .ttd-wrapper {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-end;
                        margin-top: 30px;
                    }

                    .barcode-container {
                        display: flex;
                        flex-direction: column;
                        align-items: flex-start;
                        gap: 3px;
                    }

                    .barcode-img {
                        width: 130px;
                        height: 40px;
                    }

                    .barcode-text {
                        margin: 0;
                        font-size: 8.5px;
                        color: #64748b;
                        font-family: Arial, Helvetica, sans-serif;
                    }

                    .ttd {
                        text-align: center;
                        font-size: 12.5px;
                        width: 210px;
                    }

                    .digital-stamp {
                        border: 2px dashed rgba(16, 185, 129, 0.4);
                        color: rgba(16, 185, 129, 0.7);
                        font-weight: bold;
                        font-size: 8.5px;
                        padding: 5px 10px;
                        transform: rotate(-5deg);
                        font-family: Arial, Helvetica, sans-serif;
                        border-radius: 5px;
                    }

                    .nama-ttd {
                        font-weight: bold;
                        text-decoration: underline;
                    }
                </style>
            </head>
            <body>
                ${suratHtml}
            </body>
            </html>
        `);

        printWindow.document.close();

        // Trigger fungsi print bawaan engine browser setelah halaman termuat penuh
        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    }
</script>

@endsection