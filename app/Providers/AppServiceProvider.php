<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Hanya Admin yang boleh mengelola data Ekspedisi
        Gate::define('manage-ekspedisi', function (User $user) {
            return $user->role === 'admin';
        });

        // Hanya Admin yang boleh menghapus data Ekspedisi
        Gate::define('delete-ekspedisi', function (User $user) {
            return $user->role === 'admin';
        });
    }
}



