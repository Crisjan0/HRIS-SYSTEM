<?php

use App\Mail\OtpVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users are redirected to OTP verification after registration', function () {
    Mail::fake();

    $response = $this->post('/register', [
        'lastname' => 'Dela Cruz',
        'firstname' => 'Juan',
        'middlename' => 'Santos',
        'suffix' => 'Jr.',
        'division' => 'Finance and Administrative Division',
        'position' => 'EMPLOYEE',
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

    // Employee record should be created
    $this->assertDatabaseHas('employees', [
        'lastname' => 'Dela Cruz',
        'firstname' => 'Juan',
        'middlename' => 'Santos',
        'suffix' => 'Jr.',
        'division' => 'Finance and Administrative Division',
        'role' => 'EMPLOYEE',
    ]);

    Mail::assertSent(OtpVerificationMail::class);
});

test('registration fails without required personal information', function () {
    Mail::fake();

    $response = $this->post('/register', [
        'lastname' => '',
        'firstname' => '',
        'division' => 'Finance and Administrative Division',
        'position' => 'EMPLOYEE',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['lastname', 'firstname']);
    Mail::assertNotSent(OtpVerificationMail::class);
});

test('registration fails without division and position', function () {
    Mail::fake();

    $response = $this->post('/register', [
        'lastname' => 'Dela Cruz',
        'firstname' => 'Juan',
        'division' => '',
        'position' => '',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['division', 'position']);
    Mail::assertNotSent(OtpVerificationMail::class);
});

test('registration fails with invalid division', function () {
    Mail::fake();

    $response = $this->post('/register', [
        'lastname' => 'Dela Cruz',
        'firstname' => 'Juan',
        'division' => 'Invalid Division',
        'position' => 'EMPLOYEE',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['division']);
    Mail::assertNotSent(OtpVerificationMail::class);
});

test('OTP verification completes registration successfully', function () {
    Mail::fake();

    // Register first
    $this->post('/register', [
        'lastname' => 'Dela Cruz',
        'firstname' => 'Juan',
        'division' => 'Migrant Workers Processing Division',
        'position' => 'HRSTAFF',
        'email' => 'otp-test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'otp-test@example.com')->first();

    // Simulate knowing the OTP (set a known one)
    $otp = '123456';
    $user->update([
        'otp' => Hash::make($otp),
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->post(route('register.verify-otp.submit'), [
        'otp' => $otp,
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();

    $user->refresh();
    expect($user->email_verified_at)->not->toBeNull();
    expect($user->otp)->toBeNull();
});
