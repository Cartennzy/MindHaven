<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPsikolog extends Model
{
    protected $table = 'jadwal_psikologs';

    protected $fillable = [
        'id_psikolog',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function psikolog()
    {
        return $this->belongsTo(
            Psikolog::class,
            'id_psikolog',
            'id_psikolog'
        );
    }
}