<?php

namespace Paulido\Artisan;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
class MakePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:package'; //{name=vendor/package}' (i.e name: <vendor/name> : paulido/package)

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scafold a package';

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
        $vendor = $this->ask('Enter vendor name :');
        $name = $this->ask('Enter package name : ');
        $path = base_path() . '/app/packages/' . $vendor . '/' . $name . '/src';
        $command = "composer init" ;

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $this->info("**package infos**");
        $this->info("package name: {$vendor} . '/' . {$name}");

        exec("cd {$path} && {$command}");
        Artisan::call("make:repository {$name}");
        
        $this->info( "package created succefully");
    }
}
