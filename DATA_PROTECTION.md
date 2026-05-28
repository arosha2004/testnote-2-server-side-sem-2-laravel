# Data Protection

This document outlines the data protection layers and safety mechanisms implemented in the NoteHub database models and HTTP controller layer. These patterns prevent data leakage, validate input integrity, secure database entries, and restrict unauthorized access to resources.

---

## 9. Models

Eloquent models act as the gatekeeper for all database transactions in NoteHub. They enforce strong data protection policies directly at the application layer through fillable attributes, hidden fields, and type casting.

### 9.1 Mass Assignment Protection (`$fillable`)
To defend against **Mass Assignment Vulnerabilities**—where a malicious request attempts to update restricted columns (like changing user roles or elevating privileges)—every Eloquent model strictly defines its `$fillable` array. Any input parameter not listed in this array is silently ignored during write operations.

* **User Model Mass Assignment:**
  *Location:* [app/Models/User.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/User.php#L31-L42)
  ```php
  protected $fillable = [
      'first_name',
      'last_name',
      'email',
      'password',
      'user_role',
      'phone_number',
      'registered_date',
      'last_login_time',
      'google_id',
      'google_token',
  ];
  ```

* **Note Model Mass Assignment:**
  *Location:* [app/Models/Note.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/Note.php#L13)
  ```php
  protected $fillable = ['user_id', 'title', 'content', 'is_pinned', 'attachment'];
  ```

* **Category Model Mass Assignment:**
  *Location:* [app/Models/Category.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/Category.php#L15)
  ```php
  protected $fillable = ['category_name'];
  ```

---

### 9.2 Sensitive Attribute Protection (`$hidden`)
To prevent accidental data leakage, sensitive user credentials and tokens must never be sent in API payloads or serialized to JSON. The `User` model hides these critical fields using the `$hidden` array:

* **Hidden Serialization:**
  *Location:* [app/Models/User.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/User.php#L117-L122)
  ```php
  protected $hidden = [
      'password',
      'remember_token',
      'two_factor_recovery_codes',
      'two_factor_secret',
  ];
  ```

---

### 9.3 Attribute Casts & Password Hashing
Attribute casting automatically transforms database column values to relevant PHP data types. Crucially, password hashing is handled natively at the model layer, ensuring passwords are never stored in plain text.

* **Type Casting and Hashing:**
  *Location:* [app/Models/User.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/User.php#L139-L147)
  ```php
  protected function casts(): array
  {
      return [
          'email_verified_at' => 'datetime',
          'registered_date' => 'datetime',
          'last_login_time' => 'datetime',
          'password' => 'hashed', // Auto-hashes password using Bcrypt
      ];
  }
  ```
* **Boolean Normalization:**
  *Location:* [app/Models/Note.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/Note.php#L15-L17)
  ```php
  protected $casts = [
      'is_pinned' => 'boolean',
  ];
  ```

---

### 9.4 Soft Deletes
The `Note` model implements **Soft Deletes**. Instead of physically removing notes from the database (which can cause accidental data loss), notes are flagged with a `deleted_at` timestamp. This allows them to be kept in the Recycle Bin and restored easily.

* **Soft Delete Configuration:**
  *Location:* [app/Models/Note.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Models/Note.php#L9-L11)
  ```php
  class Note extends Model
  {
      use HasFactory, SoftDeletes;
  ```

---

## 10. Data Validation through Controllers

Data validation is the first line of defense at the HTTP routing layer. Every controller verifies incoming request shapes, formats, and values before they reach the business logic or database.

### 10.1 Input Format & Schema Validation
Laravel's `$request->validate()` method is used to enforce data integrity constraints. If validation fails, Laravel automatically throws a `ValidationException` and redirects the user back (for web requests) or returns a `422 Unprocessable Entity` JSON response (for API requests).

* **Note Creation Validation:**
  *Location:* [app/Http/Controllers/Api/NoteController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/NoteController.php#L20-L24)
  ```php
  $validated = $request->validate([
      'title' => 'required|string|max:255',
      'content' => 'nullable|string',
      'category_id' => 'nullable|exists:categories,id', // Enforces relational integrity
  ]);
  ```

* **Reminder Date & Pattern Validation:**
  *Location:* [app/Http/Controllers/Api/ReminderController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/ReminderController.php#L23-L27)
  ```php
  $validated = $request->validate([
      'note_id' => 'required|exists:notes,id',
      'reminder_time' => 'required|date|after:now', // Enforces chronological validation
      'repeat_type' => 'nullable|string|in:none,daily,weekly,monthly', // Restricts allowed enum values
  ]);
  ```

---

### 10.2 Resource Ownership Authorization
Validation doesn't just check data format; it also ensures that the authenticated user actually owns the resource they are trying to access, update, or delete. Without this verification, the system would be vulnerable to **Insecure Direct Object Reference (IDOR)** attacks.

* **Note Access Authorization:**
  *Location:* [app/Http/Controllers/Api/NoteController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/NoteController.php#L46-L48)
  ```php
  if ($note->user_id !== $request->user()->id) {
      return response()->json(['message' => 'Unauthorized'], 403);
  }
  ```

* **Reminder Scope Authorization:**
  *Location:* [app/Http/Controllers/Api/ReminderController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/ReminderController.php#L29-L30)
  ```php
  // Prevents linking a reminder to a note belonging to another user
  $note = $request->user()->notes()->findOrFail($request->note_id);
  ```

* **Scoped Listing Retrieval:**
  *Location:* [app/Http/Controllers/Api/NoteController.php](file:///c:/xampp/htdocs/testnote-2-server-side-sem-2-laravel/app/Http/Controllers/Api/NoteController.php#L13)
  ```php
  // Retrieves notes scoped exclusively to the authenticated user's session
  $notes = $request->user()->notes()->with('categories')->latest()->paginate(10);
  ```
