<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Cashier::ignoreMigrations();

        $this->registerServices();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function registerServices()
    {
        $services = [
            'Contracts\Repositories\BranchRepository' => 'Repositories\MSSQL\BranchRepository',
            'Contracts\Repositories\PropertyRepository' => 'Repositories\MSSQL\PropertyRepository',
        ];

        foreach ($services as $key => $value) {
            $this->app->singleton('App\\'.$key, 'App\\'.$value);
        }
    }
}
