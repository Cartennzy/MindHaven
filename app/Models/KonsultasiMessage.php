<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonsultasiMessage extends Model
{
    protected $table = 'konsultasi_messages';

    protected $primaryKey = 'id_message';

    protected $fillable = [
        'id_konsultasi',
        'sender_role',
        'sender_id',
        'pesan',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'id_konsultasi', 'id_konsultasi');
    }
}