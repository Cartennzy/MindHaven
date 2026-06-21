<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';

    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nama',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function artikels()
    {
        return $this->hasMany(Artikel::class, 'id_admin', 'id_admin');
    }

    public function meditasis()
    {
        return $this->hasMany(Meditasi::class, 'id_admin', 'id_admin');
    }
}