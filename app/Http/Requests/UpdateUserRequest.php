<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
            'role' => ['required', 'in:student,teacher,admin'],
            'phone' => ['nullable', 'regex:/^[0-9]{9,15}$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'nif' => ['nullable', 'regex:/^[0-9]{9}$/', 'unique:users,nif,' . $userId],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'O nome deve conter apenas letras e espaços.',
            'phone.regex' => 'O número de telefone deve conter apenas números (9-15 dígitos).',
            'nif.regex' => 'O NIF deve conter exatamente 9 dígitos.',
            'nif.unique' => 'Este NIF já está registado no sistema.',
            'date_of_birth.before' => 'A data de nascimento deve ser anterior à data atual.',
            'profile_picture.image' => 'O ficheiro deve ser uma imagem.',
            'profile_picture.max' => 'A imagem não pode exceder 2MB.',
        ];
    }
}
