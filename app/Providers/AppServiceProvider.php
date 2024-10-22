<?php

namespace App\Providers;

use App\Models\Collection\Collection;
use App\Models\User\User;
use App\Observers\CollectionObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Observers
        User::observe(UserObserver::class);
        Collection::observe(CollectionObserver::class);
    }
}
