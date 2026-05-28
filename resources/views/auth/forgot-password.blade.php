<x-guest-layout>
    <div class="min-h-screen bg-white flex flex-col justify-center items-center px-4">
        
        <div class="w-full max-w-[400px] flex flex-col items-center">
            
            <!-- Logo -->
            <div class="mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="NoteHub logo" class="mx-auto h-20 w-20 object-contain" />
            </div>

            <!-- Header -->
            <h1 class="text-[28px] font-bold text-slate-900 mb-4 tracking-tight text-center">Reset Password</h1>

            <div class="mb-6 text-sm text-slate-500 text-center leading-relaxed">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
            </div>

            @session('status')
                <div class="mb-6 font-medium text-sm text-green-700 w-full text-center bg-green-50 p-4 rounded-md border border-green-200">
                    {{ $value }}
                </div>
            @endsession

            <x-validation-errors class="mb-4 w-full" />

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="w-full">
                @csrf

                <!-- Email Input -->
                <div class="mb-6">
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                           placeholder="Enter your email address"
                           class="w-full px-4 py-3 rounded-md border border-slate-300 focus:border-[#3b66d5] focus:ring-1 focus:ring-[#3b66d5] outline-none transition-colors text-slate-800 placeholder:text-slate-400">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-[#3b66d5] hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition-colors shadow-sm mb-4">
                    {{ __('Email Password Reset Link') }}
                </button>
                
                <!-- Back to Login -->
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-[#3b66d5] transition-colors font-medium">
                        &larr; Back to login
                    </a>
                </div>
            </form>
            
        </div>
    </div>
</x-guest-layout>
