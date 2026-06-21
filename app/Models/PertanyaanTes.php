<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanTes extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan_tes';
    protected $primaryKey = 'id_pertanyaan';

    protected $fillable = [
        'id_instrumen',
        'teks_pertanyaan', // Mengunci fillable teks_pertanyaan
        'pilihan_opsi',
    ];

    protected $casts = [
        'pilihan_opsi' => 'array',
    ];

    public function instrumen()
    {
        return $this->belongsTo(InstrumenTes::class, 'id_instrumen', 'id_instrumen');
    }
}