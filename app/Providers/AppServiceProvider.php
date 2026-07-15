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
        $this->app->bind(
            \App\Repositories\Interfaces\UnitRepositoryInterface::class,
            \App\Repositories\Eloquent\UnitRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\AlternateUnitRepositoryInterface::class,
            \App\Repositories\Eloquent\AlternateUnitRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\BranchPriceRepositoryInterface::class,
            \App\Repositories\Eloquent\BranchPriceRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\TransporterRepositoryInterface::class,
            \App\Repositories\Eloquent\TransporterRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\DealerRepositoryInterface::class,
            \App\Repositories\Eloquent\DealerRepository::class
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
