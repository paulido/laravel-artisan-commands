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

        // Vérifier si le fichier existe
        if (!file_exists($file)) {
            $this->error("Le fichier '$file' n'existe pas.");
            return 1; // Retourner un code d'erreur
        }

        $destination = $this->argument('destination') ?? resource_path().'/views/template.blade.php';

        $lines = file($file); // Lire le fichier dans un tableau de lignes

        $newContent = '';
        foreach ($lines as $line) {
            // Remplacer src="../../ ou href="../../ par {{assets('/
            $line = str_replace(['src="../../', 'href="../../'], ['src="{{assets(\'/', 'href="{{assets(\'/'], $line);
        
            // Fermer {{assets(...)}} quand on rencontre une extension de fichier
            $line = preg_replace_callback('/{{assets\(\'\/[^\'" ]+\.(css|js|jpg|jpeg|png|gif|svg)\'\)}}/', function($matches) {
                return $matches[0] . '}}';
            }, $line);
        
            $newContent .= $line;
        }
        
        // Écrire le contenu mis à jour dans le fichier de destination
        file_put_contents($destination, $newContent);
        $this->info("Remplacement terminé !");
        
        return 0; // Retourner un code de succès
    }
}
