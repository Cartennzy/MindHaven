<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Konsultasi;
use App\Models\Pasien;
use App\Models\Psikolog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | STATISTIK LANDING PAGE
        |--------------------------------------------------------------------------
        |
        */

        $psikologQuery = Psikolog::query();

        if (Schema::hasColumn('psikologs', 'is_active')) {
            $psikologQuery->where('is_active', true);
        }

        $totalPsikolog = $psikologQuery
            ->where('status_verifikasi', 'verified')
            ->count();

        $totalKonsultasi = Konsultasi::count();

        $totalPasien = Pasien::count();

        /*
        |--------------------------------------------------------------------------
        | PREVIEW PSIKOLOG AKTIF & TERVERIFIKASI
        |--------------------------------------------------------------------------
        |
        */

        $psikologsQuery = Psikolog::with('user')
            ->where('status_verifikasi', 'verified');

        if (Schema::hasColumn('psikologs', 'is_active')) {
            $psikologsQuery->where('is_active', true);
        }

        $psikologs = $psikologsQuery
            ->latest('id_psikolog')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ARTIKEL PUBLIC DARI ADMIN
        |--------------------------------------------------------------------------
        |
        */

        $artikelsQuery = Artikel::with('admin')
            ->latest('id_artikel');

        if (Schema::hasColumn('artikels', 'status')) {
            $artikelsQuery->where('status', true);
        }

        $artikels = $artikelsQuery->take(3)->get();

        /*
        |--------------------------------------------------------------------------
        | SINKRONISASI TESTIMONI PASIEN SECARA REALTIME
        |--------------------------------------------------------------------------
        |
        */
        
        $testimonialsData = [];
        if (Schema::hasTable('testimonials')) {
            $testimonialsData = DB::table('testimonials')
                ->latest()
                ->take(3)
                ->get();
        }

        return view('frontend.home', compact(
            'totalPsikolog',
            'totalKonsultasi',
            'totalPasien',
            'psikologs',
            'artikels',
            'testimonialsData'
        ));
    }
}