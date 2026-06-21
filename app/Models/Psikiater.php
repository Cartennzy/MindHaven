<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Psikiater extends Model
{
    protected $table = 'psikiaters';

    protected $primaryKey = 'id_psikiater';

    protected $fillable = [
        'id_rumahsakit',
        'nama_lengkap',
        'email',
        'spesialisasi',
        'no_telepon',
        'jadwal_praktik',
        'str_psikiater',
        'status',
        'pengalaman',
        'foto_profil',
        'alamat_praktik',
    ];

    protected $casts = [
        'status' => 'boolean',
        'pengalaman' => 'integer',
    ];

    public function rumahSakit()
    {
        return $this->belongsTo(RumahSakit::class, 'id_rumahsakit', 'id_rumahsakit');
    }

    public function rujukanPsikiaters()
    {
        return $this->hasMany(RujukanPsikiater::class, 'id_psikiater', 'id_psikiater');
    }
}