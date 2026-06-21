<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    protected $table = 'konsultasis';

    protected $primaryKey = 'id_konsultasi';

    protected $fillable = [
        'id_pasien',
        'id_psikolog',
        'topik_konsultasi',
        'keluhan',
        'tanggal_konsultasi',
        'jam_konsultasi',
        'metode_konsultasi',
        'harga',
        'status',
        'skor_rating',
        'catatan_ulasan',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function psikolog()
    {
        return $this->belongsTo(Psikolog::class, 'id_psikolog', 'id_psikolog');
    }

    public function detailKonsultasi()
    {
        return $this->hasOne(DetailKonsultasi::class, 'id_konsultasi', 'id_konsultasi');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_konsultasi', 'id_konsultasi');
    }

    public function rujukanPsikiater()
    {
        return $this->hasOne(RujukanPsikiater::class, 'id_konsultasi', 'id_konsultasi');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_konsultasi', 'id_konsultasi');
    }

    public function messages()
    {
        return $this->hasMany(KonsultasiMessage::class, 'id_konsultasi', 'id_konsultasi');
    }

    public function getMetodeKonsultasiTextAttribute()
    {
        return match ($this->metode_konsultasi) {
            'chat' => 'Chat',
            'video_call' => 'Video Call',
            'temu_janji' => 'Temu Janji',
            default => '-',
        };
    }
}