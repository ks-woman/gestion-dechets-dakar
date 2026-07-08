<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockDechet;
use App\Models\CategorieDechet;

class StockDechetSeeder extends Seeder
{
    public function run()
    {
        $categories = CategorieDechet::all();
        foreach ($categories as $categorie) {
            StockDechet::firstOrCreate(
                ['categorie_id' => $categorie->id],
                ['quantite' => 0, 'prix_unitaire' => 0]
            );
        }
    }
}
