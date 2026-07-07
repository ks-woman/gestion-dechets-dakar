<?php

namespace App\Console\Commands;

use App\Services\PrimeCollecteurService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculerPrimesCollecteur extends Command
{
    protected $signature = 'collecteur:calculer-primes {--mois=} {--annee=} {--collecteur=}';
    protected $description = 'Calcule les primes des collecteurs pour un mois donné.';

    public function handle(PrimeCollecteurService $service)
    {
        $mois = $this->option('mois') ?? Carbon::now()->subMonth()->month;
        $annee = $this->option('annee') ?? Carbon::now()->subMonth()->year;

        $collecteurId = $this->option('collecteur');

        $this->info("Calcul des primes pour $mois/$annee...");

        if ($collecteurId) {
            $collecteur = \App\Models\Collecteur::find($collecteurId);
            if (!$collecteur) {
                $this->error('Collecteur non trouvé.');
                return 1;
            }
            $service->enregistrerPrimes($collecteur, $mois, $annee);
            $this->info('✅ Prime calculée pour le collecteur #' . $collecteurId);
        } else {
            $service->calculerPrimesPourTous($mois, $annee);
            $this->info('✅ Primes calculées pour tous les collecteurs.');
        }
        return 0;
    }
}
