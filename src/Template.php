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
    protected $signature = 'template:assets';

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
        $file = "/home/vagrant/Codes/laravel/ecommerce/resources/views/components/layout-home.blade.php";
        // $file = $this->argument('file');
        $lines = file($file); // Read the file into an array of lines

        $newContent = '';
        foreach ($lines as $line) {
            // Remplacer src="../../ ou href="../../ par {{assets('/
            $line = str_replace(['src="../../', 'href="../../'], ['src="{{assets(\'/', 'href="{{assets(\'/'], $line);
        
        
            // Fermer {{assets(...)}} quand on rencontre une extension de fichier
            $line = preg_replace_callback('/{{assets\(\'\/[^\'" ]+\.(css|js|jpg|jpeg|png|gif|svg)/', function($matches) {
                return $matches[0] . '\')}}';
            }, $line);
        
        
            $newContent .= $line;
        }
        
        // Écrire le contenu mis à jour dans le fichier
        file_put_contents($file, $newContent);
        echo "Remplacement terminé !\n";
        
    }
}
