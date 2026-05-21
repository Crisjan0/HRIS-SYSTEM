<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMW HRIS - Register</title>
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

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-4">
                    <input type="text" name="name" value="{{ old('name') }}" 
                           placeholder="Full Name" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200"
                           required autofocus>
                    @error('name')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <input type="email" name="email" value="{{ old('email') }}" 
                           placeholder="Email address" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200"
                           required>
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

               <div class="mb-4 relative">
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

                <div class="mb-6 relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Confirm Password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200"
                           required>
                
                    <button type="button" onclick="togglePassword('password_confirmation', 'eyeIconConfirm')"
                            class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                        <i id="eyeIconConfirm" class="fas fa-eye-slash"></i>
                    </button>
                
                    <p id="password_match_message" class="text-sm mt-2 hidden"></p>
                    @error('password_confirmation')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-[#1e3a8a] text-white font-bold py-3 rounded-lg hover:bg-blue-900 transition duration-300 shadow-lg">
                    REGISTER
                </button>

                <div class="mt-8 text-center space-y-2">
                    <p class="text-gray-700 text-sm">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-blue-700 font-semibold hover:underline">Sign in here</a>
                    </p>
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

    function checkPasswordsMatch() {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const message = document.getElementById('password_match_message');
        
        if (confirm === '') {
            message.classList.add('hidden');
            return;
        }
        
        message.classList.remove('hidden');
        if (password === confirm) {
            message.innerHTML = 'Passwords match <span class=\"font-bold\">✓</span>';
            message.className = 'text-sm mt-2 text-[#42b72a] font-medium';
        } else {
            message.textContent = 'Passwords do not match';
            message.className = 'text-sm mt-2 text-[#ce1126] font-medium';
        }
    }
    
    document.getElementById('password').addEventListener('input', checkPasswordsMatch);
    document.getElementById('password_confirmation').addEventListener('input', checkPasswordsMatch);
  </script>
    
</body>
</html>
