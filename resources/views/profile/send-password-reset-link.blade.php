<x-action-section>
    <x-slot name="title">
        {{ __('Reset Password via Email') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Send a secure password reset link directly to your email address to change your password without entering the current one.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-slate-600 dark:text-slate-400 transition-colors">
            {{ __('This is particularly useful if you registered using Google OAuth and do not have a password set, or if you want to update your password securely through your email.') }}
        </div>

        <div class="mt-5">
            <x-button wire:click="sendResetLink" wire:loading.attr="disabled">
                {{ __('Send Reset Password Link') }}
            </x-button>
        </div>
    </x-slot>
</x-action-section>
