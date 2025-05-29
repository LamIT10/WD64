<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->id,
            'password' => $this->id ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
