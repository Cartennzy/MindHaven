<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Psikolog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FRONTEND LOGIN PASIEN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'login' => 'Email atau password salah.',
                ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role !== 'pasien') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Login ini hanya untuk pasien.',
                ]);
        }

        return redirect()->route('pasien.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | BACKEND LOGIN ADMIN & PSIKOLOG
    |--------------------------------------------------------------------------
    */

    public function backendLogin()
    {
        return view('backend.auth.login');
    }

    public function backendLoginProcess(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | LOGIN NORMAL KE TABEL USERS
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt($credentials)) {

            /*
            |--------------------------------------------------------------------------
            | FALLBACK LOGIN PSIKOLOG
            | Dipakai kalau email/password psikolog ada di tabel psikologs,
            | tetapi data users belum sinkron.
            |--------------------------------------------------------------------------
            */

            $psikolog = Psikolog::where('email', $request->email)->first();

            if (
                !$psikolog ||
                !$psikolog->user_id ||
                !Hash::check($request->password, $psikolog->password)
            ) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'login' => 'Email atau password salah.',
                    ]);
            }

            $user = $psikolog->user;

            if (!$user) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'login' => 'Akun psikolog belum terhubung dengan data user.',
                    ]);
            }

            $user->update([
                'email' => $psikolog->email,
                'password' => $psikolog->password,
                'role' => 'psikolog',
            ]);

            Auth::login($user);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | PSIKOLOG
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'psikolog') {
            return redirect()->route('psikolog.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE TIDAK SESUAI
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('backend.login')
            ->withErrors([
                'login' => 'Akses backend hanya untuk admin dan psikolog.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}