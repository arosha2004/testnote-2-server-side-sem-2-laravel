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
            <div class="relative w-full flex items-center justify-center my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative px-4 bg-white text-xs uppercase tracking-wider text-slate-400">or</div>
            </div>

            <!-- Google Login Button -->
            <a href="{{ route('auth.google') }}" 
               class="w-full flex items-center justify-center gap-3 bg-white hover:bg-slate-50 text-slate-700 font-medium py-3 px-4 rounded-md border border-slate-300 transition-colors shadow-sm mb-6">
                <svg class="h-5 w-5" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span>Continue with Google</span>
            </a>

            <!-- Footer -->
            <div class="text-center text-sm text-slate-500">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-[#3b66d5] hover:underline font-medium">Sign up</a>
            </div>
            
        </div>
    </div>
</x-guest-layout>
