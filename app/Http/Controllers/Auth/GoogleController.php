<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'nama' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt('google-login'),
                'role' => 'mahasiswa'
            ]);

            // Kirim email reset password untuk pendaftar baru dari Google
            \Illuminate\Support\Facades\Password::broker()->sendResetLink(
                ['email' => $user->email]
            );
        }

        Auth::login($user);

        if (in_array($user->role, ['admin', 'dosen', 'ilb'])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('mahasiswa.home');
    }
}
