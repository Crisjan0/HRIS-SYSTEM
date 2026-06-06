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

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-4">
                    <input type="email" name="email" value="{{ old('email') }}" 
                           placeholder="Email address" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-800 transition duration-200"
                           required autofocus>
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
                        Don't have an account yet? 
                        <a href="#" data-consent-href="{{ route('register.consent') }}" class="text-blue-700 font-semibold hover:underline" id="registerLink">Register here</a>
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

    <div id="privacyModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/50" id="privacyBackdrop"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <h3 class="text-lg font-bold text-gray-800">Data Privacy Act of 2012 Consent</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" id="privacyClose">
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
                        id="privacyCancel">
                    Cancel
                </button>
                <button type="button"
                        class="px-4 py-2.5 text-sm font-bold text-white bg-[#1e3a8a] rounded-lg hover:bg-blue-900"
                        id="privacyAgree">
                    Agree
                </button>
            </div>
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

    const registerLink = document.getElementById('registerLink');
    const privacyModal = document.getElementById('privacyModal');
    const privacyBackdrop = document.getElementById('privacyBackdrop');
    const privacyClose = document.getElementById('privacyClose');
    const privacyCancel = document.getElementById('privacyCancel');
    const privacyAgree = document.getElementById('privacyAgree');

    function openPrivacyModal(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        privacyModal.classList.remove('hidden');
        privacyModal.classList.add('flex');
    }

    function closePrivacyModal() {
        privacyModal.classList.add('hidden');
        privacyModal.classList.remove('flex');
    }

    registerLink.addEventListener('click', openPrivacyModal);
    privacyBackdrop.addEventListener('click', closePrivacyModal);
    privacyClose.addEventListener('click', closePrivacyModal);
    privacyCancel.addEventListener('click', closePrivacyModal);
    privacyAgree.addEventListener('click', () => {
        window.location.href = registerLink.getAttribute('data-consent-href');
    });
  </script>
    
</body>
</html>