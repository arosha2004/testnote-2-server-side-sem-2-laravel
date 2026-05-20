<x-guest-layout>
    <div class="min-h-screen bg-white flex flex-col justify-center items-center px-4" x-data="{ showPassword: false }">
        
        <div class="w-full max-w-[400px] flex flex-col items-center">
            
            <!-- Logo -->
            <div class="mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="NoteHub logo" class="mx-auto h-20 w-20 object-contain" />
            </div>

            <!-- Header -->
            <h1 class="text-[32px] font-bold text-slate-900 mb-8 tracking-tight">Log in</h1>

            <x-validation-errors class="mb-4 w-full" />

            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600 w-full text-center">
                    {{ $value }}
                </div>
            @endsession

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="w-full" @submit.prevent="if(!showPassword) { showPassword = true; $refs.pwd.focus(); } else { $el.submit(); }">
                @csrf

                <!-- Email Input -->
                <div class="mb-4">
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                           placeholder="Email"
                           class="w-full px-4 py-3 rounded-md border border-slate-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-colors text-slate-800 placeholder:text-slate-400">
                </div>

                <!-- Password Input (Hidden by default, shown when 'log in manually' is clicked) -->
                <div x-show="showPassword" x-collapse class="mb-4">
                    <input id="password" type="password" name="password" autocomplete="current-password" x-ref="pwd"
                           placeholder="Password"
                           class="w-full px-4 py-3 rounded-md border border-slate-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-colors text-slate-800 placeholder:text-slate-400">
                </div>

                <!-- Remember Me (Hidden, but active for Fortify) -->
                <input type="hidden" name="remember" value="on">

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-[#3b66d5] hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition-colors shadow-sm mb-4">
                    <span x-text="showPassword ? 'Log in' : 'Continue with email'"></span>
                </button>
            </form>

            <!-- Toggle Text -->
            <div class="text-center text-sm text-slate-500 mb-8 leading-relaxed">
                <p x-show="!showPassword">We'll email you a code to log in,<br>or you can <button @click="showPassword = true" type="button" class="text-[#3b66d5] hover:underline font-medium">log in manually</button>.</p>
                <p x-show="showPassword" style="display: none;">Enter your password to log in,<br>or <button @click="showPassword = false" type="button" class="text-[#3b66d5] hover:underline font-medium">use a magic link instead</button>.</p>
            </div>

            <!-- Divider -->
            <div class="w-full h-px bg-slate-100 mb-8"></div>

            <!-- Footer -->
            <div class="text-center text-sm text-slate-500">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-[#3b66d5] hover:underline font-medium">Sign up</a>
            </div>
            
        </div>
    </div>
</x-guest-layout>
