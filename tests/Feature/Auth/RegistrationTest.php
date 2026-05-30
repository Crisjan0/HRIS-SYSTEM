<?php

use App\Mail\OtpVerificationMail;
use Illuminate\Support\Facades\Mail;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users are redirected to OTP verification after registration', function () {
    Mail::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('register.verify-otp'));
    $this->assertGuest();

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);

    // User should be unverified
    $this->assertDatabaseMissing('users', [
        'email' => 'test@example.com',
        'email_verified_at' => now(),
    ]);

    Mail::assertSent(OtpVerificationMail::class);
});
