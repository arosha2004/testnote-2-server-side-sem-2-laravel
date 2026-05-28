# Authentication System Features

This document provides the core code examples and implementations for the authentication features in NoteHub.

---

## Table of Contents
1. [User Registration](#1-user-registration)
2. [Login System](#2-login-system)
3. [Logout System](#3-logout-system)
4. [Password Hashing](#4-password-hashing)
5. [Cross-Site Request Forgery (CSRF) Protection](#5-cross-site-request-forgery-csrf-protection)
6. [Password Reset](#6-password-reset)
7. [Session Management](#7-session-management)
8. [Middleware Route Protection](#8-middleware-route-protection)
9. [Email Verification](#9-email-verification)
10. [Secure Authentication Flow](#10-secure-authentication-flow)

---

## 1. User Registration
User registration validates input parameters, splits the full name into first and last name components, and securely saves the user to the database.

*Location:* [app/Actions/Fortify/CreateNewUser.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Actions/Fortify/CreateNewUser.php)
```php
<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            ...User::parseFullName($input['name']),
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
```

---

## 2. Login System
Authentication is natively handled using Google OAuth 2.0 (via Laravel Socialite), allowing seamless user sign-in.

*Location:* [app/Http/Controllers/Auth/GoogleController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Auth/GoogleController.php#L28-L103)
```php
public function handleGoogleCallback(): RedirectResponse
{
    try {
        $googleUser = Socialite::driver('google')->user();
    } catch (InvalidStateException $e) {
        try {
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $driver = Socialite::driver('google');
            $googleUser = $driver->stateless()->user();
        } catch (Exception $ex) {
            return redirect()->route('login')->withErrors([
                'google' => 'Authentication failed. Please try again.',
            ]);
        }
    } catch (Exception $e) {
        return redirect()->route('login')->withErrors([
            'google' => 'Unable to authenticate with Google. Error: ' . $e->getMessage(),
        ]);
    }

    if (!$googleUser || !$googleUser->getEmail()) {
        return redirect()->route('login')->withErrors([
            'google' => 'Failed to retrieve email address from your Google Account.',
        ]);
    }

    $user = User::where('google_id', $googleUser->getId())->first();

    if ($user) {
        if ($user->google_token !== $googleUser->token) {
            $user->update([
                'google_token' => $googleUser->token,
            ]);
        }
    } else {
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'google_token' => $googleUser->token,
            ]);
        } else {
            $nameParts = User::parseFullName($googleUser->getName());

            $user = User::create([
                'first_name' => $nameParts['first_name'] ?: 'Google',
                'last_name' => $nameParts['last_name'] ?: 'User',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'google_token' => $googleUser->token,
                'password' => Hash::make(Str::random(24)),
                'registered_date' => now(),
                'email_verified_at' => now(),
            ]);
        }
    }

    $user->update([
        'last_login_time' => now(),
    ]);

    Auth::login($user, true);

    return redirect()->intended('/dashboard');
}
```

---

## 3. Logout System
When a user logs out, their current session state or access token is deleted.

*Location:* [app/Http/Controllers/Api/AuthController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/AuthController.php#L72-L79)
```php
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Successfully logged out'
    ]);
}
```

---

## 4. Password Hashing
All user passwords are automatically hashed using Bcrypt before database storage via Model-level casting.

*Location:* [app/Models/User.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/User.php#L139-L147)
```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'registered_date' => 'datetime',
        'last_login_time' => 'datetime',
        'password' => 'hashed', // Automatically encrypts passwords upon saving
    ];
}
```

---

## 5. Cross-Site Request Forgery (CSRF) Protection
Laravel generates a secure CSRF token for each active session to ensure that the authenticated user is the one initiating requests. All standard HTML forms must include this token.

*Location:* [resources/views/auth/login.blade.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/resources/views/auth/login.blade.php#L23-L31)
```html
<!-- The form contains the @csrf blade directive, which renders a hidden input field -->
<form method="POST" action="{{ route('login') }}" class="w-full">
    @csrf

    <!-- Email Input -->
    <div class="mb-4">
        <input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="Email">
    </div>
```

---

## 6. Password Reset
NoteHub provides password reset functionality allowing users to securely update their passwords using temporary tokens.

*Location:* [app/Actions/Fortify/ResetUserPassword.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Actions/Fortify/ResetUserPassword.php)
```php
<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
```

---

## 7. Session Management
Sessions are tracked in the database to enable multi-device auditing and prevent session hijacking.

*Location:* [.env](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/.env#L30)
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

---

## 8. Middleware Route Protection
Unauthenticated clients are blocked from accessing secure views using middleware groups.

*Location:* [routes/api.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/routes/api.php#L12-L18)
```php
Route::middleware('web')->group(function () {
    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
```

---

## 9. Email Verification
Users must verify their email addresses to unlock note management. Google OAuth registrations are verified automatically.

*Location:* [app/Http/Controllers/Auth/GoogleController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Auth/GoogleController.php#L88)
```php
'email_verified_at' => now(), // Verifies emails upon Google callback
```

---

## 10. Secure Authentication Flow
Multi-Factor Authentication (2FA) is configured to provide an extra layer of security.

*Location:* [config/fortify.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/config/fortify.php#L170-L174)
```php
Features::twoFactorAuthentication([
    'confirm' => true,
    'confirmPassword' => true,
]),
```
