<?php
namespace App\Repositories;
use App\Models\User;
class UserRepository extends BaseRepository
{
    public function __construct()
    {
        $this->handleModel = User::class;
    }

    public function allWithPaginate($limit = 10)
    {
        return User::paginate($limit);
    }
}
