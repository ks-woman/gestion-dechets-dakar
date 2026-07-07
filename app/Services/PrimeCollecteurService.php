<?php

namespace App\Services;

use App\Models\Collecteur;
use App\Models\Collecte;
use App\Models\PrimeCollecteur;
use Carbon\Carbon;

class PrimeCollecteurService
{
    /**
     * Calcule les primes pour un collecteur pour un mois/année donnés.
     */
    public function calculerPrimes(Collecteur $collecteur, int $mois, int $annee): array
    {
        // Récupérer toutes les collectes du mois
        $collectes = Collecte::where('collecteur_id', $collecteur->id)
            ->whereMonth('date_collecte', $mois)
            ->whereYear('date_collecte', $annee)
            ->where('statut', 'realisee')
            ->get();

        $totalCollectes = $collectes->count();
        $poidsTotal = $collectes->sum('poids_recyclable') + $collectes->sum('poids_organique') + $collectes->sum('poids_residuel');

        // 1. Prime de base : 1000 FCFA par collecte
        $primeBase = $totalCollectes * 1000;

        // 2. Bonus de poids : 500 FCFA pour 100 kg
        $bonusPoids = floor($poidsTotal / 100) * 500;

        // 3. Bonus de ponctualité : 2000 FCFA si 100% des collectes sont à l'heure
        // (supposons un champ 'a_l_heure' dans la table collectes)
        $collectesEnRetard = $collectes->filter(function ($c) {
            return isset($c->a_l_heure) && $c->a_l_heure == false;
        })->count();
        $bonusPonctualite = ($collectesEnRetard == 0 && $totalCollectes > 0) ? 2000 : 0;

        // 4. Prime de satisfaction : 3000 FCFA si note moyenne >= 4.5
        $noteMoyenne = $collecteur->note_moyenne ?? 0;
        $primeSatisfaction = ($noteMoyenne >= 4.5) ? 3000 : 0;

        // 5. Prime d'ancienneté : 5000 FCFA par trimestre complet
        $dateEmbauche = $collecteur->user->date_inscription ?? now();
        $trimestres = floor($dateEmbauche->diffInMonths(now()) / 3);
        $primeAnciennete = $trimestres * 5000;

        // 6. Prime d'objectif : 10 000 FCFA si objectif mensuel atteint
        // (supposons un champ 'objectif_mensuel' dans la table collecteurs)
        $objectifMensuel = $collecteur->objectif_mensuel ?? 50;
        $primeObjectif = ($totalCollectes >= $objectifMensuel) ? 10000 : 0;

        // Total
        $montantTotal = $primeBase + $bonusPoids + $bonusPonctualite + $primeSatisfaction + $primeAnciennete + $primeObjectif;

        return [
            'collecteur_id' => $collecteur->id,
            'mois' => $mois,
            'annee' => $annee,
            'montant_total' => $montantTotal,
            'details' => [
                'prime_base' => $primeBase,
                'bonus_poids' => $bonusPoids,
                'bonus_ponctualite' => $bonusPonctualite,
                'prime_satisfaction' => $primeSatisfaction,
                'prime_anciennete' => $primeAnciennete,
                'prime_objectif' => $primeObjectif,
                'total_collectes' => $totalCollectes,
                'poids_total' => round($poidsTotal, 2),
                'note_moyenne' => $noteMoyenne,
                'objectif_mensuel' => $objectifMensuel,
                'objectif_atteint' => $totalCollectes >= $objectifMensuel,
            ],
            'statut' => 'calcule',
        ];
    }

    /**
     * Enregistre ou met à jour la prime pour un collecteur.
     */
    public function enregistrerPrimes(Collecteur $collecteur, int $mois, int $annee): PrimeCollecteur
    {
        $data = $this->calculerPrimes($collecteur, $mois, $annee);

        return PrimeCollecteur::updateOrCreate(
            [
                'collecteur_id' => $collecteur->id,
                'mois' => $mois,
                'annee' => $annee,
            ],
            $data
        );
    }

    /**
     * Calcule les primes pour tous les collecteurs pour un mois donné.
     */
    public function calculerPrimesPourTous(int $mois, int $annee): void
    {
        $collecteurs = Collecteur::all();
        foreach ($collecteurs as $collecteur) {
            $this->enregistrerPrimes($collecteur, $mois, $annee);
        }
    }

    /**
     * Récupère le total des primes validées pour un collecteur.
     */
    public function totalPrimesValides(Collecteur $collecteur): float
    {
        return PrimeCollecteur::where('collecteur_id', $collecteur->id)
            ->where('statut', 'valide')
            ->sum('montant_total');
    }

    /**
     * Récupère le total des primes payées pour un collecteur.
     */
    public function totalPrimesPayees(Collecteur $collecteur): float
    {
        return PrimeCollecteur::where('collecteur_id', $collecteur->id)
            ->where('statut', 'paye')
            ->sum('montant_total');
    }

    /**
     * Change le statut d'une prime.
     */
    public function changerStatut(PrimeCollecteur $prime, string $nouveauStatut): bool
    {
        if (!in_array($nouveauStatut, ['calcule', 'valide', 'paye'])) {
            return false;
        }
        $prime->statut = $nouveauStatut;
        return $prime->save();
    }
}
