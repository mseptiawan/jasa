<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Define gate admin
        FacadesGate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
