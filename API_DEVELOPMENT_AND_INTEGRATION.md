# 12. API Development and Integration

The project includes RESTful API functionality.

---

## API Features

*   **API authentication using Sanctum:** Secure authentication pipeline using Laravel Sanctum's personal access tokens.
*   **JSON responses:** All endpoints return uniform, standard JSON payloads with appropriate HTTP status codes.
*   **Protected endpoints:** Core endpoints are guarded by the `auth:sanctum` middleware to ensure only authorized users can access resources.
*   **CRUD operations through API:** Full Create, Read, Update, and Delete operations for notes, categories, and reminders.
*   **Validation handling:** All requests undergo strict validation rules before being processed, returning descriptive validation errors when input conditions are not met.
*   **Token-based authorization:** Access control based on active bearer tokens rather than persistent sessions, supporting multi-device states.

---

## Example API Endpoints

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| **POST** | `/api/login` | User login |
| **POST** | `/api/register` | User registration |
| **GET** | `/api/notes` | Get all notes |
| **POST** | `/api/notes` | Create note |
| **PUT** | `/api/notes/{id}` | Update note |
| **DELETE** | `/api/notes/{id}` | Delete note |

---

## API Response Example

### Login Response (`POST /api/login`)

```json
{
  "message": "Login successful",
  "token": "generated_token"
}
```

---

## Technical Implementation Details

### 1. Endpoint Configuration
The API routes are registered under the `api` middleware group, guaranteeing stateless processing.
*Location:* [routes/api.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/routes/api.php#L58-L83)

```php
Route::middleware('api')->prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('notes', NoteController::class);
        Route::apiResource('reminders', ReminderController::class);
    });
});
```

### 2. Authentication Logic
Tokens are generated during user registration or login, registering active devices dynamically.
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

    $token = $user->createToken($request->device_name ?? 'auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user
    ]);
}
```

### 3. CRUD Note Implementation
Access control is enforced at the controller level to verify note ownership before operations occur.
*Location:* [app/Http/Controllers/Api/NoteController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/NoteController.php#L44-L60)

```php
public function show(Request $request, Note $note)
{
    if ($note->user_id !== $request->user()->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $note->load(['categories', 'versions', 'reminders']);

    return response()->json($note);
}
```
