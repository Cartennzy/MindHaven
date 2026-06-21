<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RujukanPsikiater extends Model
{
    protected $table = 'rujukan_psikiaters';

    protected $primaryKey = 'id_rujukan';

    protected $fillable = [
        'id_konsultasi',
        'id_psikolog',
        'id_pasien',
        'id_psikiater',
        'id_rumahsakit',
        'nomor_rujukan',
        'diagnosa_awal',
        'alasan_rujukan',
        'catatan_rujukan',
        'catatan_psikolog',
        'status',
        'status_rujukan',
        'tanggal_rujukan',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'id_konsultasi', 'id_konsultasi');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function psikolog()
    {
        return $this->belongsTo(Psikolog::class, 'id_psikolog', 'id_psikolog');
    }

    public function psikiater()
    {
        return $this->belongsTo(Psikiater::class, 'id_psikiater', 'id_psikiater');
    }

    public function rumahSakit()
    {
        return $this->belongsTo(RumahSakit::class, 'id_rumahsakit', 'id_rumahsakit');
    }

    public function getRumahSakitRujukanAttribute()
    {
        return $this->rumahSakit ?? $this->psikiater?->rumahSakit;
    }
}