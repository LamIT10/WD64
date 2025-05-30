<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return inertia('Auth/ForgotPassword');
    }
    
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );
        
        if ($status === Password::RESET_LINK_SENT) {
            // Nếu gửi thành công, trả về thông báo thành công cho Inertia/Vue
            return redirect()->back()->with([
                'success' => __($status),
            ]);
        } else {
            // Nếu gửi thất bại (email không tồn tại...), trả về lỗi cho Inertia/Vue
            return redirect()->back()->withErrors([
                'email' => __($status),
            ]);
        }
    }
}
