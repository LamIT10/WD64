<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use function Laravel\Prompts\error;

class LoginRepository extends BaseRepository
{


    public function __construct(User $user)
    {
        $this->handleModel = $user;
    }

    public function login($data)
    {
        try {
            DB::beginTransaction();
            // Login băng thư viện hỗ trợ Atem
            $credentials = [
                'email' => $data['email'],
                'password' => $data['password'],
            ];
            if (!Auth::attempt($credentials)) {
                throw new Exception('Thông tin đăng nhập không chính xác.');
            }

            $user = User::find(Auth::check() ? Auth::user()->id : null);

            // xử lí tk đã nghỉ
             if ($user->status === 'inactive') {
                Auth::logout(); 
            throw new Exception('Thông tin đăng nhập không chính xác.');
             }
            // xử lý các quyên hạng trùng nhau
            $permissions = $user->getPermissionsViaRoles()->pluck("name")->unique()->values();
            $roles = $user->getRoleNames()->toArray();
            session(['permissions' => $permissions]);
            session(['roles' => $roles]);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error('Lỗi đăng nhập: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
}
