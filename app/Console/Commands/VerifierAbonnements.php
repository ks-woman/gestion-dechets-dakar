<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Models\Notification;
use Illuminate\Console\Command;

class VerifierAbonnements extends Command
{
    protected $signature = 'abonnements:verifier';
    protected $description = 'Vérifie les fins d\'essai et les abonnements expirés';

    public function handle()
    {
        $this->info('Vérification des abonnements...');

        // Essais expirés
        $essaisExpires = Abonnement::where('statut', 'essai')
            ->where('date_fin_essai', '<', now())
            ->get();

        foreach ($essaisExpires as $abonnement) {
            $abonnement->statut = 'expire';
            $abonnement->save();

            $user = $abonnement->user;
            if ($user) {
                $user->statut_compte = 'en_attente_paiement';
                $user->save();

                Notification::create([
                    'user_id' => $user->id,
                    'titre' => ' Fin de votre période d\'essai',
                    'message' => 'Votre période d\'essai est terminée. Abonnez-vous pour continuer.',
                    'type' => 'abonnement',
                    'est_lu' => false,
                ]);

                $this->warn("Essai expiré pour {$user->email}");
            }
        }

        // Abonnements actifs dont le paiement est dû
        // (ici on simule une échéance à 1 mois après la date de début)
        $abonnes = Abonnement::where('statut', 'actif')
            ->where('date_debut_abonnement', '<', now()->subMonth())
            ->get();

        foreach ($abonnes as $abonnement) {
            $abonnement->statut = 'expire';
            $abonnement->save();

            $user = $abonnement->user;
            if ($user) {
                $user->statut_compte = 'en_attente_paiement';
                $user->save();

                Notification::create([
                    'user_id' => $user->id,
                    'titre' => ' Paiement en retard',
                    'message' => 'Votre abonnement a été suspendu. Veuillez renouveler votre paiement.',
                    'type' => 'abonnement',
                    'est_lu' => false,
                ]);

                $this->warn("Abonnement suspendu pour {$user->email}");
            }
        }

        $this->info("" . $essaisExpires->count() . " essais expirés, " . $abonnes->count() . " abonnements suspendus.");
    }
}
