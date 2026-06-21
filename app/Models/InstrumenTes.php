<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumenTes extends Model
{
    use HasFactory;

    protected $table = 'instrumen_tes';
    protected $primaryKey = 'id_instrumen';

    protected $fillable = [
        'nama_tes',
        'slug',
        'deskripsi',
        'rules_skor',
    ];

    protected $casts = [
        'rules_skor' => 'array',
    ];

    public function pertanyaans()
    {
        return $this->hasMany(PertanyaanTes::class, 'id_instrumen', 'id_instrumen');
    }

    public function hasils()
    {
        return $this->hasMany(HasilTes::class, 'id_instrumen', 'id_instrumen');
    }
}