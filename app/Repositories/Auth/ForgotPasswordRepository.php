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

class ForgotPasswordRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        $this->handleModel = $user;
    }

    public function login($data)
    {
       // Chưa sửu dung
    }

}
