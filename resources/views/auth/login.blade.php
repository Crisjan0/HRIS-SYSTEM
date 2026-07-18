<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMW HRIS - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        <div class="bg-white rounded-2xl shadow-2xl p-10 w-full max-w-md">
            <div class="flex flex-col items-center mb-8">
              <img src="{{ asset('images/logo-DMW.png') }}" alt="HRIS Logo" class="h-24 w-auto object-contain">
            </div>  

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf
                
                <div class="mb-4">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                           placeholder="name@dmw.gov.ph"
                           pattern="^[^@\s]+@dmw\.gov\.ph$"
                           title="Invalid email. Please use your official dmw.gov.ph email address."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200 @error('email') border-red-500 @enderror"
                           required autofocus>
                    <span id="emailError" class="hidden text-red-500 text-xs mt-1">Invalid email. Please use your official dmw.gov.ph email address.</span>
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

               <div class="mb-6 relative">
                    <input type="password" name="password" id="password"
                           placeholder="Password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200"
                           required>
                
                    <button type="button" onclick="togglePassword('password', 'eyeIcon')"
                            class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                        <i id="eyeIcon" class="fas fa-eye-slash"></i>
                    </button>
                
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-blue-800 focus:ring-blue-800 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-[#1e3a8a] text-white font-bold py-3 rounded-lg hover:bg-blue-900 transition duration-300 shadow-lg">
                    SIGN IN
                </button>

                <div class="mt-8 text-center space-y-2">
                    <p class="text-gray-700 text-sm">
                        {{ __('Accounts are created by Human Resource personnel.') }}
                    </p>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-blue-500 text-sm hover:underline">
                        Forgot password?
                    </a>
                    @endif
                </div>

                <div class="mt-8 flex justify-center items-center gap-4">
                    <img src="https://www.gppb.gov.ph/wp-content/uploads/2023/07/Logo-Bagong-Pilipinas.png" 
                         alt="Bagong Pilipinas" class="h-10">
                    <img src="https://dmw.gov.ph/images/dmw_logo.png" 
                         alt="DMW Seal" class="h-10">
                </div>
            </form>
        </div>
    </div>

  <script>
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const officialEmailPattern = /^[^@\s]+@dmw\.gov\.ph$/;

    function validateEmailField(showWhenEmpty = false) {
        const value = emailInput.value.trim();
        const isValid = officialEmailPattern.test(value);
        const shouldShowError = value !== '' ? !isValid : showWhenEmpty;

        emailInput.classList.toggle('border-red-500', shouldShowError);
        emailInput.classList.toggle('focus:ring-red-500', shouldShowError);
        emailInput.classList.toggle('focus:ring-blue-800', !shouldShowError);
        emailError.classList.toggle('hidden', !shouldShowError);

        return isValid;
    }

    emailInput.addEventListener('input', () => validateEmailField(false));
    emailInput.addEventListener('blur', () => validateEmailField(false));
    loginForm.addEventListener('submit', (event) => {
        if (!validateEmailField(true)) {
            event.preventDefault();
            emailInput.focus();
        }
    });

    function togglePassword(inputId, iconId) {
        const password = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (password.type === "password") {
            password.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            password.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }

  </script>
    
</body>
</html>
