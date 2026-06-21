<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meditasi extends Model
{
    protected $table = 'meditasis';

    protected $primaryKey = 'id_meditasi';

    protected $fillable = [
        'id_admin',
        'kategori',
        'judul',
        'deskripsi',
        'audio',
        'durasi',
        'status',
    ];

    public function getRouteKeyName()
    {
        return 'id_meditasi';
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}