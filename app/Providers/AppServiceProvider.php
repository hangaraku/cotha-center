<?php

namespace App\Providers;

use App\Models\UserUnit;
use App\Observers\UserUnitObserver;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;
use RyanChandler\FilamentNavigation\Models\Navigation;
use Filament\Navigation\NavigationItem;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Http\Responses\LoginResponse::class
        );
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        // Register UserUnit observer for automatic point management
        UserUnit::observe(UserUnitObserver::class);
        
        if(env('REDIRECT_HTTPS')) {
            $url->forceScheme('https');
        }
    }
}
