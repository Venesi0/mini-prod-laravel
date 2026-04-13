<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


use Illuminate\Support\Facades\View;
use App\Models\Client;

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
        View::composer('user.*', function ($view) {
            $view->with('clients', Client::all());
        });

        View::composer([
            'admin.admin-clients',
            'admin.projects',
            'admin.project',
            'admin.tickets',
        ], function ($view) {
            $view->with('clients', Client::all());
        });
    }
}




