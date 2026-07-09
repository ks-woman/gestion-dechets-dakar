<?php

namespace App\Console\Commands;

use App\Models\Collecte;
use App\Models\StockDechet;
use App\Models\CategorieDechet;
use Illuminate\Console\Command;

class UpdateStocksFromExistingCollectes extends Command
{
    protected $signature = 'stocks:update-from-collectes';
    protected $description = 'Met à jour les stocks à partir des collectes déjà enregistrées';

    public function handle()
    {
        $collectes = Collecte::where('statut', 'realisee')
            ->whereNotNull('details_poids')
            ->get();

        $this->info('Collectes trouvées : ' . $collectes->count());

        $categories = CategorieDechet::where('est_actif', true)->get();

        foreach ($collectes as $collecte) {
            $details = json_decode($collecte->details_poids, true);

            if (!$details) continue;

            foreach ($categories as $categorie) {
                $poids = 0;

                switch ($categorie->nom) {
                    case 'Plastique':
                        $poids = $details['plastiques_metaux'] ?? 0;
                        break;
                    case 'Papier / Carton':
                        $poids = $details['papiers_cartons'] ?? 0;
                        break;
                    case 'Organique':
                        $poids = $details['organiques'] ?? 0;
                        break;
                    case 'Résiduel':
                        $poids = $details['autres'] ?? 0;
                        break;
                    default:
                        $poids = 0;
                        break;
                }

                if ($poids > 0) {
                    StockDechet::incrementer($categorie->id, $poids);
                }
            }
        }

        $this->info('Stocks mis à jour avec succès.');
    }
}
