<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilTes extends Model
{
    use HasFactory;

    protected $table = 'hasil_tes';
    protected $primaryKey = 'id_hasil';

    protected $fillable = [
        'id_pasien',
        'id_instrumen',
        'total_skor',
        'kesimpulan_status',
        'catatan_saran',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function instrumen()
    {
        return $this->belongsTo(InstrumenTes::class, 'id_instrumen', 'id_instrumen');
    }
}