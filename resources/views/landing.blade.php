<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NoteHub - Your Second Brain</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            outfit: ['Outfit', 'sans-serif'],
                        },
                    },
                },
            }
        </script>
        @vite(['resources/js/app.js'])
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .mesh-bg {
                background-color: #ffffff;
                background-image: 
                    radial-gradient(at 40% 20%, rgba(79, 70, 229, 0.15) 0px, transparent 50%),
                    radial-gradient(at 80% 0%, rgba(147, 51, 234, 0.15) 0px, transparent 50%),
                    radial-gradient(at 0% 50%, rgba(236, 72, 153, 0.1) 0px, transparent 50%);
            }
            .floating { animation: float 6s ease-in-out infinite; }
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
                100% { transform: translateY(0px); }
            }
            .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
            .btn-indigo {
                background-color: #4f46e5;
                color: #ffffff;
                font-weight: 700;
                padding: 0.9rem 1.75rem;
                border-radius: 9999px;
                box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.5);
                transition: all 0.3s ease;
            }
            .btn-indigo:hover { background-color: #4338ca; }
            .btn-indigo:active { transform: scale(0.95); }
            .text-gradient {
                background-image: linear-gradient(90deg, #4f46e5, #8b5cf6, #ec4899);
                background-clip: text;
                -webkit-background-clip: text;
                color: transparent;
                -webkit-text-fill-color: transparent;
            }
        </style>
    </head>
    <body class="antialiased text-slate-800 bg-slate-50 selection:bg-indigo-500 selection:text-white">
        
        <!-- Floating Navbar -->
        <div class="fixed top-0 w-full z-50 px-4 sm:px-6 lg:px-8 pt-6">
            <nav class="max-w-7xl mx-auto glass rounded-full px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:justify-between gap-4 sm:items-center shadow-lg border border-white/40">
                <a href="/" class="flex shrink-0 items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="NoteHub" class="h-10 w-10 rounded-full object-cover shadow-sm ring-1 ring-white/80">
                    <span class="text-2xl font-bold tracking-tight text-brand-navy">NoteHub</span>
                </a>
                <div class="flex flex-wrap justify-center gap-4 sm:gap-8 md:justify-start">
                    <a href="#features" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Features</a>
                    <a href="#how-it-works" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">How it Works</a>
                </div>
                <div class="flex flex-wrap justify-center items-center gap-3 md:gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-indigo px-6 py-2.5 text-sm rounded-full">Go to Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-indigo-600 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-slate-900 text-white font-bold px-6 py-2.5 rounded-full hover:bg-indigo-600 transition-colors shadow-lg">Start for free</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </div>

        <!-- Hero Section -->
        <main class="mesh-bg pt-32 sm:pt-40 pb-16 sm:pb-20 overflow-hidden relative">
            <!-- Decorative Blobs -->
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 font-bold text-sm mb-8">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    NOTEHUB 2.0 is now live
                </div>
                
                <h1 class="text-5xl sm:text-6xl md:text-7xl font-black tracking-tight text-slate-900 mb-6 leading-tight">
                    Your Second Brain, <br class="hidden md:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">Perfectly Organized.</span>
                </h1>
                
                <p class="mt-6 text-lg sm:text-xl text-slate-600 max-w-xl sm:max-w-2xl mx-auto font-medium leading-relaxed mb-10">
                    Capture your genius thoughts, set powerful reminders, and access your knowledge base anywhere. The ultimate smart workspace for modern professionals.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="btn-indigo text-lg px-8 sm:px-10 py-4 flex items-center justify-center gap-2">
                        Get Started for Free
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
            
            <!-- Dashboard Showcase -->
            <div class="max-w-7xl mx-auto mt-24 px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-5 text-left">
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-6 leading-tight">
                            Experience a beautifully <br/> 
                            <span class="text-indigo-600">designed workspace</span>
                        </h2>
                        <p class="text-lg text-slate-600 mb-8 font-medium">
                            Say goodbye to cluttered interfaces. NoteHub offers a crystal-clear, distraction-free environment to organize your thoughts, tasks, and ideas with elegance.
                        </p>
                        
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">✓</div>
                                <span class="text-slate-700 font-medium">Intuitive Folder Organization</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold">✓</div>
                                <span class="text-slate-700 font-medium">Rich Text Editor & Markdown</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 font-bold">✓</div>
                                <span class="text-slate-700 font-medium">Smart Tags & Reminders</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="lg:col-span-7 relative floating">
                        <!-- Decorative glow behind image -->
                        <div class="absolute -inset-4 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-[2.5rem] opacity-20 blur-2xl"></div>
                        
                        <!-- The Picture -->
                        <img src="{{ asset('images/dashboard-mockup.png') }}" alt="Dashboard Preview" class="relative rounded-[2rem] shadow-2xl border border-white/50 w-full h-auto object-cover ring-1 ring-slate-900/5 bg-white/50 backdrop-blur-sm">
                        
                        <!-- Floating badge (words) -->
                        <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl p-4 flex items-center gap-4 border border-slate-100 animate-bounce" style="animation-duration: 3s;">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-2xl">🚀</div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Productivity</p>
                                <p class="text-sm text-slate-900 font-black">Boosted by 200%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Social Proof -->
        <div class="py-10 border-y border-slate-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">Trusted by brilliant minds everywhere</p>
                <div class="flex flex-wrap justify-center gap-10 opacity-50 grayscale">
                    <!-- Fake logos -->
                    <div class="text-2xl font-black">Acme Corp</div>
                    <div class="text-2xl font-black">Globex</div>
                    <div class="text-2xl font-black">Soylent</div>
                    <div class="text-2xl font-black">Initech</div>
                    <div class="text-2xl font-black">Umbrella</div>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div id="features" class="py-32 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20">
                    <h2 class="text-indigo-600 font-black tracking-widest uppercase text-sm mb-3">Power Features</h2>
                    <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                        Everything you need to <br/>do your best work
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Feature 1 -->
                    <div class="card-premium p-8 group hover:-translate-y-2 transition-all duration-500 cursor-pointer">
                        <div class="w-16 h-16 bg-indigo-50 rounded-[1.25rem] flex items-center justify-center mb-6 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Smart Editor</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">Jot down ideas, class notes, or meeting minutes quickly with our distraction-free interface.</p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="card-premium p-8 group hover:-translate-y-2 transition-all duration-500 cursor-pointer">
                        <div class="w-16 h-16 bg-purple-50 rounded-[1.25rem] flex items-center justify-center mb-6 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Organization</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">Group your notes into color-coded categories to keep your mental workspace perfectly structured.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="card-premium p-8 group hover:-translate-y-2 transition-all duration-500 cursor-pointer">
                        <div class="w-16 h-16 bg-orange-50 rounded-[1.25rem] flex items-center justify-center mb-6 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Reminders</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">Set one-time or recurring alerts attached to specific notes so you never drop the ball.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="card-premium p-8 group hover:-translate-y-2 transition-all duration-500 cursor-pointer">
                        <div class="w-16 h-16 bg-green-50 rounded-[1.25rem] flex items-center justify-center mb-6 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Time Travel</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">Accidentally deleted a paragraph? Restore older versions of your notes instantly with full history.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- How it Works -->
        <div id="how-it-works" class="py-32 bg-white border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20">
                    <h2 class="text-indigo-600 font-black tracking-widest uppercase text-sm mb-3">Simple Process</h2>
                    <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                        How NoteHub Works
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                    <!-- Connecting Line -->
                    <div class="hidden md:block absolute top-10 left-[16.66%] right-[16.66%] h-1 bg-gradient-to-r from-indigo-100 via-purple-100 to-pink-100 z-0"></div>

                    <!-- Step 1 -->
                    <div class="relative z-10 flex flex-col items-center text-center group">
                        <div class="w-20 h-20 bg-white rounded-full border-4 border-indigo-100 shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:border-indigo-300 transition-all duration-300">
                            <span class="text-2xl font-black text-indigo-600">1</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Sign Up Free</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">Create your account in seconds. No credit card required, just your email.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative z-10 flex flex-col items-center text-center group">
                        <div class="w-20 h-20 bg-white rounded-full border-4 border-purple-100 shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:border-purple-300 transition-all duration-300">
                            <span class="text-2xl font-black text-purple-600">2</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Capture Ideas</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">Start writing notes, creating categories, and setting reminders instantly.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative z-10 flex flex-col items-center text-center group">
                        <div class="w-20 h-20 bg-white rounded-full border-4 border-pink-100 shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:border-pink-300 transition-all duration-300">
                            <span class="text-2xl font-black text-pink-600">3</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Stay Organized</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">Access your structured knowledge base from any device, anytime.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-24 relative overflow-hidden">
            <div class="absolute inset-0 bg-slate-900"></div>
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">Ready to clear your mind?</h2>
                <p class="text-xl text-indigo-100 mb-10 font-medium">Join thousands of users who have transformed their productivity.</p>
                <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 font-black text-lg py-4 px-12 rounded-full shadow-2xl hover:scale-105 transition-transform duration-300">
                    Create Your Account
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-100 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="NoteHub" class="h-8 w-8 rounded-full object-cover">
                    <span class="text-xl font-bold tracking-tight text-brand-navy">NoteHub</span>
                </div>
                <p class="text-slate-500 font-medium">© {{ date('Y') }} NoteHub. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
