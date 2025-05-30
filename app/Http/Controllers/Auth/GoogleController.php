<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Tìm user theo email
        $user = User::where('email', $googleUser->getEmail())->first();
        
        if (!$user) {
            // Nếu không tồn tại user, chuyển về trang login kèm thông báo lỗi
            return redirect()->route('login')->withErrors([
                'email' => 'Email Google của bạn chưa được đăng ký trong hệ thống!',
            ]);
        }

        // Nếu tồn tại user, đăng nhập
        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
