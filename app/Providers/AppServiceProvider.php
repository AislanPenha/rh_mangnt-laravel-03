<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        // -------------------------
        // GATE
        // -------------------------

        // Define a gate that checks if the user is admin
        Gate::define('admin', function(){
            return (Auth::user()->role === 'admin');
        });

        // Define a gate that checks if the user is rh
        Gate::define('rh', function(){
            return (Auth::user()->role === 'rh');
        });

        // Define a gate that checks if the user is colaborator
        Gate::define('colaborator', function(){
            return (Auth::user()->role === 'colaborator');
        });
    }
}
