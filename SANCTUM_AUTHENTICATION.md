# 9. Laravel Sanctum API Authentication

Laravel Sanctum was implemented to secure the NoteHub API, providing token-based authentication for mobile apps, single-page applications (SPAs), and third-party integrations.

---

## Sanctum Features Used

### 1. Personal Access Tokens
Tokens are stored in the database as cryptographically-hashed values. The User model uses the `HasApiTokens` trait to handle token operations.
*User Model Trait:* [app/Models/User.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/User.php#L13)
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens; // Enables token operations (createToken, tokens,)
    
```
*Token Database Schema:* [database/migrations/2026_05_14_153233_create_personal_access_tokens_table.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/database/migrations/2026_05_14_153233_create_personal_access_tokens_table.php#L14-L23)
```php
Schema::create('personal_access_tokens', function (Blueprint $table) {
    $table->id();
    $table->morphs('tokenable'); // Links the token to the User model
    $table->text('name');         // Friendly name for the client device
    $table->string('token', 64)->unique(); // Hashed token value
    $table->text('abilities')->nullable();
    $table->timestamp('last_used_at')->nullable();
    $table->timestamp('expires_at')->nullable()->index();
    $table->timestamps();
});
```

### 2. Token-based Authentication
Credentials check and token generation is handled in the `AuthController`.
*Token Generation:* [app/Http/Controllers/Api/AuthController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/AuthController.php#L63)
```php
$token = $user->createToken($request->device_name ?? 'auth_token')->plainTextToken;
```

### 3. Secure API Requests
When making requests to protected resources, the client must include the generated token in the HTTP `Authorization` header as a `Bearer` token. Below are real-world HTTP request/response examples based on the NoteHub project.

#### Example A: Fetching User Notes (`GET /api/notes`)
This secure request retrieves the notes owned by the authenticated user.

*   **cURL Command:**
    ```bash
    curl -X GET "http://127.0.0.1:8000/api/notes" \
      -H "Authorization: Bearer 3|aG81XmJ2T3Y1..." \
      -H "Accept: application/json"
    ```

*   **Standard HTTP Request Headers:**
    ```http
    GET /api/notes HTTP/1.1
    Host: 127.0.0.1:8000
    Authorization: Bearer 3|aG81XmJ2T3Y1...
    Accept: application/json
    ```

*   **Success Response (`200 OK`):**
    ```json
    {
      "current_page": 1,
      "data": [
        {
          "id": 12,
          "user_id": 4,
          "title": "AWS Server Credentials",
          "content": "Deploy IP address mapping using nip.io wildcard domains.",
          "is_pinned": true,
          "attachment": null,
          "created_at": "2026-05-28T07:22:15.000000Z",
          "updated_at": "2026-05-28T07:22:15.000000Z",
          "categories": [
            {
              "id": 2,
              "category_name": "DevOps",
              "pivot": {
                "note_id": 12,
                "category_id": 2,
                "description": null
              }
            }
          ]
        }
      ],
      "first_page_url": "http://127.0.0.1:8000/api/notes?page=1",
      "from": 1,
      "last_page": 1,
      "last_page_url": "http://127.0.0.1:8000/api/notes?page=1",
      "next_page_url": null,
      "path": "http://127.0.0.1:8000/api/notes",
      "per_page": 10,
      "prev_page_url": null,
      "to": 1,
      "total": 1
    }
    ```

#### Example B: Creating a New Note (`POST /api/notes`)
This secure request inserts a new note and links it to a category.

*   **cURL Command:**
    ```bash
    curl -X POST "http://127.0.0.1:8000/api/notes" \
      -H "Authorization: Bearer 3|aG81XmJ2T3Y1..." \
      -H "Content-Type: application/json" \
      -H "Accept: application/json" \
      -d '{"title": "NoteHub API Design", "content": "Creating clean documentation files for Laravel Sanctum.", "category_id": 1}'
    ```

*   **Standard HTTP Request Payload:**
    ```http
    POST /api/notes HTTP/1.1
    Host: 127.0.0.1:8000
    Authorization: Bearer 3|aG81XmJ2T3Y1...
    Content-Type: application/json
    Accept: application/json

    {
      "title": "NoteHub API Design",
      "content": "Creating clean documentation files for Laravel Sanctum.",
      "category_id": 1
    }
    ```

*   **Success Response (`201 Created`):**
    ```json
    {
      "id": 13,
      "user_id": 4,
      "title": "NoteHub API Design",
      "content": "Creating clean documentation files for Laravel Sanctum.",
      "is_pinned": false,
      "attachment": null,
      "created_at": "2026-05-28T07:31:02.000000Z",
      "updated_at": "2026-05-28T07:31:02.000000Z",
      "categories": [
        {
          "id": 1,
          "category_name": "Documentation",
          "pivot": {
            "note_id": 13,
            "category_id": 1,
            "description": null
          }
        }
      ]
    }
    ```


### 4. Token Validation
Laravel's Sanctum guard automatically intercepts the incoming request, decrypts/hashes the Bearer token, verifies its signature against the `personal_access_tokens` table, and resolves the associated `User` model.

### 5. API Route Protection
Routes are placed inside a group protected by the `auth:sanctum` middleware guard.
*Route Protection Group:* [routes/api.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/routes/api.php#L62-L71)
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

## API Authentication Flow

```mermaid
sequenceDiagram
    autonumber
    actor Client as Client App (Mobile/SPA)
    participant Server as Laravel Sanctum API
    database DB as MySQL Database

    Client->>Server: 1. POST /api/login (email, password, device_name)
    Server->>DB: Validate credentials & check User
    DB-->>Server: User verified
    Server->>DB: 2. Insert secure token details in personal_access_tokens
    DB-->>Server: Token record saved
    Server-->>Client: Return plainTextToken (Bearer token)
    Note over Client: 3. Token is stored securely on the client (e.g. LocalStorage)
    
    Client->>Server: 4. GET /api/notes (With Authorization: Bearer Header)
    Server->>DB: 5. Protected routes validate the token signature & check status
    DB-->>Server: Token valid & matches User
    Server-->>Client: Return secure JSON resource list (200 OK)
```

### 1. User logs in
The user posts their credentials to the `/api/login` endpoint.

*   **Route Route Configuration:** [routes/api.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/routes/api.php#L60)
    ```php
    Route::post('/login', [AuthController::class, 'login']);
    ```
*   **Controller Method:** [app/Http/Controllers/Api/AuthController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/AuthController.php#L36-L51)
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
    ```

### 2. Sanctum generates a token
If credentials verify, a unique plain-text token bound to the user profile is compiled.

*   **Token Creation Action:** [app/Http/Controllers/Api/AuthController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/AuthController.php#L63-L69)
    ```php
        // Token is compiled and returned to the client
        $token = $user->createToken($request->device_name ?? 'auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
    ```

### 3. Token is stored on the client
The API returns a JSON response containing the `access_token`. The client application captures and saves this token locally (e.g., using `localStorage.setItem('auth_token', token)` in Web SPAs or Keychain/SecureStore in iOS/Android apps) to maintain the login session.

### 4. Token is sent in API requests
For every request targeting secure resources (like fetching notes), the client attaches the Bearer token in the HTTP `Authorization` header.

*   **Secure API Endpoint Registration:** [routes/api.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/routes/api.php#L69)
    ```php
    Route::apiResource('notes', NoteController::class);
    ```
*   **Example Client Request Integration:**
    ```javascript
    // Example JavaScript Fetch Request
    fetch('/api/notes', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
            'Accept': 'application/json'
        }
    })
    ```

### 5. Protected API routes validate the token
The request is routed through the `auth:sanctum` guard. If valid, Sanctum binds the identified user instance to the request context.

*   **Middleware Verification Guard:** [routes/api.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/routes/api.php#L62-L66)
    ```php
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user(); // Resolves verified user
        });
    ```
*   **Controller Action Resolving the Resource:** [app/Http/Controllers/Api/NoteController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/NoteController.php#L11-L16)
    ```php
    public function index(Request $request)
    {
        $notes = $request->user()->notes()->with('categories')->latest()->paginate(10);

        return response()->json($notes);
    }
    ```

