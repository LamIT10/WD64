<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Str;

class ResetPasswordRepository extends BaseRepository
{


    public function __construct(User $user)
    {
        $this->handleModel = $user;
    }

    public function reset($data)
    {
        try {
            if (is_array($data)) {
                $data = [
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'password_confirmation' => $data['password_confirmation'],
                    'token' => $data['token'],
                ];
            }

            $status = Password::reset(
                $data,
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->setRememberToken(\Illuminate\Support\Str::random(60));
                    $user->save();
                }
            );


            if ($status === Password::PASSWORD_RESET) {

            } else {
                // dd(__($status));
                $alert = __($status);

                throw new Exception(__($alert));
            }

            return true;
        } catch (\Throwable $th) {
            Log::error('Lỗi đặt lại mật khẩu: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }

}
