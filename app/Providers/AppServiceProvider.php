<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            function (\Illuminate\Auth\Events\Login $event) {
                if (request()->hasSession()) {
                    if (!session()->has('flash.banner')) {
                        session()->flash('flash.banner', 'Login successful! Welcome back to NoteHub.');
                        session()->flash('flash.bannerStyle', 'success');
                    }
                }
            }
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            function (\Illuminate\Auth\Events\Logout $event) {
                if (request()->hasSession()) {
                    session()->flash('flash.banner', 'You have been successfully logged out.');
                    session()->flash('flash.bannerStyle', 'success');
                }
            }
        );
    }
}
