<?php

use App\Mail\OtpVerificationMail;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
});

test('registration sends OTP and stores verification record', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertDatabaseHas('otp_verifications', [
        'email' => 'test@example.com',
    ]);

    Mail::assertSent(OtpVerificationMail::class, function ($mail) {
        return $mail->hasTo('test@example.com');
    });
});

test('OTP verification page requires session email', function () {
    $response = $this->get(route('register.verify-otp'));

    $response->assertRedirect(route('register'));
});

test('OTP verification page can be rendered with session', function () {
    $response = $this->withSession(['otp_email' => 'test@example.com'])
        ->get(route('register.verify-otp'));

    $response->assertStatus(200);
    $response->assertSee('test@example.com');
});

test('valid OTP creates user and logs them in', function () {
    $otp = '123456';

    OtpVerification::create([
        'email' => 'test@example.com',
        'otp' => Hash::make($otp),
        'registration_data' => [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ],
        'expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->withSession(['otp_email' => 'test@example.com'])
        ->post(route('register.verify-otp.submit'), [
            'otp' => $otp,
        ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);

    $this->assertDatabaseMissing('otp_verifications', [
        'email' => 'test@example.com',
    ]);
});

test('invalid OTP returns error', function () {
    OtpVerification::create([
        'email' => 'test@example.com',
        'otp' => Hash::make('123456'),
        'registration_data' => [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ],
        'expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->withSession(['otp_email' => 'test@example.com'])
        ->post(route('register.verify-otp.submit'), [
            'otp' => '999999',
        ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('otp');
});

test('expired OTP returns error', function () {
    OtpVerification::create([
        'email' => 'test@example.com',
        'otp' => Hash::make('123456'),
        'registration_data' => [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ],
        'expires_at' => now()->subMinutes(1), // Already expired
    ]);

    $response = $this->withSession(['otp_email' => 'test@example.com'])
        ->post(route('register.verify-otp.submit'), [
            'otp' => '123456',
        ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('otp');
});

test('resend OTP sends a new code', function () {
    OtpVerification::create([
        'email' => 'test@example.com',
        'otp' => Hash::make('123456'),
        'registration_data' => [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ],
        'expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->withSession(['otp_email' => 'test@example.com'])
        ->post(route('register.resend-otp'));

    $response->assertSessionHas('status');

    Mail::assertSent(OtpVerificationMail::class, function ($mail) {
        return $mail->hasTo('test@example.com');
    });
});
