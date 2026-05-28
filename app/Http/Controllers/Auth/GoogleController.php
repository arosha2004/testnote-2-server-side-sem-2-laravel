<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleController extends Controller
{
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
            session()->flash('flash.banner', 'Login successful! Welcome back to NoteHub.');
            session()->flash('flash.bannerStyle', 'success');
        } else {
            // Check if user exists with the same email address
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Link Google account to existing email
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                ]);
                session()->flash('flash.banner', 'Login successful! Welcome back to NoteHub.');
                session()->flash('flash.bannerStyle', 'success');
            } else {
                // Register a new user
                $nameParts = User::parseFullName($googleUser->getName());

                $user = User::create([
                    'first_name' => $nameParts['first_name'] ?: 'Google',
                    'last_name' => $nameParts['last_name'] ?: 'User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'password' => Hash::make(Str::random(24)),
                    'registered_date' => now(),
                    'email_verified_at' => now(),
                ]);
                session()->flash('flash.banner', 'Registration successful! Welcome to NoteHub.');
                session()->flash('flash.bannerStyle', 'success');
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
}
