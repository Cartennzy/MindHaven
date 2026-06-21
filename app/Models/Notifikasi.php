<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifications';

    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'user_id',
        'psikolog_id',
        'id_konsultasi',
        'id_rujukan',
        'judul',
        'pesan',
        'tipe',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function psikolog()
    {
        return $this->belongsTo(Psikolog::class, 'psikolog_id', 'id_psikolog');
    }

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'id_konsultasi', 'id_konsultasi');
    }

    public function rujukanPsikiater()
    {
        return $this->belongsTo(RujukanPsikiater::class, 'id_rujukan', 'id_rujukan');
    }
}