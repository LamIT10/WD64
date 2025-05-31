<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return Inertia::render('Auth/ForgotPassword');
    }
    
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink( $request->only('email'));
        if ($status === Password::RESET_LINK_SENT) {
            return $this->returnInertia(__($status),'Đường dẫn đặt lại mật khẩu đã được gửi đến email của bạn.','login');
        } else {
            return redirect()->back()->withErrors(['email' => __($status),]);
        }
    }
}
