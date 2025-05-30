<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
    /**
     * Hiển thị form đăng nhập.
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Xử lý đăng nhập.
     */
    public function login(LoginRequest $request)
    {
       $user = User::where('email', $request->email)->first();
        
    //    return !Hash::check("123456", "$2y$10$.dQHrt9q9Z2xYhY.gE6zY.dh/wBUEglmBHWNWioI2Rv5n2vd4KUQK");

        if (!$user || !Hash::check($request->password, $user->password)) {
            // Nếu thông tin đăng nhập không hợp lệ, trả về lỗi
            throw ValidationException::withMessages([
                'email' => 'Thông tin đăng nhập không hợp lệ.',
            ]);
        }
        // return [$user, $request->password];

        // Đăng nhập thủ công
        Auth::login($user);

        // $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
    /**
     * Xử lý đăng xuất.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
    
}