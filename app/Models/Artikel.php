<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $table = 'artikels';

    protected $primaryKey = 'id_artikel';

    protected $fillable = [
        'id_admin',
        'kategori',
        'judul',
        'konten',
        'gambar',
        'penulis',
        'sumber_artikel',
        'tanggal_publish',
        'status',
    ];

    protected $casts = [
        'tanggal_publish' => 'date',
        'status' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'id_artikel';
    }

    public function admin()
    {
        return $this->belongsTo(
            Admin::class,
            'id_admin',
            'id_admin'
        );
    }
}