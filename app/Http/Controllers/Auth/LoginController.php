<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\Auth\LoginRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    protected $loginRepository;

    public function __construct(LoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }
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
        $data = $request->validated();
        $status = $this->loginRepository->login($data);
        return $this->returnInertia($status, 'Đăng nhập thành công', 'admin.dashboard' );
    }
    /**
     * Xử lý đăng xuất.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget("permissions");
        session()->forget("roles");
        return redirect('/login');
    }
    
}