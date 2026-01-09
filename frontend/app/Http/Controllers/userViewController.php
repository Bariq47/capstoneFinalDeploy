<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserViewController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $response = Http::post(env('API_URL') . '/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->failed()) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->withInput();
        }

        session([
            'jwt_token' => $response->json('token'),
            'role' => $response->json('user.role'),
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Logout user
     */
    public function logout()
    {
        session()->flush();

        return redirect()->route('login')
            ->with('success', 'Berhasil logout');
    }
}
