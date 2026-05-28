# 13. External Libraries Used

This document outlines the third-party Laravel packages and external front-end libraries integrated into the **NoteHub** application. Each library is detailed with its specific role in the system, its configuration setup, and concrete code examples showing how it is used in the codebase.

---

## Table of Contents
1. [Laravel Jetstream](#1-laravel-jetstream) (Authentication Scaffolding)
2. [Laravel Sanctum](#2-laravel-sanctum) (API Token Authentication)
3. [Livewire](#3-livewire) (Reactive Front-End Logic)
4. [Laravel Socialite](#4-laravel-socialite) (Google OAuth Login)
5. [Tailwind CSS](#5-tailwind-css) (Responsive Custom Design)
6. [DOMPDF](#6-dompdf) (Document PDF Generation)

---

## 1. Laravel Jetstream

### Description
**Laravel Jetstream** provides the application's authentication scaffolding, including login, registration, email verification, two-factor authentication (2FA), session management, and optional API support. It utilizes Fortify as the stateless authentication backend and Livewire for the frontend interface.

### Dependency
*   `laravel/jetstream: ^5.5` in [composer.json](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/composer.json)

### Examples in Code

#### A. Stack Configuration
Jetstream is configured to use the **Livewire** stack and has account deletion enabled.
*Location:* [config/jetstream.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/config/jetstream.php#L19-L66)
```php
'stack' => 'livewire',

// Enabled features
'features' => [
    // Features::termsAndPrivacyPolicy(),
    // Features::profilePhotos(),
    // Features::api(),
    Features::accountDeletion(),
],
```

#### B. Fortify Custom User Creation Action
When Jetstream registers a new user, it calls the CreateNewUser action which parses full names into separate first and last name database fields.
*Location:* [app/Actions/Fortify/CreateNewUser.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Actions/Fortify/CreateNewUser.php#L45-L59)
```php
public function create(array $input): User
{
    Validator::make($input, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => $this->passwordRules(),
    ])->validate();

    return User::create([
        ...User::parseFullName($input['name']),
        'email' => $input['email'],
        'password' => Hash::make($input['password']),
    ]);
}
```

---

## 2. Laravel Sanctum

### Description
**Laravel Sanctum** provides a lightweight authentication system for single-page applications (SPAs), mobile applications, and simple, token-based APIs. In NoteHub, Sanctum issues cryptographically secure, revokable Bearer personal access tokens to clients accessing resources statelessly.

### Dependency
*   `laravel/sanctum: ^4.0` in [composer.json](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/composer.json)

### Examples in Code

#### A. User Model Integration
The user model includes the `HasApiTokens` trait, enabling the model to issue and manage personal access tokens.
*Location:* [app/Models/User.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/User.php#L10-L20)
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
   
}
```

#### B. API Token Generation on Login
When authenticating via the REST API, NoteHub validates credentials and returns a plain-text token.
*Location:* [app/Http/Controllers/Api/AuthController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/AuthController.php#L73-L96)
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

    $token = $user->createToken($request->device_name ?? 'auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user
    ]);
}
```

#### C. Sanctum API Middleware Route Protection
Stateless routes are grouped and protected under the `auth:sanctum` guard.
*Location:* [routes/api.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/routes/api.php#L55-L65)
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('notes', NoteController::class);
    Route::apiResource('reminders', ReminderController::class);
});
```

---

## 3. Livewire

### Description
**Laravel Livewire** is a full-stack framework that makes building dynamic, reactive interfaces simple without leaving the comfort of Laravel. It handles AJAX requests automatically under the hood to refresh sections of the page when data updates.

### Dependency
*   `livewire/livewire: ^3.6.4` in [composer.json](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/composer.json)

### Examples in Code

#### A. Interactive Livewire Component Logic
The `NoteManager` class handles property bindings, validation, search queries, pagination, and file attachments dynamically.
*Location:* [app/Livewire/NoteManager.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Livewire/NoteManager.php#L13-L73)
```php
class NoteManager extends Component
{
    use WithPagination, WithFileUploads;

    public $categories;
    public $title;
    public $content;
    public $category_id;
    public $note_id;
    public $isOpen = false;
    public $search = '';
    public $filterCategory = '';

    public function render()
    {
        $query = auth()->user()->notes()->with('categories');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('content', 'like', '%'.$this->search.'%');
            });
        }

        $notes = $query->orderBy('is_pinned', 'desc')->latest()->paginate(9);

        return view('livewire.note-manager', ['notes' => $notes]);
    }
}
```

#### B. Component Markup with Property Debouncing & Action Calls
The Blade view binds user inputs directly to Livewire properties and component methods via directives like `wire:model` and `wire:click`.
*Location:* [resources/views/livewire/note-manager.blade.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/resources/views/livewire/note-manager.blade.php#L15-L33)
```html
{{-- Property Debouncing for Live Search --}}
<input
    wire:model.live.debounce.300ms="search"
    type="text"
    placeholder="Search by title or content..."
    class="input-field pl-11"
>

{{-- Action Click Bindings --}}
<button wire:click="create()" class="btn-primary shrink-0">
    New Note
</button>
```

---

## 4. Laravel Socialite

### Description
**Laravel Socialite** provides an expressive, fluent interface to OAuth authentication with popular social networks. In NoteHub, Socialite is used exclusively to facilitate secure authentication with Google Identity Services.

### Dependency
*   `laravel/socialite: ^5.27` in [composer.json](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/composer.json)

### Examples in Code

#### A. Third-Party Service Credentials Setup
The Google client secret, ID, and redirect callback URI are mapped in the services configuration file.
*Location:* [config/services.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/config/services.php#L37-L41)
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

#### B. Socialite Redirect & Callback Flow
The `GoogleController` invokes Socialite to redirect the user to Google's authorization screen and subsequently parse profile details on callback.
*Location:* [app/Http/Controllers/Auth/GoogleController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Auth/GoogleController.php#L17-L48)
```php
/**
 * Redirect the user to the Google authentication page.
 */
public function redirectToGoogle(): RedirectResponse
{
    return Socialite::driver('google')->redirect();
}

/**
 * Obtain the user information from Google and authenticate them.
 */
public function handleGoogleCallback(): RedirectResponse
{
    try {
        $googleUser = Socialite::driver('google')->user();
    } catch (InvalidStateException $e) {
        // Fallback to stateless on localhost / state mismatch
        $googleUser = Socialite::driver('google')->stateless()->user();
    } catch (Exception $e) {
        return redirect()->route('login')->withErrors(['google' => 'Authentication failed.']);
    }
    
    // ... Process and authenticate user
}
```

---

## 5. Tailwind CSS

### Description
**Tailwind CSS** is a utility-first CSS framework packed with classes that can be composed directly in markup. In NoteHub, Tailwind CSS is used to design a premium, fully-responsive dashboard interface complete with a clean grid system, responsive navigation, and transitions.

### Dependency
*   `tailwindcss` configured via Vite in [package.json](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/package.json) and [vite.config.js](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/vite.config.js)

### Examples in Code

#### A. Tailwind Theme Extensions
Custom font families (`Outfit`) and theme color palettes (`brand`) are registered within the configuration file.
*Location:* [tailwind.config.js](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/tailwind.config.js#L14-L27)
```javascript
theme: {
    extend: {
        fontFamily: {
            sans: ['Outfit', ...defaultTheme.fontFamily.sans],
        },
        colors: {
            brand: {
                navy: '#0f2d5c',
                teal: '#0d9488',
                orange: '#f97316',
            },
        },
    },
},
```

#### B. Component Layer Directive Styling
To keep markup clean, Tailwind utility directives are grouped into custom class selectors using the `@apply` directive.
*Location:* [resources/css/app.css](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/resources/css/app.css#L37-L51)
```css
@layer components {
    .note-card {
        @apply flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition-all duration-300 hover:border-indigo-200 hover:shadow-lg;
    }

    .btn-primary {
        @apply inline-flex items-center justify-center gap-2 rounded-xl bg-brand-teal px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-brand-teal;
    }

    .input-field {
        @apply w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm transition focus:border-indigo-500 focus:bg-white focus:outline-none;
    }
}
```

---

## 6. DOMPDF

### Description
**DOMPDF** is an HTML to PDF converter. It parses HTML stylesheets, inline styles, and standard HTML structures to export dynamic documents. NoteHub utilizes `laravel-dompdf` to format and download notes.

### Dependency
*   `barryvdh/laravel-dompdf: ^3.1` in [composer.json](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/composer.json)

### Examples in Code

#### A. PDF Controller Method
The `exportPdf` action uses the `Pdf` facade to load a specific Blade template, passing it the note instance, and returning it as a downloadable stream.
*Location:* [app/Livewire/NoteManager.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Livewire/NoteManager.php#L219-L228)
```php
use Barryvdh\DomPDF\Facade\Pdf;

public function exportPdf($id)
{
    $note = auth()->user()->notes()->with('categories')->findOrFail($id);
    
    $pdf = Pdf::loadView('pdf.note', ['note' => $note]);
    
    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->stream();
    }, 'note-' . $note->id . '.pdf');
}
```

#### B. HTML PDF Blade Template
A clean, minimal HTML template with standard styling is used to compile the PDF layout.
*Location:* [resources/views/pdf/note.blade.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/resources/views/pdf/note.blade.php)
```html
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $note->title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .category {
            display: inline-block;
            background: #eef2ff;
            color: #4f46e5;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($note->categories->isNotEmpty())
            <div class="category">{{ $note->categories->pluck('category_name')->join(', ') }}</div>
        @endif
        <h1>{{ $note->title }}</h1>
    </div>
    <div class="content">
        {{ $note->content }}
    </div>
</body>
</html>
```
