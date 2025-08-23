<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Repositories\Auth\ProfileRepository;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $profileRepo;

    public function __construct(ProfileRepository $profileRepo)
    {
        $this->profileRepo = $profileRepo;
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $this->profileRepo->update($user, $request->validated());

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }
}
