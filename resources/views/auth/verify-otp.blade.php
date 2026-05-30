<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMW HRIS - Verify Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .otp-input:focus {
            transform: scale(1.05);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(30, 58, 138, 0.4); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(30, 58, 138, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(30, 58, 138, 0); }
        }
        .icon-pulse {
            animation: pulse-ring 2s infinite;
        }
    </style>
</head>

<body class="relative min-h-screen flex items-center justify-center font-sans overflow-hidden">

    <div class="fixed inset-0 z-0">
       <img src="{{ asset('images/background-DMWHRIS.jpg') }}" class="w-full h-full object-cover"
             alt="Background">
    </div>

    <div class="relative z-10 container mx-auto flex flex-col lg:flex-row items-center justify-center gap-12 px-6">

        <div class="text-white max-w-lg text-center lg:text-left">
            <h1 class="text-5xl font-bold leading-tight">
                Department of <br> Migrant Workers
            </h1>
            <p class="mt-4 text-xl italic font-light opacity-90">
                Kagawaran ng Manggagawang Pandarayuhan
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-10 w-full max-w-md animate-fade-in-up">
            <div class="flex flex-col items-center mb-6">
              <img src="{{ asset('images/logo-DMW.png') }}" alt="HRIS Logo" class="h-20 w-auto object-contain">
            </div>

            {{-- Email icon with pulse --}}
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center icon-pulse">
                    <i class="fas fa-envelope-open-text text-[#1e3a8a] text-2xl"></i>
                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-800 text-center mb-2">Verify Your Email</h2>
            <p class="text-sm text-gray-500 text-center mb-6">
                We sent a 6-digit verification code to
                <br>
                <span class="font-semibold text-gray-700">{{ $email }}</span>
            </p>

            {{-- Status message (resend success) --}}
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-center">
                    <p class="text-green-700 text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i>{{ session('status') }}
                    </p>
                </div>
            @endif

            {{-- Error message --}}
            @error('otp')
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-center">
                    <p class="text-red-600 text-sm font-medium">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                </div>
            @enderror

            <form method="POST" action="{{ route('register.verify-otp.submit') }}" id="otpForm">
                @csrf

                {{-- OTP Input boxes --}}
                <div class="flex justify-center gap-2 mb-6" id="otpContainer">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text"
                               maxlength="1"
                               class="otp-input w-12 h-14 text-center text-xl font-bold border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-800 focus:border-blue-800 transition-all duration-200"
                               data-index="{{ $i }}"
                               inputmode="numeric"
                               pattern="[0-9]"
                               autocomplete="one-time-code">
                    @endfor
                </div>

                {{-- Hidden input to hold the full OTP value --}}
                <input type="hidden" name="otp" id="otpHidden">

                <button type="submit" id="verifyBtn"
                        class="w-full bg-[#1e3a8a] text-white font-bold py-3 rounded-lg hover:bg-blue-900 transition duration-300 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    <i class="fas fa-shield-alt mr-2"></i>VERIFY & CREATE ACCOUNT
                </button>
            </form>

            {{-- Resend & Back --}}
            <div class="mt-6 text-center space-y-3">
                <p class="text-gray-500 text-sm">
                    Didn't receive the code?
                </p>
                <form method="POST" action="{{ route('register.resend-otp') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="text-blue-700 font-semibold hover:underline text-sm">
                        <i class="fas fa-redo mr-1"></i>Resend Code
                    </button>
                </form>

                <div class="pt-2">
                    <a href="{{ route('register') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Registration
                    </a>
                </div>
            </div>

            <div class="mt-8 flex justify-center items-center gap-4">
                <img src="https://www.gppb.gov.ph/wp-content/uploads/2023/07/Logo-Bagong-Pilipinas.png"
                     alt="Bagong Pilipinas" class="h-10">
                <img src="https://dmw.gov.ph/images/dmw_logo.png"
                     alt="DMW Seal" class="h-10">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('otpHidden');
            const verifyBtn = document.getElementById('verifyBtn');
            const form = document.getElementById('otpForm');

            function updateHiddenInput() {
                let otp = '';
                inputs.forEach(input => otp += input.value);
                hiddenInput.value = otp;
                verifyBtn.disabled = otp.length !== 6;
            }

            inputs.forEach((input, index) => {
                // Only allow numeric input
                input.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');

                    if (this.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    updateHiddenInput();
                });

                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                        updateHiddenInput();
                    }
                });

                // Handle paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');

                    for (let i = 0; i < Math.min(pastedData.length, 6); i++) {
                        if (inputs[i]) {
                            inputs[i].value = pastedData[i];
                        }
                    }

                    // Focus the next empty input or the last one
                    const nextEmpty = Array.from(inputs).findIndex(inp => !inp.value);
                    if (nextEmpty !== -1) {
                        inputs[nextEmpty].focus();
                    } else {
                        inputs[inputs.length - 1].focus();
                    }

                    updateHiddenInput();
                });
            });

            // Auto-focus first input
            inputs[0].focus();
        });
    </script>

</body>
</html>
