# Authentication Systems

This document outlines the authentication mechanics, configurations, and implementation code examples for the **NoteHub** application. The system supports two distinct pipelines: a stateful session-based interface for the Web Portal and a stateless token-based system for the REST API.

---

## 1. Authentication Architecture Overview

NoteHub implements a hybrid authentication ecosystem:
*   **Web Portal Authentication:** Employs standard HTTP cookies and server-side session state managed by **Laravel Jetstream & Fortify**. Standard login, registration, password resets, and profile management use standard sessions.
*   **Social OAuth Authentication:** Enables secure third-party sign-in via Google accounts, integrated via **Laravel Socialite**.
*   **API Authentication:** Provides stateless access to REST endpoints via cryptographically signed access tokens managed by **Laravel Sanctum**.

---

## 2. Web Portal Authentication (Jetstream & Fortify)

The web portal uses session-based authentication. Fortify handles user verification on the backend while Jetstream provides the Livewire-based UI.

### 2.1 Configuration & Enabled Features
Fortify features are configured in the `config/fortify.php` file, which registers routes and controllers for session management.
*Location:* [config/fortify.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/config/fortify.php#L164-L178)
```php
'features' => [
    Features::registration(),                // Allows new standard users to register
    Features::resetPasswords(),              // Email-based password reset flows
    Features::updateProfileInformation(),    // Modifying name, email, phone number
    Features::updatePasswords(),             // Updating account password securely
    Features::twoFactorAuthentication([
        'confirm' => true,                   // Requires password confirmation to enable
        'confirmPassword' => true,           // Enforces re-entering password to change settings
    ]),
    Features::passkeys([
        'confirmPassword' => true,           // Support for passwordless WebAuthn keys
    ]),
],
```

### 2.2 New User Registration Flow
Registration collects name details, splits the input into first and last names, hashes the password, and logs the user in.
*Location:* [app/Actions/Fortify/CreateNewUser.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Actions/Fortify/CreateNewUser.php#L20-L34)
```php
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
        'password' => Hash::make($input['password']), // Secure Bcrypt hashing
    ]);
}
```

---

## 3. Social Sign-In (Google OAuth 2.0 via Socialite)

Google Social Sign-In acts as an alternative authentication method. If the user already has an account, it updates the Google tokens and logs them in. If they are new, it registers them using their Google profile details.

### 3.1 Google Authentication Controller
*Location:* [app/Http/Controllers/Auth/GoogleController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Auth/GoogleController.php#L28-L103)
```php
public function handleGoogleCallback(): RedirectResponse
{
    // Retrieve user details from Google
    $googleUser = Socialite::driver('google')->user();
    
    // Check if user already linked Google sign-in
    $user = User::where('google_id', $googleUser->getId())->first();

    if ($user) {
        if ($user->google_token !== $googleUser->token) {
            $user->update(['google_token' => $googleUser->token]);
        }
    } else {
        // Link to existing account or register new user
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
                'password' => Hash::make(Str::random(24)), // Generate secure random password
                'registered_date' => now(),
                'email_verified_at' => now(),
            ]);
        }
    }

    $user->update(['last_login_time' => now()]);
    Auth::login($user, true); // Create stateful web session

    return redirect()->intended('/dashboard');
}
```

---

## 4. Two-Factor Authentication (2FA)

To guard against compromised user passwords, NoteHub implements voluntary Two-Factor Authentication (2FA).

*   **Setup:** The user enables 2FA from their profile settings by confirming their password. The system displays a QR code containing a TOTP (Time-Based One-Time Password) secret.
*   **Verification:** The user scans this code using an authenticator app (e.g. Google Authenticator, Authy).
*   **Active Verification Flow:** During subsequent logins, Fortify intercepts the session redirect, detects the enabled 2FA state, and prompts the user to input the temporary 6-digit verification code before granting access to `/dashboard`.
*   **Recovery:** If the authenticator device is lost, the user can authenticate using one of the pre-generated recovery codes (which are encrypted in the database and hidden from standard model views via `$hidden` arrays).

---

## 5. API Token Authentication (Laravel Sanctum)

For stateless environments (e.g., API clients, mobile apps), users authenticate via **Sanctum API tokens** using the `/api/login` and `/api/register` endpoints.

### 5.1 Endpoint Registration
*Location:* [routes/api.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/routes/api.php#L57-L70)
```php
Route::middleware('api')->prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        // ... Resource routes
    });
});
```

### 5.2 Device Tracking & Token Issuance Example
When logging in via the API, the system records the client device details, operating system, and timestamp in the `devices` table, then responds with a plain-text Sanctum token.
*Location:* [app/Http/Controllers/Api/AuthController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/AuthController.php#L36-L70)
```php
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
        'device_name' => 'nullable|string'
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    // Capture and persist device metadata
    if ($request->device_name) {
        $user->devices()->create([
            'device_type' => $request->device_name,
            'os' => $request->header('User-Agent', 'unknown'),
            'last_accessed_time' => now(),
        ]);
        $user->update(['last_login_time' => now()]);
    }

    // Generate Token
    $token = $user->createToken($request->device_name ?? 'auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user
    ]);
}
```

### 5.3 Requesting Protected Resources
API clients must include the Bearer token in the `Authorization` header of all requests:
```http
GET /api/notes HTTP/1.1
Host: localhost:8000
Authorization: Bearer 3|aG81XmJ2T3Y1...
Accept: application/json
```

### 5.4 Token Revocation (Logout)
When a user logs out via the API, their current Sanctum token is deleted from the database, preventing any future access with that token.
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
