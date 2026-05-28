# NoteHub Security Features

This document outlines the core security architecture and features currently implemented in the NoteHub application. 

## Brief Overview
NoteHub is built on the robust Laravel framework, leveraging its built-in security mechanisms and first-party packages to ensure data integrity and user safety. The application implements a multi-layered security approach including:
- **Authentication & Identity:** Handled via Laravel Jetstream, Laravel Sanctum, and Socialite for secure login, API tokens, and Google OAuth.
- **Data Protection:** All passwords are mathematically hashed, and database interactions use PDO parameter binding to prevent SQL injection.
- **Session & Request Security:** Active defense mechanisms like CSRF token validation protect user sessions against cross-site attacks.

Below is the detailed evidence and code implementations for each security feature. Click the file links to open the exact file and line to take your screenshots!

---

## 1. Robust Application Scaffolding (Laravel Jetstream)
The application utilizes **Laravel Jetstream**, a robust application scaffolding that provides highly secure, pre-built authentication features including login, registration, email verification, two-factor authentication (2FA), and session management.

**Evidence of Implementation:**
Jetstream is officially installed and configured in the project's core dependencies.
*Location:* `composer.json` (Lines 11-16)
```json
"require": {
    "laravel/framework": "^12.0",
    "laravel/jetstream": "^5.5",
    "livewire/livewire": "^3.6.4"
}
```
*How it is applied:* Jetstream's session-based authentication is actively used to guard private web routes. Any request attempting to access the dashboard or notes must pass through Jetstream's session verification middleware.
*Code Location:* `routes/api.php` (Lines 14-18)
```php
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Protected routes like /dashboard and /notes
});
```

## 2. API Token Authentication (Laravel Sanctum)
For mobile devices, third-party clients, and internal API requests, the system utilizes **Laravel Sanctum**. Sanctum provides a featherweight authentication system for SPAs and simple APIs, issuing secure Bearer tokens that can be revoked at any time per device.

**Evidence of Implementation:**
Sanctum is installed and actively used to issue tokens upon successful login.
*Location:* `composer.json` (Line 13)
```json
"require": {
    "laravel/sanctum": "^4.0"
}
```
*Code Implementation:* `app/Http/Controllers/Api/AuthController.php` (Lines 63-69)
```php
// Generate a secure access token bound to the user's specific device
$token = $user->createToken($request->device_name ?? 'auth_token')->plainTextToken;

return response()->json([
    'access_token' => $token,
    'token_type' => 'Bearer',
    'user' => $user
]);
```
*How it is applied:* Sanctum intercepts all incoming API requests and verifies the provided Bearer token before allowing access to secure endpoints (like retrieving or modifying notes).
*Code Location:* `routes/api.php` (Lines 62-71)
```php
// Protects all API Resource routes (categories, notes, reminders)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('notes', NoteController::class);
});
```

## 3. OAuth 2.0 Integration (Google Sign-In)
The application allows users to securely authenticate using their Google accounts via **Laravel Socialite**. This delegates authentication security directly to Google, removing the risk of password fatigue and weak user-generated passwords.

**Evidence of Implementation:**
*Location:* `composer.json` (Line 14)
```json
"require": {
    "laravel/socialite": "^5.27"
}
```
*Code Implementation:* `app/Http/Controllers/Auth/GoogleController.php` (Lines 31-38)
```php
try {
    // Securely retrieve user details from Google OAuth provider
    $googleUser = Socialite::driver('google')->user();
} catch (InvalidStateException $e) {
    // Fallback to stateless on session mismatch issues to prevent errors
    $googleUser = Socialite::driver('google')->stateless()->user();
}
```

## 4. Secure Password Hashing
All user passwords stored in the database are securely hashed using strong cryptographic algorithms (Bcrypt). The system never stores plain-text passwords.

**Evidence of Implementation:**
*Code Location:* `app/Http/Controllers/Api/AuthController.php` (Lines 21-25)
```php
$user = User::create([
    ...User::parseFullName($request->name),
    'email' => $request->email,
    'password' => Hash::make($request->password), // Securely hashes the password before DB insertion
]);
```

## 5. Cross-Site Request Forgery (CSRF) Protection
Laravel automatically generates a CSRF "token" for each active user session. This token verifies that the authenticated user is the one actually making the requests to the application, protecting against malicious cross-site exploits.

**Evidence of Implementation:**
*Code Location:* `resources/views/auth/login.blade.php` (Lines 23-28)
```blade
<form method="POST" action="{{ route('login') }}">
    @csrf <!-- Automatically injects a hidden, cryptographically secure CSRF token field -->
    
    <div>
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
    </div>
</form>
```

## 6. Input Validation & Data Sanitization
Before any user data is processed or stored in the database, it must pass strict validation rules. This ensures only expected data formats enter the system, thwarting malicious payloads and XSS attempts.

**Evidence of Implementation:**
*Code Location:* `app/Http/Controllers/Api/AuthController.php` (Lines 15-19)
```php
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:8|confirmed',
]);
```

## 7. Protection Against SQL Injection
By utilizing Laravel's Eloquent ORM and Query Builder, the application inherently uses PDO parameter binding. This completely protects the database from SQL injection vulnerabilities because inputs are treated strictly as parameters, not executable code.

**Evidence of Implementation:**
*Code Location:* `app/Http/Controllers/Api/AuthController.php` (Line 44)
```php
// The input is automatically parameterized and escaped by Eloquent
$user = User::where('email', $request->email)->first();
```
