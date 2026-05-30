<?php

use App\Mail\OtpVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
});

test('registration creates unverified user with OTP and sends email', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::query()->where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->email_verified_at)->toBeNull()
        ->and($user->otp)->not->toBeNull()
        ->and($user->otp_expires_at)->not->toBeNull();

    Mail::assertSent(OtpVerificationMail::class, function ($mail) {
        return $mail->hasTo('test@example.com');
    });
});

test('OTP verification page requires session', function () {
    $response = $this->get(route('register.verify-otp'));

    $response->assertRedirect(route('register'));
});

test('OTP verification page can be rendered with session', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
        'otp' => Hash::make('123456'),
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->withSession(['otp_user_id' => $user->id])
        ->get(route('register.verify-otp'));

    $response->assertStatus(200);
    $response->assertSee($user->email);
});

test('valid OTP verifies user and logs them in', function () {
    $otp = '123456';

    $user = User::factory()->create([
        'email_verified_at' => null,
        'otp' => Hash::make($otp),
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->withSession(['otp_user_id' => $user->id])
        ->post(route('register.verify-otp.submit'), [
            'otp' => $otp,
        ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $user->refresh();
    expect($user->email_verified_at)->not->toBeNull()
        ->and($user->otp)->toBeNull()
        ->and($user->otp_expires_at)->toBeNull();
});

test('invalid OTP returns error', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
        'otp' => Hash::make('123456'),
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->withSession(['otp_user_id' => $user->id])
        ->post(route('register.verify-otp.submit'), [
            'otp' => '999999',
        ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('otp');
});

test('expired OTP returns error', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
        'otp' => Hash::make('123456'),
        'otp_expires_at' => now()->subMinutes(1),
    ]);

    $response = $this->withSession(['otp_user_id' => $user->id])
        ->post(route('register.verify-otp.submit'), [
            'otp' => '123456',
        ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('otp');
});

test('resend OTP sends a new code', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
        'otp' => Hash::make('123456'),
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->withSession(['otp_user_id' => $user->id])
        ->post(route('register.resend-otp'));

    $response->assertSessionHas('status');

    Mail::assertSent(OtpVerificationMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});
