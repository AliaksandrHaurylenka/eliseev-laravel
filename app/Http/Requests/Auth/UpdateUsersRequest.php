<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Entity\User;

class UpdateUsersRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,id,' . $this->user->id,
            'status' => ['required', 'string', Rule::in([
                User::STATUS_WAIT,
                User::STATUS_ACTIVE
            ])],
        ];
    }
}