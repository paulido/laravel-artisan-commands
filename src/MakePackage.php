<?php

namespace Paulido\Artisan;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Exception;
use InvalidArgumentException;

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

        $packagePath = base_path() . '/app/packages/' . $name;
        $srcPath = $packagePath . '/src';

        // Create the package directory structure
        $this->createDirectory($srcPath);
        $serviceName = $this->createServiceProvider($srcPath, $name);
        // Uncomment if routes file creation is needed
        // $this->createRoutesFile($srcPath);
        $this->initializeComposer($srcPath, $name);

        $this->info("Package created successfully.");

        // Register the service provider
        try {
            $this->appendExtraToComposerJson("{$name}\\{$serviceName}", $serviceName);
        } catch (Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1; // Error code
        }

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
        return preg_match('/^[a-z0-9-_]+\/[a-z0-9-_]+$/i', $name);
    }

    /**
     * Create a directory if it doesn't exist.
     *
     * @param string $path
     */
    protected function createDirectory($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    /**
     * Create a service provider file.
     *
     * @param string $srcPath
     * @param string $name
     */
    protected function createServiceProvider($srcPath, $name)
    {
        $providerTemplate = $this->getStub();
        $providerName = ucfirst(explode('/', $name)[1]) . 'ServiceProvider';
        $providerContent = $this->replaceClass($providerTemplate, $providerName);
        file_put_contents($srcPath . '/' . $providerName . '.php', $providerContent);
        $this->info("Service provider created.");
        return $providerName;
    }

    /**
     * Initialize composer in the package directory.
     *
     * @param string $srcPath
     * @param string $name
     */
    protected function initializeComposer($srcPath, $name)
    {
        $command = "composer init --name={$name}";
        exec("cd {$srcPath} && {$command}");
        $this->info("Composer initialized.");
    }

    /**
     * Append the service provider and alias to the main composer.json.
     *
     * @param string $provider
     * @param string $alias
     */
    protected function appendExtraToComposerJson($provider, $alias)
    {
        $composerPath = base_path('composer.json'); // Update this path

        if (!file_exists($composerPath)) {
            throw new Exception("File not found: $composerPath");
        }

        $composerJson = json_decode(file_get_contents($composerPath), true);

        if (!isset($composerJson['extra'])) {
            $composerJson['extra'] = [];
        }
        if (!isset($composerJson['extra']['laravel'])) {
            $composerJson['extra']['laravel'] = [
                'providers' => [],
                'aliases' => []
            ];
        }

        $composerJson['extra']['laravel']['providers'][] = $provider;
        $composerJson['extra']['laravel']['aliases'][$alias] = "{$provider}::class";

        file_put_contents($composerPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info("Successfully appended to composer.json.");
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('Paulido/Artisan/stubs/ServiceProvider.stub'); // Corrected path and namespace
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Paulido\\Artisan'; // Corrected namespace format
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        if (!$this->argument('name')) {
            throw new InvalidArgumentException("Missing required argument package name");
        }
        $stub = str_replace('DummyServiceProvider', $name, $stub);
        return $stub;
    }
}
