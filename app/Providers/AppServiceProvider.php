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
        $this->app->bind(
            \App\Repositories\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\StockRepositoryInterface::class,
            \App\Repositories\Eloquent\StockRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\CustomerRepositoryInterface::class,
            \App\Repositories\Eloquent\CustomerRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\BranchRepositoryInterface::class,
            \App\Repositories\Eloquent\BranchRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\VehicleRepositoryInterface::class,
            \App\Repositories\Eloquent\VehicleRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
