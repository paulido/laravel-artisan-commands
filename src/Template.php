<?php

namespace Paulido\Artisan;

use Illuminate\Console\Command;

class Template extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'template:assets {file} {destination?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use asset in given template';

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
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("Le fichier '$file' n'existe pas.");
            return 1; // Return error code
        }

        $destination = $this->argument('destination') ?? resource_path().'/views/template.blade.php';

        $lines = file($file); // Read file line by line in an array

        $newContent = '';
        foreach ($lines as $line) {
            // Remplace src="../../ ou href="../../ by {{assets('/
            $line = str_replace(['src="../../', 'href="../../'], ['src="{{assets(\'/', 'href="{{assets(\'/'], $line);
        
            // Close {{assets(...)}}
            $line = preg_replace_callback('/{{assets\(\'\/[^\'" ]+\.(css|js|jpg|jpeg|png|gif|svg)/', function($matches) {
                return $matches[0] . '\')}}';
            }, $line);
        
            $newContent .= $line;
        }
        
        file_put_contents($destination, $newContent);
        $this->info("Remplacement terminé !");
        
        return 0; // succes
    }
}
