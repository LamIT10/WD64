<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileRepository
{
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function changePassword(User $user, string $newPassword): User
    {
        $user->password = Hash::make($newPassword);
        $user->save();
        return $user;
    }
}
