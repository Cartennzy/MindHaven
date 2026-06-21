<?php

namespace App\Http\Controllers\Frontend;

use App\Events\KonsultasiMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\KonsultasiMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsultasiMessageController extends Controller
{
    public function store(Request $request, Konsultasi $konsultasi)
    {
        $this->authorizeChatAccess($konsultasi);

        if ($konsultasi->status !== 'diproses') {
            return response()->json([
                'success'=>false,
                'message'=>'Pesan hanya dapat dikirim saat konsultasi diproses.'
            ],422);
        }

        $request->validate([
            'pesan'=>'required|string|max:2000'
        ]);

        $role = Auth::user()->role;

        $senderId =
            $role === 'pasien'
            ? Auth::user()->pasien->id_pasien
            : Auth::user()->psikolog->id_psikolog;

        $message = KonsultasiMessage::create([
            'id_konsultasi'=>$konsultasi->id_konsultasi,
            'sender_role'=>$role,
            'sender_id'=>$senderId,
            'pesan'=>$request->pesan,
            'is_read'=>false,
        ]);

        broadcast(
            new KonsultasiMessageSent(
                $message
            )
        )->toOthers();

        return response()->json([
            'success'=>true,
            'message'=>'Pesan berhasil dikirim.',
            'data'=>[
                'sender_role'=>$message->sender_role,
                'sender_name'=>Auth::user()->name,
                'pesan'=>$message->pesan,
                'time'=>$message->created_at->format('H:i').' WIB'
            ]
        ]);
    }

    public function fetch(Konsultasi $konsultasi)
{
    $this->authorizeChatAccess($konsultasi);

    $messages = KonsultasiMessage::where(
            'id_konsultasi',
            $konsultasi->id_konsultasi
        )
        ->oldest()
        ->get()
        ->map(function ($message) {

            if ($message->sender_role === 'psikolog') {

                $senderName =
                    \App\Models\Psikolog::find($message->sender_id)
                    ?->nama_lengkap ?? 'Psikolog';

            } else {

                $senderName =
                    \App\Models\Pasien::find($message->sender_id)
                    ?->nama_lengkap ?? 'Pasien';

            }

            return [
                'sender_role' => $message->sender_role,
                'sender_name' => $senderName,
                'pesan' => $message->pesan,
                'time' => $message->created_at->format('H:i') . ' WIB',
                'created_at' => $message->created_at,
            ];
        });

    return response()->json([
        'success' => true,
        'messages' => $messages
    ]);
}

    private function authorizeChatAccess(Konsultasi $konsultasi)
    {
        $user=Auth::user();

        if(
            $user->role==='pasien'
            &&
            $konsultasi->id_pasien
            !==
            $user->pasien->id_pasien
        ){
            abort(403);
        }

        if(
            $user->role==='psikolog'
            &&
            $konsultasi->id_psikolog
            !==
            $user->psikolog->id_psikolog
        ){
            abort(403);
        }
    }
}