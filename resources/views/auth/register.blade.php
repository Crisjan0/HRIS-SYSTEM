<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMW HRIS - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.35s ease-out;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(24px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-24px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .slide-in-right { animation: slideInRight 0.3s ease-out; }
        .slide-in-left { animation: slideInLeft 0.3s ease-out; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }

        .registration-card-scroll {
            max-height: calc(100vh - 3rem);
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        @supports (height: 100dvh) {
            .registration-card-scroll {
                max-height: calc(100dvh - 3rem);
            }
        }

        @media (max-width: 640px) {
            .registration-card-scroll {
                max-height: calc(100vh - 1.5rem);
            }
        }

        /* Custom select styling */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
        }
    </style>
</head>

<body class="relative min-h-screen flex items-center justify-center font-sans overflow-hidden">

    <div class="fixed inset-0 z-0">
       <img src="{{ asset('images/background-DMWHRIS.jpg') }}" class="w-full h-full object-cover"
             alt="Background">
    </div>

    <div class="relative z-10 container mx-auto flex flex-col lg:flex-row items-center justify-center gap-12 px-6 py-8">

        <div class="text-white max-w-lg text-center lg:text-left">
            <h1 class="text-5xl font-bold leading-tight">
                Department of <br> Migrant Workers
            </h1>
            <p class="mt-4 text-xl italic font-light opacity-90">
                Kagawaran ng Manggagawang Pandarayuhan
            </p>
        </div>

        {{-- Multi-step registration card --}}
        <div class="registration-card-scroll bg-white rounded-2xl shadow-2xl p-8 sm:p-10 w-full max-w-md animate-fade-in-up"
             x-data="registrationWizard()"
             x-cloak>

            <div class="flex flex-col items-center mb-6">
              <img src="{{ asset('images/logo-DMW.png') }}" alt="HRIS Logo" class="h-20 w-auto object-contain">
            </div>

            {{-- Step Indicator --}}
            <div class="flex items-center justify-center mb-8 px-2">
                <template x-for="(label, idx) in stepLabels" :key="idx">
                    <div class="flex items-center">
                        {{-- Step circle --}}
                        <div class="flex flex-col items-center">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300 shadow-sm"
                                 :class="step > idx + 1 ? 'bg-green-500 text-white' : (step === idx + 1 ? 'bg-[#1e3a8a] text-white ring-4 ring-blue-200' : 'bg-gray-200 text-gray-500')">
                                <template x-if="step > idx + 1">
                                    <i class="fas fa-check text-xs"></i>
                                </template>
                                <template x-if="step <= idx + 1">
                                    <span x-text="idx + 1"></span>
                                </template>
                            </div>
                            <span class="text-[10px] mt-1 font-medium text-center leading-tight w-16"
                                  :class="step === idx + 1 ? 'text-[#1e3a8a]' : 'text-gray-400'"
                                  x-text="label"></span>
                        </div>
                        {{-- Connector line --}}
                        <template x-if="idx < stepLabels.length - 1">
                            <div class="w-8 h-0.5 mx-1 -mt-4 transition-all duration-300"
                                 :class="step > idx + 1 ? 'bg-green-400' : 'bg-gray-200'"></div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Server validation errors --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-red-600 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm" @submit="handleSubmit($event)">
                @csrf
                <input type="hidden" name="privacy_consent" :value="consentAccepted ? '1' : ''">

                {{-- ===================== STEP 1: Personal Information ===================== --}}
                <div x-show="step === 1" x-transition:enter="slide-in-right" class="space-y-4">
                    <h2 class="text-lg font-bold text-gray-800 text-center mb-1">
                        <i class="fas fa-user-circle text-[#1e3a8a] mr-1"></i> Personal Information
                    </h2>
                    <p class="text-xs text-gray-400 text-center mb-4">Fill in your basic details</p>

                    {{-- Last Name --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="lastname" x-model="form.lastname"
                               placeholder="e.g. Dela Cruz"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 text-sm"
                               id="reg-lastname">
                        <p x-show="errors.lastname" x-text="errors.lastname" class="text-red-500 text-xs mt-1"></p>
                    </div>

                    {{-- First Name --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="firstname" x-model="form.firstname"
                               placeholder="e.g. Juan"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 text-sm"
                               id="reg-firstname">
                        <p x-show="errors.firstname" x-text="errors.firstname" class="text-red-500 text-xs mt-1"></p>
                    </div>

                    {{-- Middle Name --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Middle Name</label>
                        <input type="text" name="middlename" x-model="form.middlename"
                               placeholder="e.g. Santos"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 text-sm"
                               id="reg-middlename">
                    </div>

                    {{-- Suffix --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Suffix</label>
                        <select name="suffix" x-model="form.suffix"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 text-sm bg-white"
                                id="reg-suffix">
                            <option value="">None</option>
                            <option value="Jr.">Jr.</option>
                            <option value="Sr.">Sr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                            <option value="V">V</option>
                        </select>
                    </div>
                </div>

                {{-- ===================== STEP 2: Division & Position ===================== --}}
                <div x-show="step === 2" x-transition:enter="slide-in-right" class="space-y-4">
                    <h2 class="text-lg font-bold text-gray-800 text-center mb-1">
                        <i class="fas fa-building text-[#1e3a8a] mr-1"></i> Division & Position
                    </h2>
                    <p class="text-xs text-gray-400 text-center mb-4">Select your division and position</p>

                    {{-- Division --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Division <span class="text-red-500">*</span></label>
                        <select name="division" x-model="form.division"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 text-sm bg-white"
                                id="reg-division">
                            <option value="">— Select Division —</option>
                            <option value="Finance and Administrative Division">Finance and Administrative Division</option>
                            <option value="Migrant Workers Processing Division">Migrant Workers Processing Division</option>
                            <option value="Migrant Workers Protection Division">Migrant Workers Protection Division</option>
                            <option value="Welfare and Reintegration Division">Welfare and Reintegration Division</option>
                        </select>
                        <p x-show="errors.division" x-text="errors.division" class="text-red-500 text-xs mt-1"></p>
                    </div>

                    {{-- Position --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Position <span class="text-red-500">*</span></label>
                        <select name="position" x-model="form.position"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 text-sm bg-white"
                                id="reg-position">
                            <option value="">— Select Position —</option>
                            <option value="EMPLOYEE">Employee</option>
                            <option value="HRSTAFF">HR Staff</option>
                            <option value="CHIEF">Chief</option>
                            <option value="REGIONALDIRECTOR">Regional Director</option>
                            <option value="ADMIN">Admin</option>
                        </select>
                        <p x-show="errors.position" x-text="errors.position" class="text-red-500 text-xs mt-1"></p>
                    </div>

                    {{-- Division info box --}}
                    <div x-show="form.division" x-transition class="mt-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <p class="text-xs text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            You selected: <strong x-text="form.division"></strong>
                        </p>
                    </div>
                </div>

                {{-- ===================== STEP 3: Account Credentials ===================== --}}
                <div x-show="step === 3" x-transition:enter="slide-in-right" class="space-y-4">
                    <h2 class="text-lg font-bold text-gray-800 text-center mb-1">
                        <i class="fas fa-lock text-[#1e3a8a] mr-1"></i> Account Credentials
                    </h2>
                    <p class="text-xs text-gray-400 text-center mb-4">Set up your login credentials</p>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" x-model="form.email"
                               placeholder="e.g. juan@dmw.gov.ph"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 text-sm"
                               id="reg-email">
                        <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-xs mt-1"></p>
                    </div>

                    {{-- Password --}}
                    <div class="relative">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Password <span class="text-red-500">*</span></label>
                        <input :type="showPassword ? 'text' : 'password'" name="password" x-model="form.password"
                               placeholder="Minimum 8 characters"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 text-sm pr-10"
                               id="reg-password">
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3 top-7 text-gray-400 hover:text-gray-600">
                            <i :class="showPassword ? 'fas fa-eye' : 'fas fa-eye-slash'"></i>
                        </button>
                        <p x-show="errors.password" x-text="errors.password" class="text-red-500 text-xs mt-1"></p>
                    </div>

                    {{-- Password Strength Meter --}}
                    <div x-show="form.password.length > 0" x-transition class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-500">Password Strength</span>
                            <span class="text-xs font-bold"
                                  :class="{
                                      'text-red-500': getPasswordStrength() === 'Weak',
                                      'text-yellow-500': getPasswordStrength() === 'Fair',
                                      'text-green-500': getPasswordStrength() === 'Strong',
                                  }"
                                  x-text="getPasswordStrength()"></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 ease-out"
                                 :class="{
                                     'bg-red-500': getPasswordStrength() === 'Weak',
                                     'bg-yellow-400': getPasswordStrength() === 'Fair',
                                     'bg-green-500': getPasswordStrength() === 'Strong',
                                 }"
                                 :style="'width: ' + getPasswordStrengthPercent() + '%'"></div>
                        </div>
                        <ul class="space-y-1 mt-1">
                            <li class="text-xs flex items-center gap-1.5"
                                :class="form.password.length >= 8 ? 'text-green-600' : 'text-gray-400'">
                                <i :class="form.password.length >= 8 ? 'fas fa-check-circle' : 'fas fa-circle'" class="text-[8px]"></i>
                                At least 8 characters
                            </li>
                            <li class="text-xs flex items-center gap-1.5"
                                :class="/[A-Z]/.test(form.password) ? 'text-green-600' : 'text-gray-400'">
                                <i :class="/[A-Z]/.test(form.password) ? 'fas fa-check-circle' : 'fas fa-circle'" class="text-[8px]"></i>
                                At least one uppercase letter
                            </li>
                            <li class="text-xs flex items-center gap-1.5"
                                :class="/[0-9]/.test(form.password) ? 'text-green-600' : 'text-gray-400'">
                                <i :class="/[0-9]/.test(form.password) ? 'fas fa-check-circle' : 'fas fa-circle'" class="text-[8px]"></i>
                                At least one number
                            </li>
                        </ul>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="relative">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" x-model="form.password_confirmation"
                               placeholder="Re-enter your password"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 text-sm pr-10"
                               id="reg-password-confirmation">
                        <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-7 text-gray-400 hover:text-gray-600">
                            <i :class="showConfirm ? 'fas fa-eye' : 'fas fa-eye-slash'"></i>
                        </button>
                    </div>

                    {{-- Password match indicator --}}
                    <div x-show="form.password_confirmation.length > 0" x-transition>
                        <p class="text-xs font-medium"
                           :class="form.password === form.password_confirmation ? 'text-green-600' : 'text-red-500'">
                            <i :class="form.password === form.password_confirmation ? 'fas fa-check-circle' : 'fas fa-times-circle'" class="mr-1"></i>
                            <span x-text="form.password === form.password_confirmation ? 'Passwords match' : 'Passwords do not match'"></span>
                        </p>
                    </div>
                </div>

                {{-- ===================== Navigation Buttons ===================== --}}
                <div class="flex items-center justify-between mt-8 gap-3">
                    {{-- Back button --}}
                    <button type="button"
                            x-show="step > 1"
                            @click="prevStep()"
                            class="flex items-center gap-1.5 px-5 py-2.5 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition duration-200 text-sm font-medium">
                        <i class="fas fa-arrow-left text-xs"></i> Back
                    </button>
                    <div x-show="step === 1"></div>

                    {{-- Next / Submit button --}}
                    <template x-if="step < 3">
                        <button type="button"
                                @click="nextStep()"
                                class="flex items-center gap-1.5 px-5 py-2.5 bg-[#1e3a8a] text-white rounded-lg hover:bg-blue-900 transition duration-300 shadow-lg text-sm font-bold ml-auto">
                            Next <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </template>
                    <template x-if="step === 3">
                        <button type="submit"
                                :disabled="submitting"
                                @click.prevent="handleSubmit($event)"
                                class="flex items-center gap-1.5 px-5 py-2.5 bg-[#1e3a8a] text-white rounded-lg hover:bg-blue-900 transition duration-300 shadow-lg text-sm font-bold ml-auto disabled:opacity-50 disabled:cursor-not-allowed">
                            <template x-if="!submitting">
                                <span><i class="fas fa-paper-plane mr-1"></i> Submit & Verify</span>
                            </template>
                            <template x-if="submitting">
                                <span><i class="fas fa-spinner fa-spin mr-1"></i> Processing...</span>
                            </template>
                        </button>
                    </template>
                </div>

                @error('privacy_consent')
                    <p class="mt-3 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </form>

            {{-- Data Privacy Consent Modal --}}
            <div x-show="showConsentModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
                <div class="absolute inset-0 bg-black/50" @click="showConsentModal = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 sm:p-8 animate-fade-in-up">
                    <div class="flex items-start justify-between gap-4">
                        <h3 class="text-lg font-bold text-gray-800">Data Privacy Act of 2012 Consent</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600" @click="showConsentModal = false">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mt-4 space-y-4 text-sm text-gray-700 leading-relaxed max-h-[50vh] overflow-y-auto pr-1">
                        <p>
                            Data Privacy Act of 2012, I consent to the following terms and conditions on the collection, use,
                            processing and disclosure of my personal data: I am aware that the Department of Migrant Workers
                            has collected and stored my personal data upon accomplishment of this form. These data include
                            my full name, contact details like addresses, and landline/mobile numbers. I express my consent
                            for the Department of Migrant Workers to collect, store my personal information. I hereby affirm
                            my right to be informed, object to processing, access, and rectify and to suspend or withdraw my
                            personal data pursuant to the provisions of the RA 10173 and its implementing rules and regulations.
                        </p>
                        <p>
                            By clicking the Agree button below, I warrant that I have read, understood all of the above
                            provisions, and agreed with its full implementation.
                        </p>
                        <p>
                            Read the full text of the law at
                            <a href="https://privacy.gov.ph/data-privacy-act/" target="_blank" rel="noopener noreferrer" class="text-blue-700 font-semibold hover:underline">privacy.gov.ph/data-privacy-act</a>.
                        </p>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button"
                                class="px-4 py-2.5 text-sm font-semibold text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
                                @click="showConsentModal = false">
                            Cancel
                        </button>
                        <button type="button"
                                class="px-4 py-2.5 text-sm font-bold text-white bg-[#1e3a8a] rounded-lg hover:bg-blue-900"
                                @click="agreeConsent()">
                            Agree
                        </button>
                    </div>
                </div>
            </div>

            {{-- Login link --}}
            <div class="mt-6 text-center">
                <p class="text-gray-700 text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-700 font-semibold hover:underline">Sign in here</a>
                </p>
            </div>

            <div class="mt-6 flex justify-center items-center gap-4">
                <img src="https://www.gppb.gov.ph/wp-content/uploads/2023/07/Logo-Bagong-Pilipinas.png"
                     alt="Bagong Pilipinas" class="h-10">
                <img src="https://dmw.gov.ph/images/dmw_logo.png"
                     alt="DMW Seal" class="h-10">
            </div>
        </div>
    </div>

    <script>
        function registrationWizard() {
            return {
                step: 1,
                submitting: false,
                showPassword: false,
                showConfirm: false,
                showConsentModal: false,
                consentAccepted: false,
                stepLabels: ['Personal', 'Division', 'Credentials'],
                form: {
                    lastname: '{{ old("lastname", "") }}',
                    firstname: '{{ old("firstname", "") }}',
                    middlename: '{{ old("middlename", "") }}',
                    suffix: '{{ old("suffix", "") }}',
                    division: '{{ old("division", "") }}',
                    position: '{{ old("position", "") }}',
                    email: '{{ old("email", "") }}',
                    password: '',
                    password_confirmation: '',
                },
                errors: {},

                validateStep(stepNum) {
                    this.errors = {};
                    let valid = true;

                    if (stepNum === 1) {
                        if (!this.form.lastname.trim()) {
                            this.errors.lastname = 'Last name is required.';
                            valid = false;
                        }
                        if (!this.form.firstname.trim()) {
                            this.errors.firstname = 'First name is required.';
                            valid = false;
                        }
                    }

                    if (stepNum === 2) {
                        if (!this.form.division) {
                            this.errors.division = 'Please select your division.';
                            valid = false;
                        }
                        if (!this.form.position) {
                            this.errors.position = 'Please select your position.';
                            valid = false;
                        }
                    }

                    if (stepNum === 3) {
                        if (!this.form.email.trim()) {
                            this.errors.email = 'Email address is required.';
                            valid = false;
                        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
                            this.errors.email = 'Please enter a valid email address.';
                            valid = false;
                        }
                        if (!this.form.password) {
                            this.errors.password = 'Password is required.';
                            valid = false;
                        } else if (this.getPasswordStrength() !== 'Strong') {
                            this.errors.password = 'Password must be Strong (at least 8 characters, one uppercase letter, and one number).';
                            valid = false;
                        }
                        if (this.form.password !== this.form.password_confirmation) {
                            this.errors.password_confirmation = 'Passwords do not match.';
                            valid = false;
                        }
                    }

                    return valid;
                },

                nextStep() {
                    if (this.validateStep(this.step)) {
                        this.step++;
                    }
                },

                prevStep() {
                    this.errors = {};
                    this.step--;
                },

                handleSubmit(event) {
                    if (!this.validateStep(3)) {
                        event.preventDefault();
                        return;
                    }

                    if (!this.consentAccepted) {
                        event.preventDefault();
                        this.submitting = false;
                        this.showConsentModal = true;
                        return;
                    }

                    this.submitting = true;
                },

                getPasswordStrength() {
                    const pw = this.form.password;
                    if (!pw) return '';

                    const hasLength = pw.length >= 8;
                    const hasUpper = /[A-Z]/.test(pw);
                    const hasNumber = /[0-9]/.test(pw);

                    if (hasLength && hasUpper && hasNumber) return 'Strong';
                    if (hasLength && (hasUpper || hasNumber)) return 'Fair';
                    return 'Weak';
                },

                getPasswordStrengthPercent() {
                    const s = this.getPasswordStrength();
                    if (s === 'Strong') return 100;
                    if (s === 'Fair') return 55;
                    return 25;
                },

                agreeConsent() {
                    this.consentAccepted = true;
                    this.showConsentModal = false;

                    if (this.step === 3 && this.validateStep(3)) {
                        this.submitting = true;

                        this.$nextTick(() => {
                            document.getElementById('registerForm').submit();
                        });
                    }
                }
            };
        }
    </script>

</body>
</html>
