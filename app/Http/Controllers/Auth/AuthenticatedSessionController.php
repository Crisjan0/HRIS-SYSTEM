<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OtpVerificationMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // If the user is not authenticated after authenticate(), it means
        // the account exists but is unverified — redirect to OTP verification.
        if (! Auth::check()) {
            $user = User::query()
                ->where('email', $request->string('email'))
                ->whereNull('email_verified_at')
                ->first();

            if ($user) {
                // Generate a fresh OTP and send it
                $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                $user->update([
                    'otp' => Hash::make($otp),
                    'otp_expires_at' => now()->addMinutes(10),
                ]);

                Mail::to($user->email)->send(new OtpVerificationMail($otp));

                session(['otp_user_id' => $user->id]);

                return redirect()->route('register.verify-otp')
                    ->with('status', 'Your account is not yet verified. A new verification code has been sent to your email.');
            }
        }

        $request->session()->regenerate();

        if ($request->user()?->must_change_password) {
            return redirect()->route('profile.edit')
                ->with('status', 'Please change your temporary password before continuing.');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
