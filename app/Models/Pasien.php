<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasiens';

    protected $primaryKey = 'id_pasien';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'no_telepon',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'foto_profil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function konsultasis()
    {
        return $this->hasMany(Konsultasi::class, 'id_pasien', 'id_pasien');
    }

    public function rujukanPsikiaters()
    {
        return $this->hasMany(RujukanPsikiater::class, 'id_pasien', 'id_pasien');
    }
}