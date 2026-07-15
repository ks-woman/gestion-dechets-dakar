<?php

namespace App\Services;

use App\Models\User;
use App\Models\Paiement;
use App\Models\Abonnement;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Simule un paiement (à remplacer par une vraie intégration plus tard)
     */
    public function simulerPaiement(User $user, string $mode = 'wave'): Paiement
    {
        // Récupérer ou créer l'abonnement
        $abonnement = $user->abonnement;
        if (!$abonnement) {
            $abonnement = Abonnement::create([
                'user_id' => $user->id,
                'date_debut_essai' => now(),
                'date_fin_essai' => now()->addDays(15),
                'montant_mensuel' => 5000,
                'statut' => 'essai',
            ]);
        }

        // Mode de paiement valide
        $modePaiement = in_array($mode, ['paiement_mobile', 'paiement_bancaire', 'especes'])
            ? $mode
            : 'paiement_mobile';

        // Générer une référence unique
        $ref = 'SIM-' . strtoupper(uniqid());

        // Créer le paiement en incluant la colonne 'reference' (qui semble exister)
        $paiement = Paiement::create([
            'abonnement_id' => $abonnement->id,
            'montant' => $abonnement->montant_mensuel,
            'date_paiement' => now(),
            'mode_paiement' => $modePaiement,
            'reference' => $ref,                    // <-- ajout pour satisfaire la colonne
            'reference_transaction' => $ref,        // si la colonne existe aussi
            'statut' => 'valide',
        ]);

        // Mettre à jour l'abonnement
        $abonnement->statut = 'actif';
        $abonnement->date_debut_abonnement = now();
        $abonnement->save();

        $user->statut_compte = 'abonne_actif';
        $user->save();

        return $paiement;
    }
}
