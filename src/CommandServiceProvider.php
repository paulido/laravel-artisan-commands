<?php

namespace Paulido\Commands;

use Illuminate\Support\ServiceProvider;
use Paulido\Commands\MakePackage;


class CommandServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__.'/routes/console.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakePackage::class,
            ]);
        }
    }
}
