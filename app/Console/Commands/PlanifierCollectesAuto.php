<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Collecte;
use Illuminate\Console\Command;

class PlanifierCollectesAuto extends Command
{
    protected $signature = 'collectes:planifier-auto';
    protected $description = 'Planifie automatiquement les collectes selon les préférences des utilisateurs';

    public function handle()
    {
        $users = User::where('frequence_collecte', '!=', 'sur_demande')->get();
        $compteur = 0;

        foreach ($users as $user) {
            if (!$user->estJourCollecte()) {
                continue;
            }

            // Vérifier si une collecte existe déjà pour aujourd'hui
            $existe = Collecte::where('user_id', $user->id)
                ->whereDate('date_collecte', today())
                ->exists();

            if (!$existe) {
                Collecte::create([
                    'user_id' => $user->id,
                    'date_demande' => now(),
                    'date_collecte' => now(),
                    'statut' => 'planifiee',
                    'adresse' => $user->adresse,
                    'collecte_auto' => true
                ]);
                $compteur++;
                $this->info("Collecte planifiée pour {$user->prenom} {$user->nom}");
            }
        }

        $this->info("$compteur collectes planifiées automatiquement.");
    }
}
