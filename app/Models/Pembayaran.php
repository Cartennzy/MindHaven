<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';

    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_konsultasi',
        'id_order',
        'snap_token',
        'snap_redirect_url',
        'metode_pembayaran',
        'biaya_admin',
        'biaya_psikolog',
        'total_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
    ];

    protected $casts = [
        'biaya_admin' => 'decimal:2',
        'biaya_psikolog' => 'decimal:2',
        'total_pembayaran' => 'decimal:2',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'id_konsultasi', 'id_konsultasi');
    }
}