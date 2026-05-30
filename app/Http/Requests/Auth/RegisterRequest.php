<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Step 1: Personal Information
            'lastname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:20'],

            // Step 2: Division & Position
            'division' => ['required', 'string', 'in:Finance and Administrative Division,Migrant Workers Processing Division,Migrant Workers Protection Division,Welfare and Reintegration Division'],
            'position' => ['required', 'string', 'in:EMPLOYEE,HRSTAFF,CHIEF,REGIONALDIRECTOR,ADMIN'],

            // Step 3: Credentials
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'lastname.required' => 'Last name is required.',
            'firstname.required' => 'First name is required.',
            'division.required' => 'Please select your division.',
            'division.in' => 'Please select a valid division.',
            'position.required' => 'Please select your position.',
            'position.in' => 'Please select a valid position.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
