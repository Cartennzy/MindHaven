<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Psikolog extends Model
{
    protected $table = 'psikologs';

    protected $primaryKey = 'id_psikolog';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'email',
        'password',
        'no_telepon',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'foto_profil',
        'spesialisasi',
        'pengalaman',
        'biaya_konsultasi',

        'pendidikan',
        'dokumen_pendidikan',

        'str_psikolog',
        'dokumen_str_psikolog',

        'sip_psikolog',
        'dokumen_sip_psikolog',

        'jadwal_praktik',

        'bio',
        'metode_konsultasi',

        'dokumen_verifikasi',
        'catatan_verifikasi',

        'is_active',
        'status_verifikasi',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'biaya_konsultasi' => 'decimal:2',
        'pengalaman' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getSpesialisasiLabelAttribute()
    {
        return str_replace('_', ' ', $this->spesialisasi);
    }

    public function jadwalPraktiks()
    {
        return $this->hasMany(
            JadwalPsikolog::class,
            'id_psikolog',
            'id_psikolog'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function konsultasis()
    {
        return $this->hasMany(Konsultasi::class, 'id_psikolog', 'id_psikolog');
    }

    public function rujukanPsikiaters()
    {
        return $this->hasMany(RujukanPsikiater::class, 'id_psikolog', 'id_psikolog');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'psikolog_id', 'id_psikolog');
    }
}