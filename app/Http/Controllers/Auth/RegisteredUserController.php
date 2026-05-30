<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle the initial registration form submission.
     * Validates input, generates OTP, sends it via email, and redirects to OTP verification.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate a 6-digit OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Remove any previous OTPs for this email
        OtpVerification::query()->where('email', $request->email)->delete();

        // Store the OTP and registration data
        OtpVerification::create([
            'email' => $request->email,
            'otp' => Hash::make($otp),
            'registration_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ],
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP to the user's email
        Mail::to($request->email)->send(new OtpVerificationMail($otp));

        // Store email in session for the OTP verification page
        session(['otp_email' => $request->email]);

        return redirect()->route('register.verify-otp');
    }

    /**
     * Display the OTP verification view.
     */
    public function showOtpForm(): View|RedirectResponse
    {
        if (! session('otp_email')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp', [
            'email' => session('otp_email'),
        ]);
    }

    /**
     * Verify the OTP and create the user account.
     *
     * @throws ValidationException
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('register')
                ->withErrors(['otp' => 'Session expired. Please register again.']);
        }

        $otpRecord = OtpVerification::query()
            ->where('email', $email)
            ->latest()
            ->first();

        if (! $otpRecord) {
            return redirect()->route('register')
                ->withErrors(['otp' => 'No verification code found. Please register again.']);
        }

        if ($otpRecord->isExpired()) {
            $otpRecord->delete();

            return back()->withErrors(['otp' => 'Verification code has expired. Please resend.']);
        }

        if (! Hash::check($request->otp, $otpRecord->otp)) {
            return back()->withErrors(['otp' => 'Invalid verification code. Please try again.']);
        }

        // OTP is valid — create the user
        $data = $otpRecord->registration_data;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Clean up
        $otpRecord->delete();
        session()->forget('otp_email');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Resend OTP to the same email.
     */
    public function resendOtp(): RedirectResponse
    {
        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('register');
        }

        $otpRecord = OtpVerification::query()
            ->where('email', $email)
            ->latest()
            ->first();

        if (! $otpRecord) {
            return redirect()->route('register')
                ->withErrors(['otp' => 'Session expired. Please register again.']);
        }

        // Generate a new OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otpRecord->update([
            'otp' => Hash::make($otp),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($email)->send(new OtpVerificationMail($otp));

        return back()->with('status', 'A new verification code has been sent to your email.');
    }
}
