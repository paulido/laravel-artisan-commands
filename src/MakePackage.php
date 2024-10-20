<?php

namespace Paulido\Artisan;

use Illuminate\Console\Command;

class MakePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:package {name : The vendor/package name (e.g., vendor/name)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold a new package.';

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
        $name = $this->argument('name');

        // Validate the package name
        if (!$this->isValidPackageName($name)) {
            $this->error('Invalid package name format. Please use vendor/package format.');
            return 1; // Error code
        }

        $path = base_path() . '/app/packages/' . $name . '/src';
        $command = "composer init --name={$name} --no-interaction";

        // Create the package directory if it doesn't exist
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // Execute the composer command to initialize the package
        exec("cd {$path} && {$command}");

        $this->info("Package created successfully.");

        return 0; // Success
    }

    /**
     * Validate the package name format.
     *
     * @param string $name
     * @return bool
     */
    protected function isValidPackageName($name)
    {
        // Simple regex for vendor/package format
        return preg_match('/^[a-z0-9-_]+\/[a-z0-9-_]+$/i', $name);
    }
}
