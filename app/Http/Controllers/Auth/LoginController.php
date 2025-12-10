<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Giriş formunu göster
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Giriş işlemi
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Kullanıcıyı bul
        $user = User::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Admin yetkisi kontrolü
            if (!$user->hasPermission('admin')) {
                return back()->withErrors([
                    'username' => 'Bu hesabın yönetici paneline erişim izni yok.',
                ])->withInput($request->only('username'));
            }

            Auth::login($user, $request->boolean('remember'));
            
            // Son giriş zamanını güncelle
            $user->last_visit = now();
            $user->save();

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Giriş bilgileri hatalı.',
        ])->withInput($request->only('username'));
    }

    /**
     * Çıkış işlemi
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
