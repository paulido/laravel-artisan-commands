<?php

namespace Paulido\Artisan;

use Illuminate\Support\ServiceProvider;
use Paulido\Artisan\MakePackage;
use Paulido\Artisan\Hello;
use Paulido\Artisan\MigrateTable;
use Paulido\Artisan\SeedModelTable;
use Paulido\Artisan\RepositoryMakeCommand;


class ArtisanServiceProvider extends ServiceProvider
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

        // $this->loadRoutesFrom(__DIR__.'/routes/console.php');
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                Hello::class,
                MakePackage::class,
                MigrateTable::class,
                SeedModelTable::class,
                Template::class,
                // RepositoryMakeCommand::class,
                
            ]);
        }
    }
}
