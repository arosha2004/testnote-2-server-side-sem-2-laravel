# Security Features of NoteHub

This document outlines the core security features currently implemented in your application. You can use these sections directly in your security documentation.

---

## 1. OAuth 2.0 Integration (Google Sign-In)
The application allows users to securely authenticate using their Google accounts via Laravel Socialite. This prevents users from having to manage another set of passwords and delegates authentication security to Google.

**Code Location:** `app/Http/Controllers/Auth/GoogleController.php`
```php
try {
    // Retrieve user details from Google
    $googleUser = Socialite::driver('google')->user();
} catch (InvalidStateException $e) {
    // Fallback to stateless on session mismatch issues
    $googleUser = Socialite::driver('google')->stateless()->user();
}
```

## 2. Secure Password Hashing
All user passwords stored in the database are securely hashed using strong cryptographic algorithms (Bcrypt). The system never stores plain-text passwords.

**Code Location:** `app/Http/Controllers/Api/AuthController.php`
```php
$user = User::create([
    ...User::parseFullName($request->name),
    'email' => $request->email,
    'password' => Hash::make($request->password), // Securely hashes the password
]);
```

## 3. Cross-Site Request Forgery (CSRF) Protection
Laravel automatically generates a CSRF "token" for each active user session. This token verifies that the authenticated user is the one actually making the requests to the application, protecting against malicious cross-site exploits.

**Code Location:** `resources/views/auth/login.blade.php`
```blade
<form method="POST" action="{{ route('login') }}">
    @csrf <!-- Automatically injects a hidden CSRF token field -->
    
    <div>
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
    </div>
</form>
```

## 4. Token-Based API Authentication (Laravel Sanctum)
For mobile and API requests, the system utilizes token-based authentication. When a user logs in, a secure token is generated that must be passed with all subsequent API requests. The token can be revoked at any time.

**Code Location:** `app/Http/Controllers/Api/AuthController.php`
```php
// Generate a secure access token bound to the device
$token = $user->createToken($request->device_name ?? 'auth_token')->plainTextToken;

return response()->json([
    'access_token' => $token,
    'token_type' => 'Bearer',
    'user' => $user
]);
```

## 5. Input Validation & Data Sanitization
Before any user data is processed or stored in the database, it must pass strict validation rules. This prevents bad data and malicious payloads from entering the system.

**Code Location:** `app/Http/Controllers/Api/AuthController.php`
```php
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:8|confirmed',
]);
```

## 6. Protection Against SQL Injection
By utilizing Laravel's Eloquent ORM and Query Builder, the application inherently uses PDO parameter binding. This completely protects the database from SQL injection vulnerabilities.

**Code Location:** `app/Http/Controllers/Api/AuthController.php`
```php
// The input is automatically parameterized and escaped, preventing SQL injection
$user = User::where('email', $request->email)->first();
```
