<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategorieDechet;

class CategorieDechetSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['nom' => 'Plastique', 'icone' => '', 'couleur' => 'blue'],
            ['nom' => 'Métal', 'icone' => '', 'couleur' => 'gray'],
            ['nom' => 'Papier / Carton', 'icone' => '', 'couleur' => 'yellow'],
            ['nom' => 'Verre', 'icone' => '', 'couleur' => 'green'],
            ['nom' => 'Organique', 'icone' => '', 'couleur' => 'emerald'],
            ['nom' => 'Textile', 'icone' => '', 'couleur' => 'purple'],
            ['nom' => 'Déchet dangereux', 'icone' => '', 'couleur' => 'red'],
            ['nom' => 'Résiduel', 'icone' => '', 'couleur' => 'gray'],
        ];

        foreach ($categories as $cat) {
            CategorieDechet::firstOrCreate(
                ['nom' => $cat['nom']],
                [
                    'icone' => $cat['icone'],
                    'couleur' => $cat['couleur'],
                    'est_actif' => true,
                ]
            );
        }
    }
}
