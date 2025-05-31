<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Repositories\Auth\ResetPasswordRepository;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{
    public function __construct(ResetPasswordRepository $resetPasswordRepository)
    {
        $this->handleRepository = $resetPasswordRepository;
    }
    // Hiển thị form đặt lại mật khẩu (qua link email)
    public function showResetForm(Request $request, $token)
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Xử lý đặt lại mật khẩu
    public function reset(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        $status = $this->handleRepository->reset($data);

        return $this->returnInertia($status, 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập.', 'login');
    }
}
