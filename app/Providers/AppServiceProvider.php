<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        // Set session lifetime to 10 hours (600 minutes)
        config(['session.lifetime' => config('auth.remember_duration', 600)]);
        
        // Set remember me cookie to expire after 10 hours (600 minutes)
        // Laravel's default is 5 years, but we want 10 hours
        Auth::setRememberDuration(600);
    }
}
