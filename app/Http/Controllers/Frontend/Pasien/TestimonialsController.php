<?php

namespace App\Http\Controllers\Frontend\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class TestimonialsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman formulir pembuatan testimoni di dashboard pasien
     */
    public function index()
    {
        // Memanggil file yang ada di resources/views/frontend/pasien/testimonials/index.blade.php
        if (View::exists('frontend.pasien.testimonials.index')) {
            return view('frontend.pasien.testimonials.index');
        }
        
        // Cadangan otomatis jika dibaca folder tunggal 'testimonial'
        if (View::exists('frontend.pasien.testimonial.index')) {
            return view('frontend.pasien.testimonial.index');
        }

        // Jalur utama sesuai folder fisik terbaru Anda
        return view('frontend.pasien.testimonials.index');
    }

    /**
     * Menyimpan data ulasan testimoni baru dari pasien ke database
     */
    public function store(Request $request)
    {
        // Validasi input ulasan pasien
        $request->validate([
            'bintang' => 'required|integer|min:1|max:5',
            'ulasan'  => 'required|string|max:1000',
        ]);

        // Menyimpan data testimoni ke tabel 'testimonials' secara realtime
        DB::table('testimonials')->insert([
            'user_id'    => Auth::id(),
            'nama'       => Auth::user()->name ?? 'Pasien MindHaven',
            'bintang'    => $request->bintang,
            'ulasan'     => $request->ulasan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect kembali ke dashboard pasien dengan membawa flash message sukses
        return redirect('/pasien/dashboard')->with('success', 'Testimoni Anda berhasil dikirim dan disinkronkan ke halaman utama!');
    }
}