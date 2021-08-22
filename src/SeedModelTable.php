<?php

namespace Paulido\Artisan;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
class SeedModelTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:model {model}'; //{name=vendor/package}' (i.e name: <vendor/name> : paulido/package)

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'seed a model table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = $this->argument('model');
        $ClassName = ucfirst($model);
        $seeder = $ClassName . 'Seeder';
        $this->info("Seeding {$seeder}");
        Artisan::call("migrate:model {$model}");
        Artisan::call("db:seed --class={$seeder}");
        $this->info( "Seeded {$seeder}");
    }
}
