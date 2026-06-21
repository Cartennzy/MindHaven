<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodLog extends Model
{
    use HasFactory;

    protected $table = 'mood_logs';

    protected $fillable = [
        'user_id', 
        'mood_score', 
        'logged_at'
    ];

    // Relasi balik ke model User / Pasien
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}