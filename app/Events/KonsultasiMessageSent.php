<?php

namespace App\Events;

use App\Models\KonsultasiMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KonsultasiMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $idKonsultasi;

    public function __construct(KonsultasiMessage $message)
{
    $this->idKonsultasi = $message->id_konsultasi;

    if ($message->sender_role === 'psikolog') {

        $senderName =
            \App\Models\Psikolog::find($message->sender_id)
            ?->nama_lengkap ?? 'Psikolog';

    } else {

        $senderName =
            \App\Models\Pasien::find($message->sender_id)
            ?->nama_lengkap ?? 'Pasien';

    }

    $this->message = [
        'sender_role' => $message->sender_role,
        'sender_name' => $senderName,
        'pesan' => $message->pesan,
        'time' => $message->created_at->format('H:i') . ' WIB',
    ];
}

    public function broadcastOn(): array
    {
        return [
            new Channel(
                'konsultasi.' . $this->idKonsultasi
            )
        ];
    }

    public function broadcastAs(): string
    {
        return 'KonsultasiMessageSent';
    }
}