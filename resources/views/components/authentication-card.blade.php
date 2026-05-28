<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
    <div class="mb-4">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-[440px] px-8 py-10 bg-white border border-slate-200/80 shadow-2xl overflow-hidden sm:rounded-3xl transition-colors duration-300">
        {{ $slot }}
    </div>
</div>
