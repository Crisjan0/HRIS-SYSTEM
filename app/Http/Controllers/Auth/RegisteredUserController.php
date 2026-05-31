<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\OtpVerificationMail;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
     * Creates the user (unverified) and employee record, generates OTP,
     * sends it via email, and redirects to OTP verification.
     *
     * @throws ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Generate a 6-digit OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Build the user display name
        $displayName = trim($validated['firstname'].' '.$validated['lastname']);

        // Create the user with OTP (unverified — email_verified_at is null)
        $user = User::create([
            'name' => $displayName,
            'email' => $validated['email'],
            'password' => $validated['password'],
            'otp' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(10),
            'privacy_consent' => true,
        ]);

        // Create the employee record linked to the user
        Employee::create([
            'lastname' => $validated['lastname'],
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'] ?? null,
            'suffix' => $validated['suffix'] ?? null,
            'division' => $validated['division'],
            'role' => $validated['position'],
            'user_id' => $user->id,
        ]);

        // Send OTP to the user's email
        Mail::to($validated['email'])->send(new OtpVerificationMail($otp));

        // Store user ID in session for the OTP verification page
        session(['otp_user_id' => $user->id]);

        return redirect()->route('register.verify-otp');
    }

    /**
     * Display the OTP verification view.
     */
    public function showOtpForm(): View|RedirectResponse
    {
        $user = $this->getOtpUser();

        if (! $user) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp', [
            'email' => $user->email,
        ]);
    }

    /**
     * Verify the OTP and activate the user account.
     *
     * @throws ValidationException
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = $this->getOtpUser();

        if (! $user) {
            return redirect()->route('register')
                ->withErrors(['otp' => 'Session expired. Please register again.']);
        }

        if ($user->isOtpExpired()) {
            return back()->withErrors(['otp' => 'Verification code has expired. Please resend.']);
        }

        if (! Hash::check($request->otp, $user->otp)) {
            return back()->withErrors(['otp' => 'Invalid verification code. Please try again.']);
        }

        // OTP is valid — verify the email but keep account pending HR approval
        $user->forceFill([
            'email_verified_at' => now(),
            'otp' => null,
            'otp_expires_at' => null,
        ])->save();

        session()->forget('otp_user_id');

        event(new Registered($user));

        return redirect()->route('login')
            ->with('status', 'Email verified successfully! Your account is now pending HR approval. You will be able to log in once approved.');
    }

    /**
     * Resend OTP to the same email.
     */
    public function resendOtp(): RedirectResponse
    {
        $user = $this->getOtpUser();

        if (! $user) {
            return redirect()->route('register');
        }

        // Generate a new OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new OtpVerificationMail($otp));

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    /**
     * Get the user who is currently verifying their OTP.
     */
    private function getOtpUser(): ?User
    {
        $userId = session('otp_user_id');

        if (! $userId) {
            return null;
        }

        return User::query()
            ->whereNull('email_verified_at')
            ->find($userId);
    }
}
