# NoteHub QA Test Cases Reference

This table registers the 20 core QA test cases across authentication, notes management, profile configurations, and security validation.

| Test Case ID | Test Scenario | Test Steps | Prerequisites | Test Data | Expected/Intended Results | Actual Results | Test Status – Pass/Fail |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-001** | User Registration | Open registration page &rarr; Enter user details &rarr; Click register | User not registered | Name, Email, Password | User account created successfully | Account created successfully | **Pass** |
| **TC-002** | User Login | Open login page &rarr; Enter credentials &rarr; Click login | Registered account available | Valid email and password | User logged into dashboard successfully | Login successful | **Pass** |
| **TC-003** | Invalid Login | Enter incorrect password &rarr; Click login | Registered account available | Invalid password | Login denied with error message | Error displayed successfully | **Pass** |
| **TC-004** | Logout System | Click logout button after login | User authenticated | Active session | User logged out successfully | Logout successful | **Pass** |
| **TC-005** | Password Reset | Click forgot password &rarr; Enter email | Registered email required | Valid email address | Password reset email sent | Email sent successfully | **Pass** |
| **TC-006** | Middleware Route Protection | Access protected route without login | Protected route available | Guest user | Redirect to login page | Redirect successful | **Pass** |
| **TC-007** | Create Note | Open create note form &rarr; Enter details &rarr; Save note | User authenticated | Note title and content | Note created successfully | Note created successfully | **Pass** |
| **TC-008** | Display Notes | Open notes page | Existing notes available | Stored notes | Notes displayed correctly | Notes displayed successfully | **Pass** |
| **TC-009** | Update Note | Edit note &rarr; Save changes | Existing note available | Updated note details | Note updated successfully | Note updated successfully | **Pass** |
| **TC-010** | Delete Note | Click delete note button | Existing note available | Note ID | Note deleted successfully | Note deleted successfully | **Pass** |
| **TC-011** | Two-Factor Auth (Enable) | Toggle enable 2FA &rarr; Input password &rarr; Complete | User authenticated | Valid password | 2FA enabled, secret key & recovery codes generated | Enable successful | **Pass** |
| **TC-012** | Two-Factor Recovery Codes | Click regenerate recovery codes | 2FA enabled | Active session | Old codes revoked; 8 new codes stored in DB | Regeneration successful | **Pass** |
| **TC-013** | Note Soft Deletion | Click delete note on note manager card | Active notes available | Note ID | Note soft deleted and moved to Recycle Bin | Soft delete successful | **Pass** |
| **TC-014** | Restore Note | Click restore note on Recycle Bin | Soft deleted note in trash | Note ID | Note restored back to active workspace | Restore successful | **Pass** |
| **TC-015** | Profile Details Update | Change profile name/email &rarr; Save | User authenticated | New profile details | Database updates user name and email | Update successful | **Pass** |
| **TC-016** | Account Self-Deletion | Click delete account &rarr; Input password | User authenticated | Valid password | Wipes user account and notes from database | Delete successful | **Pass** |
| **TC-017** | Validation Testing | Submit form with empty fields | Create note form available | Empty title and content | Validation error displayed | Validation error displayed | **Pass** |
| **TC-018** | SQL Injection Testing | Enter SQL injection string into form | Login form available | `' OR 1=1 --` | Query blocked securely | Attack prevented successfully | **Pass** |
| **TC-019** | XSS Testing | Enter script tag into input field | Note form available | `<script>alert('XSS')</script>` | Script escaped securely | Script escaped successfully | **Pass** |
| **TC-020** | Google OAuth Login | Click Google login &rarr; Authenticate account | Google OAuth configured | Valid Google account | User logged in successfully | Login successful | **Pass** |
