# NoteHub QA Test Cases Reference

This document provides the formal QA Test Case specification and tabular directory of the automated unit and feature test cases configured in the **NoteHub** application.

---

## 1. QA Test Case Specification Table

Below is the structured registry of the automated test cases grouped by functional modules, matching the requested layout format.

| Test Case ID | Test Scenario | Test Steps | Prerequisites | Test Data | Expected/Intended Results | Actual Results |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-001** | User Registration | Open registration page &rarr; Enter user details &rarr; Click register | User not registered | Name, Email, Password | User account created successfully and success banner displayed | Account created successfully |
| **TC-002** | Login System | Open login page &rarr; Enter credentials &rarr; Click login | Registered account available | Valid email and password | User logged into dashboard and redirected | Login successful |
| **TC-003** | Invalid Login | Enter incorrect password &rarr; Click login | Registered account available | Invalid password | Login denied with error message | Error displayed successfully |
| **TC-004** | Logout System | Click logout button after login | User authenticated | Active session | User logged out successfully | Logout successful |
| **TC-005** | Password Reset | Click forgot password &rarr; Enter email | Registered email required | Valid email address | Password reset email sent | Email sent successfully |
| **TC-006** | Email Verification | Register new account &rarr; Verify email | Registered account | Verification email | Email verified successfully | Skipped (Verification not enabled) |
| **TC-007** | Middleware Route Protection | Access protected route without login | Protected route available | Guest user | Redirect to login page | Redirect successful |
| **TC-008** | System Harness | Run PHPUnit test suite | Test suite setup | None | Assert base environment is functional | Asserted successfully |
| **TC-009** | Landing Page | Open public landing page | Public access enabled | None | Renders landing page screen with status 200 OK | Render successful |
| **TC-010** | Login Screen Rendering | Open login page URL | Guest user | None | Form fields and layout render correctly | Render successful |
| **TC-011** | Registration Screen Rendering | Open registration page URL | Guest user | None | Registration form fields render correctly | Render successful |
| **TC-012** | Registration Disable Check | Open registration page with support disabled | Registration support disabled | None | Renders 404 page | Skipped (Registration enabled) |
| **TC-013** | Logout Other Sessions | Input password &rarr; Click logout other browser sessions | User authenticated | Valid password | Invalidates all other session cookies | Invalidation successful |
| **TC-014** | Password Update | Change password via profile settings | User authenticated | Valid old password, new password & matching confirmation | Password updated in database | Update successful |
| **TC-015** | Password Update Correct Check | Change password with incorrect current password | User authenticated | Mismatched old password | Rejects request with error validation | Error validated successfully |
| **TC-016** | Password Update Matching Check | Change password with mismatched new passwords | User authenticated | Mismatched new passwords | Rejects request with mismatch error | Mismatch verified successfully |
| **TC-017** | Password Confirmation Screen | Access protected profile action | User authenticated | Action requires password confirmation | Prompt screen displays | Prompt displayed successfully |
| **TC-018** | Confirm Password | Input correct password &rarr; Confirm | User authenticated | Valid password | Password confirmed for session | Confirmation successful |
| **TC-019** | Confirm Password Fail | Input incorrect password &rarr; Confirm | User authenticated | Invalid password | Rejects confirmation with error | Reject successful |
| **TC-020** | Reset Password Link Screen | Open forgot password link screen | Guest user | None | Forgot password form renders | Form rendered successfully |
| **TC-021** | Reset Password Token Screen | Open password reset URL with token | Valid token | Token data | Password reset form renders | Form rendered successfully |
| **TC-022** | Password Reset Success | Input new password &rarr; Click reset | Valid token | New password details | Password updated in database and redirected | Reset successful |
| **TC-023** | Enable Two-Factor Auth | Click enable 2FA &rarr; Complete setup | User authenticated | Valid password | 2FA enabled, secret key & recovery codes generated | Enable successful |
| **TC-024** | Regenerate 2FA Recovery Codes | Click regenerate recovery codes | 2FA enabled | Valid session | Revokes old codes and stores new codes in DB | Regeneration successful |
| **TC-025** | Disable Two-Factor Auth | Click disable 2FA | 2FA enabled | Valid password | 2FA disabled, credentials wiped to null | Disable successful |
| **TC-026** | Profile Information Render | Access profile settings page | User authenticated | Valid session | Profile form populated with name and email | Data loaded successfully |
| **TC-027** | Profile Details Update | Change name/email &rarr; Save | User authenticated | New profile details | Name and email updated in DB | Update successful |
| **TC-028** | Account Self-Deletion | Confirm deletion with password &rarr; Delete | User authenticated | Valid password | User account and associated notes purged from DB | Account deleted successfully |
| **TC-029** | Delete Account Validation | Confirm deletion with incorrect password | User authenticated | Invalid password | Blocks account deletion with error | Block successful |
| **TC-030** | Email Verification Screen | Open email verification page | Unverified user | None | Shows verification warning alert | Skipped (Verification not enabled) |
| **TC-031** | Email Verification Link | Click verification link from email | Unverified user | Valid verification link | Verifies user email in DB | Skipped (Verification not enabled) |
| **TC-032** | Note Recycle Bin (Soft Delete) | Click delete note | Active notes available | Note ID | Note soft deleted and moved to trash | Soft delete successful |
| **TC-033** | Trash Manager Display | Open trash/recycle bin page | Soft deleted notes present | None | Lists all soft deleted notes | Display successful |
| **TC-034** | Restore Note | Click restore note in recycle bin | Soft deleted note in trash | Note ID | Note restored back to active workspace | Restore successful |
| **TC-035** | Permanent Delete Note | Click permanently delete note in trash | Soft deleted note in trash | Note ID | Note permanently deleted from DB | Purge successful |
| **TC-036** | Empty Trash | Click empty trash | Soft deleted notes present | None | Purges all soft deleted notes from DB | Empty trash successful |
| **TC-037** | API Token Creation | Input name/permissions &rarr; Create API token | API enabled | Token name & abilities | Token generated and secret shown | Skipped (API disabled) |
| **TC-038** | API Token Deletion | Click delete API token | API token exists | Token ID | Revokes token and removes from DB | Skipped (API disabled) |
| **TC-039** | API Token Scope Update | Edit scopes &rarr; Save API token | API token exists | New scopes | Scope updated in DB | Skipped (API disabled) |

---

## 2. Running the Test Suite

Execute the entire suite locally:
```bash
php artisan test
```

Execute specific test suites:
```bash
# Run only Unit Tests
php artisan test --testsuite=Unit

# Run only Feature Tests
php artisan test --testsuite=Feature

# Run only Recycle Bin Tests
php artisan test --filter RecycleBinTest
```
