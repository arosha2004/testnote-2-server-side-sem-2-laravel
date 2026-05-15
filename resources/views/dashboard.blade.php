<x-app-layout>
    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            {{-- Stats Section --}}
            <section>
                <livewire:dashboard-stats />
            </section>

            {{-- Main Workspace Section --}}
            <section>
                <livewire:note-manager />
            </section>
        </div>
    </div>
</x-app-layout>
