<?php

namespace App\Services;

use App\Models\User;
use App\Models\Abonnement;
use App\Models\Paiement;
use App\Models\Notification;
use Illuminate\Support\Str;

class PaiementService
{
    public function simulerPaiement(User $user, string $mode = 'wave')
    {
        $montant = 5000;

        // Récupérer ou créer l'abonnement de l'utilisateur
        $abonnement = $user->abonnement;
        if (!$abonnement) {
            $abonnement = Abonnement::create([
                'user_id' => $user->id,
                'montant_mensuel' => $montant,
                'statut' => 'essai',
            ]);
        }

        $reference = 'PAY-' . strtoupper(uniqid());

        $paiement = Paiement::create([
            'abonnement_id' => $abonnement->id,
            'montant' => $montant,
            'reference' => $reference,
            'mode_paiement' => $mode,
            'statut' => 'valide',
            'date_paiement' => now(),
        ]);

        // Mettre à jour l'abonnement
        $abonnement->statut = 'actif';
        $abonnement->date_debut_abonnement = now();
        $abonnement->save();

        // Mettre à jour l'utilisateur
        $user->statut_compte = 'abonne_actif';
        $user->abonnement_statut = 'actif';
        $user->save();

        // Notification
        Notification::create([
            'user_id' => $user->id,
            'titre' => 'Abonnement activé',
            'message' => 'Votre abonnement a été activé avec succès. Prochain paiement le ' . now()->addMonth()->format('d/m/Y'),
            'type' => 'abonnement',
            'est_lu' => false,
        ]);

        return $paiement;
    }

    public function verifierPaiement(Paiement $paiement)
    {
        if ($paiement->statut === 'en_attente') {
            $paiement->statut = 'valide';
            $paiement->date_paiement = now();
            $paiement->save();

            $abonnement = $paiement->abonnement;
            $abonnement->statut = 'actif';
            $abonnement->date_debut_abonnement = now();
            $abonnement->save();

            $user = $abonnement->user;
            $user->statut_compte = 'abonne_actif';
            $user->abonnement_statut = 'actif';
            $user->save();

            return true;
        }
        return false;
    }

    public function genererTokenPaiement(User $user, float $montant = 5000)
    {
        $token = Str::random(32);
        session([
            'paiement_token' => $token,
            'paiement_user_id' => $user->id,
            'paiement_montant' => $montant
        ]);
        return $token;
    }
}
