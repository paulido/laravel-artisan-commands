<?php

namespace Paulido\Artisan;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
class MigrateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:model {model}'; //{name=vendor/package}' (i.e name: <vendor/name> : paulido/package)

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'migrate a model table';

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
        $str = lcfirst($model);
        $path = app_path(). "/../" . "database/migrations/*{$str}s_table.php";
        $migration = basename(glob($path)[0]);
        $this->info("Migrating {$migration}");
        Artisan::call("migrate:refresh --path=database/migrations/{$migration}");
        $this->info( "Migrated! {$migration}");
    }
}
