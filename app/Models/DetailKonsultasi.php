<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKonsultasi extends Model
{
    protected $table = 'detail_konsultasis';

    protected $primaryKey = 'id_detailkonsultasi';

    protected $fillable = [
        'id_konsultasi',
        'keluhan_utama',
        'riwayat_hidup',
        'hasil_observasi',
        'diagnosis_awal',
        'rencana_penanganan',
        'laporan_asesmen_psikologis',
        'perlu_rujukan',
    ];

    protected $casts = [
        'perlu_rujukan' => 'boolean',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'id_konsultasi', 'id_konsultasi');
    }
}