<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Auth/Profile', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'position' => $user->position ?? 'Nhân viên',
                'gender' => $user->gender ?? null,
                'dob' => $user->dob ?? null,
                'phone' => $user->phone ?? null,
                'address' => $user->address ?? null,
                'employee_code' => $user->employee_code ?? null,
                'department' => $user->department ?? null,
                'role' => $user->role ?? null,
                'avatar' => $user->avatar
                    ? asset('storage/' . $user->avatar) . '?v=' . $user->updated_at->timestamp
                    : null,
            ]
        ]);
    }
}
