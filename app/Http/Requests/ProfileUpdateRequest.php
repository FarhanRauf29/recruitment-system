<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];


        if ($this->user()->role === 'pelamar') {
            $rules = array_merge($rules, [
                'no_telepon' => ['nullable', 'string', 'max:15'],
                'alamat' => ['nullable', 'string', 'max:255'],
                'tanggal_lahir' => ['nullable', 'date'],
                'jenis_kelamin' => ['nullable', 'in:Laki-laki,Perempuan'],
                'pendidikan_terakhir' => ['nullable', 'string'],
                'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]);
        }

        return $rules;
    }
}
