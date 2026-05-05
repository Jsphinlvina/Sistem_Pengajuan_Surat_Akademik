<?php

namespace App\Providers;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $view->with([
                    'unreadNotifications' => auth()->user()
                        ->unreadNotifications()
                        ->limit(5)
                        ->get(),
                    'unreadCount' => auth()->user()
                        ->unreadNotifications()
                        ->count(),
                ]);
            }
        });

        Gate::define('admin', function ($user) {
            return auth()->user()->role === 0;
            });

        Gate::define('staff', function ($user) {
            return auth()->user()->role === 1;
            });

        Gate::define('student', function ($user) {
            return auth()->user() instanceof Mahasiswa;
        });
    }
}
