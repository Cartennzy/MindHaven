<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>MindHaven - Resume Medis Psikometrik</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; color: #333; line-height: 1.5; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #01588E; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { color: #01588E; margin: 0; font-size: 20px; font-weight: bold; }
        .header p { margin: 4px 0 0 0; color: #777; font-size: 11px; }
        .section-title { font-size: 13px; font-weight: bold; color: #01588E; background: #f0f7fc; padding: 6px 10px; margin-top: 20px; margin-bottom: 10px; border-left: 4px solid #01588E; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table td { padding: 6px; vertical-align: top; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-data th { background-color: #01588E; color: white; padding: 8px; text-align: left; font-size: 11px; }
        .table-data td { border: 1px solid #e2e8f0; padding: 8px; }
        .badge { display: inline-block; padding: 4px 10px; font-weight: bold; border-radius: 4px; background-color: #e2e8f0; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #aaa; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>MINDHAVEN HEALTH</h2>
        <p>Laporan Resume Medis Hasil Pemeriksaan Psikometrik Mandiri Elektronik</p>
    </div>

    <div class="section-title">Data Profil Pasien</div>
    <table>
        <tr>
            <td style="width: 20%;"><strong>Nama Lengkap</strong></td>
            <td style="width: 3%;">:</td>
            <td>{{ Auth::user()->name }}</td>
            <td style="width: 20%;"><strong>Tanggal Tes</strong></td>
            <td style="width: 3%;">:</td>
            <td>{{ \Carbon\Carbon::parse($hasil->created_at)->translatedFormat('d F Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>ID Rekam Medis</strong></td>
            <td>:</td>
            <td>#MH-{{ $hasil->id_hasil }}</td>
            <td><strong>Kategori Tes</strong></td>
            <td>:</td>
            <td>Asesmen Kuesioner {{ $hasil->instrumen->nama_tes }}</td>
        </tr>
    </table>

    <div class="section-title">Hasil Diagnosis Ringkas</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 25%;">Parameter Evaluasi</th>
                <th style="width: 20%;">Skor Akumulasi</th>
                <th>Kesimpulan Indikasi Klinis</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tingkat Keparahan</td>
                <td><strong>{{ $hasil->total_skor }} Poin</strong></td>
                <td><span class="badge">{{ $hasil->kesimpulan_status }}</span></td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Catatan & Saran Pendampingan Medis</div>
    <div style="background: #fafafa; padding: 12px; border: 1px solid #eee; border-radius: 8px; font-style: italic;">
        "{!! nl2br(e($hasil->catatan_saran)) !!}"
    </div>

    @if($riwayatTes->count() >= 2)
        <div class="section-title">Tren Riwayat Kestabilan Berkala</div>
        <table class="table-data">
            <thead>
                <tr>
                    <th style="width: 15%;">Sesi Ke</th>
                    <th style="width: 45%;">Tanggal Pemeriksaan</th>
                    <th style="width: 40%;">Perolehan Nilai Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayatTes as $index => $history)
                    <tr>
                        <td>#{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('d F Y') }}</td>
                        <td>{{ $history->total_skor }} Poin</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh sistem informasi pelayanan kesehatan mental MindHaven dan sah tanpa tanda tangan basah.
    </div>

</body>
</html>