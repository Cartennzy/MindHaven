<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RumahSakit extends Model
{
    protected $table = 'rumah_sakits';

    protected $primaryKey = 'id_rumahsakit';

    protected $fillable = [
        'nama_rumahsakit',
        'alamat',
        'no_telepon',
        'foto',
        'status',
        'website',
    ];

    protected $casts = [
        'id_rumahsakit' => 'integer',
    ];

    public function psikiaters()
    {
        return $this->hasMany(
            Psikiater::class,
            'id_rumahsakit',
            'id_rumahsakit'
        );
    }
}