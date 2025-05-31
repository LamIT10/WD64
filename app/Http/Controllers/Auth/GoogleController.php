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
        $user = User::where('email', $googleUser->getEmail())->first();
        if (!$user) {
            return redirect()->route("login")->with('error', "Email Google của bạn chưa được đăng ký trong hệ thống!");
        }
        Auth::login($user);
        return $this->returnInertia(['status' => true], 'Đăng nhập thành công!', 'dashboard');
    }
}
