<?php

namespace App\Http\Requests\Auth;

use App\Models\UtilityOption;
use App\Models\User;
use App\Support\UtilityOptionRegistry;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        UtilityOptionRegistry::ensureDefaults();
        $divisionOptions = UtilityOption::listFor('divisions')->pluck('value')->values()->all();

        return [
            // Step 1: Personal Information
            'lastname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:20'],

            // Step 2: Division & Position
            'division' => ['required', 'string', Rule::in($divisionOptions)],
            'position' => ['required', 'string', 'in:EMPLOYEE,HRSTAFF,RECORDOFFICER,CHIEF,REGIONALDIRECTOR,ADMIN'],

            // Step 3: Credentials
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
            'privacy_consent' => ['accepted'],
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
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter and one number.',
            'privacy_consent.accepted' => 'You must agree to the Data Privacy consent to register.',
        ];
    }
}

