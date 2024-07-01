<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return $this->store();
            case 'PUT':
                return $this->update();
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function store()
    {
        return [
            'uid' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'phone_2' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'birth_date' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:1',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function update()
    {
        return [
            'uid'         => 'nullable|string|max:255',
            'first_name'  => 'nullable|string|max:255',
            'last_name'   => 'nullable|string|max:255',
            'email'       => 'string|email|max:100|unique:users,email',
            'password'    => 'string|max:255',
            'address'     => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:255',
            'phone_2'     => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'birth_date'  => 'nullable|string|max:255',
            'gender'      => 'nullable|string|max:1',
        ];
    }
}
