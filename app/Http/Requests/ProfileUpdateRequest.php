<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Только авторизованный пользователь может редактировать свой профиль
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Поле “Имя” обязательно.',
            'email.required' => 'Поле “Email” обязательно.',
            'email.email'    => 'Укажите корректный email.',
            'email.unique'   => 'Этот email уже занят.',
        ];
    }
}
