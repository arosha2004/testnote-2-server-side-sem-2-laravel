<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class SendPasswordResetLink extends Component
{
    /**
     * Send a password reset link to the authenticated user.
     */
    public function sendResetLink()
    {
        $status = Password::broker()->sendResetLink([
            'email' => auth()->user()->email
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('flash.banner', __('A fresh password reset link has been sent to your email address.'));
            session()->flash('flash.bannerStyle', 'success');
        } else {
            session()->flash('flash.banner', __($status));
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('profile.send-password-reset-link');
    }
}
