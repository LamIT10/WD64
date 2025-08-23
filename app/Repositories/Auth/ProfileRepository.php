<?php

namespace App\Repositories\Auth;

use App\Models\User;

class ProfileRepository
{
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
}
