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
*Code Implementation:* `app/Http/Controllers/Api/AuthController.php` (Lines 36-70)
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

    // Record device login if requested (Multi Device Tracking)
    if ($request->device_name) {
        $user->devices()->create([
            'device_type' => $request->device_name,
            'os' => $request->header('User-Agent', 'unknown'),
            'last_accessed_time' => now(),
        ]);

        $user->update(['last_login_time' => now()]);
    }

    // Generate a secure access token bound to the user's specific device
    $token = $user->createToken($request->device_name ?? 'auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user
    ]);
}
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
*Code Implementation:* `app/Http/Controllers/Auth/GoogleController.php` (Lines 28-103)
```php
public function handleGoogleCallback(): RedirectResponse
{
    try {
        // Retrieve user details from Google
        $googleUser = Socialite::driver('google')->user();
    } catch (InvalidStateException $e) {
        // Fallback to stateless on localhost or session mismatch issues
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

    // Check if a user with this google_id already exists
    $user = User::where('google_id', $googleUser->getId())->first();

    if ($user) {
        // Update token if it changed
        if ($user->google_token !== $googleUser->token) {
            $user->update([
                'google_token' => $googleUser->token,
            ]);
        }
    } else {
        // Check if user exists with the same email address
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Link Google account to existing email
            $user->update([
                'google_id' => $googleUser->getId(),
                'google_token' => $googleUser->token,
            ]);
        } else {
            // Register a new user
            $nameParts = User::parseFullName($googleUser->getName());

            $user = User::create([
                'first_name' => $nameParts['first_name'] ?: 'Google',
                'last_name' => $nameParts['last_name'] ?: 'User',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'google_token' => $googleUser->token,
                // Secure random hash since Google handles the real password
                'password' => Hash::make(Str::random(24)),
                'registered_date' => now(),
                'email_verified_at' => now(),
            ]);
        }
    }

    // Set last login time
    $user->update([
        'last_login_time' => now(),
    ]);

    // Log the user in
    Auth::login($user, true);

    // Redirect to intended location or dashboard
    return redirect()->intended('/dashboard');
}
```

## 4. Secure Password Hashing
All user passwords stored in the database are securely hashed using strong cryptographic algorithms (Bcrypt). The system never stores plain-text passwords.

**Evidence of Implementation:**
*Code Location:* `app/Actions/Fortify/CreateNewUser.php` (Lines 20-34)
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
        'password' => Hash::make($input['password']), // Securely hashes the password before DB insertion
    ]);
}
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
*Code Location:* `app/Http/Controllers/Api/AuthController.php` (Lines 13-34)
```php
public function register(Request $request)
{
    // Strict input validation rules
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        ...User::parseFullName($request->name),
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user
    ], 201);
}
```

## 7. Protection Against SQL Injection
By utilizing Laravel's Eloquent ORM and Query Builder, the application inherently uses PDO parameter binding. This completely protects the database from SQL injection vulnerabilities because inputs are treated strictly as parameters, not executable code.

**Evidence of Implementation:**
*Code Location:* `app/Http/Controllers/Api/AuthController.php` (Lines 36-50)
```php
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
        'device_name' => 'nullable|string'
    ]);

    // The input is automatically parameterized and escaped by Eloquent
    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
    // ...
```

## 8. Two-Factor Authentication (2FA)
The application provides optional Two-Factor Authentication (2FA) for all users, adding an extra layer of security beyond just a password. When enabled, users must scan a secure QR code using an authenticator app (like Google Authenticator or Authy) and enter a time-based one-time password (TOTP) during login. 

**Evidence of Implementation:**
*Location:* `config/fortify.php`
```php
Features::twoFactorAuthentication([
    'confirm' => true,
    'confirmPassword' => true,
]),
```
*How it is applied:* Once enabled in their profile settings, the authentication system natively handles the 2FA flow. It temporarily suspends the login process after the password is verified and requires the user to input their secure authenticator code or backup recovery code before granting access.

## 9. Role-Based Access Control (RBAC) & Admin Security
The application distinguishes between standard users (`user`) and administrators (`admin`). Administrative routes and API endpoints are guarded by a custom `AdminMiddleware` which verifies the user's role before processing requests.

**Evidence of Implementation:**
*Location:* [app/Http/Middleware/AdminMiddleware.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Middleware/AdminMiddleware.php)
```php
public function handle(Request $request, Closure $next): Response
{
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    abort(403, 'Unauthorized. Admin access required.');
}
```
*Web Route Middleware Registration:* [routes/api.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/routes/api.php#L45-L54)
```php
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin', // Admin restriction
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin');
    })->name('dashboard');
});
```
*API Route Middleware Registration:* [routes/api.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/routes/api.php#L73-L81)
```php
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/stats', [AdminController::class, 'stats']);
    Route::get('/users', [AdminController::class, 'users']);
    // ...
});
```

## 10. Prevention of Insecure Direct Object Reference (IDOR)
To prevent unauthorized data access, the application scopes database operations to the authenticated user. Even if a user attempts to access a note or reminder using an guessed ID via direct URL manipulation or API hacking, Eloquent relationships ensure they only fetch items owned by them.

**Evidence of Implementation:**
*Location:* [app/Http/Controllers/Api/NoteController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/NoteController.php#L11-L16)
```php
public function index(Request $request)
{
    // Retrieve only notes owned by the logged-in user
    $notes = $request->user()->notes()->with('categories')->latest()->paginate(10);

    return response()->json($notes);
}
```
*Location:* [app/Http/Controllers/Api/ReminderController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/ReminderController.php#L29-L30)
```php
// Fails with 404 ModelNotFoundException if the note does not belong to the user
$note = $request->user()->notes()->findOrFail($request->note_id);
```

## 11. Soft Deletes & Recycle Bin Protection
Accidental deletion can lead to permanent data loss. The application implements Soft Deletes for the `Note` model. This writes a `deleted_at` timestamp in the database instead of permanently purging the record.

**Evidence of Implementation:**
*Location:* [app/Models/Note.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/Note.php#L9-L11)
```php
class Note extends Model
{
    use HasFactory, SoftDeletes;
```
*How it is applied:* Standard queries exclude soft-deleted notes automatically. Trashed notes are sent to a dedicated Recycle Bin page (`trash` view) managed by `TrashManager.php` where the user can choose to either restore the note or permanently purge it from the system.

