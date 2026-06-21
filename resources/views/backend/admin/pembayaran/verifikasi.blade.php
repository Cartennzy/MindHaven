@extends('backend.layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="admin-title">Verifikasi Pembayaran</h1>
        <p class="admin-subtitle">Konfirmasi status pembayaran konsultasi pasien.</p>
    </div>

    <form action="#" method="POST" class="admin-card p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-sm text-slate-500">Kode Pembayaran</p>
                <h4 class="font-bold text-[#01588E] mt-1">MH-PAY-001</h4>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-sm text-slate-500">Nominal</p>
                <h4 class="font-bold text-slate-800 mt-1">Rp150.000</h4>
            </div>
        </div>

        <div>
            <label class="admin-label">Status Pembayaran</label>
            <select name="status" class="admin-input">
                <option value="pending">Pending</option>
                <option value="berhasil">Berhasil</option>
                <option value="gagal">Gagal</option>
            </select>
        </div>

        <div>
            <label class="admin-label">Catatan Admin</label>
            <textarea name="catatan" rows="5" class="admin-input" placeholder="Catatan verifikasi..."></textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="admin-btn-primary">Simpan Verifikasi</button>
            <a href="{{ route('admin.pembayaran.index') }}" class="admin-btn-light">Batal</a>
        </div>
    </form>
</div>
@endsection