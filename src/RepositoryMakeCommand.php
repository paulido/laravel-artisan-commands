<?php

namespace Paulido\Artisan;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class RepositoryMakeCommand extends GeneratorCommand
{
    protected $name = 'make:repository {model}';
    protected $description = 'Create a new model repository';
    protected $type = 'Repository';

    private $repositoryClass;
    private $model;

    public function handle() // Changed from fire() to handle()
    {
        $this->setRepositoryClass();

        $path = $this->getPath($this->repositoryClass);

        if ($this->alreadyExists($this->repositoryClass)) {
            $this->error($this->type . ' already exists!');
            return false;
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClass($this->repositoryClass));
        $this->info($this->type . ' created successfully.');
        $this->line("<info>Created Repository :</info> $this->repositoryClass");
    }

    private function setRepositoryClass()
    {
        $name = ucwords(strtolower($this->argument('model')));
        $this->model = $name;
        $this->repositoryClass = $this->parseName($name) . 'Repository';
    }

    protected function replaceClass($stub, $name)
    {
        if (!$this->argument('model')) {
            throw new InvalidArgumentException("Missing required argument model name");
        }

        $stub = parent::replaceClass($stub, $name);
        return str_replace('DummyModel', $this->model, $stub);
    }

    protected function getStub()
    {
        return base_path('Paulido/Commands/stubs/Repository.stub'); // Adjusted path
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Paulido\Commands'; // Corrected namespace
    }

    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of the model class.'], // Changed to 'model'
        ];
    }
}
