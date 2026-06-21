<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Konsultasi;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('konsultasi.{idKonsultasi}', function ($user, $idKonsultasi) {

    $konsultasi = Konsultasi::find($idKonsultasi);

    if (!$konsultasi) {
        return false;
    }

    if (
        $user->role === 'pasien' &&
        $user->pasien &&
        $konsultasi->id_pasien === $user->pasien->id_pasien
    ) {
        return true;
    }

    if (
        $user->role === 'psikolog' &&
        $user->psikolog &&
        $konsultasi->id_psikolog === $user->psikolog->id_psikolog
    ) {
        return true;
    }

    return false;
});