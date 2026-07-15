<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAbonnementController extends Controller
{
    /**
     * Afficher la page d'abonnement de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        $abonnement = $user->abonnement;
        $paiements = $abonnement ? $abonnement->paiements()->orderBy('created_at', 'desc')->take(10)->get() : collect();

        return view('menage.abonnement.index', compact('abonnement', 'paiements'));
    }

    /**
     * Souscrire à un abonnement
     */
    public function souscrire(Request $request)
    {
        $user = Auth::user();

        // Vérifier si déjà abonné actif
        if ($user->abonnement && $user->abonnement->estActif()) {
            return redirect()->back()->with('error', 'Vous êtes déjà abonné.');
        }

        // Créer un abonnement en période d'essai
        $abonnement = Abonnement::create([
            'user_id' => $user->id,
            'date_debut_essai' => now(),
            'date_fin_essai' => now()->addDays(15),
            'montant_mensuel' => 5000,
            'statut' => 'essai',
        ]);

        // Mettre à jour le statut du compte
        $user->statut_compte = 'essai_15j';
        $user->date_debut_essai = now();
        $user->date_fin_essai = now()->addDays(15);
        $user->save();

        return redirect()->route('abonnement.index')->with('success', '✅ Votre abonnement est activé pour 15 jours d\'essai.');
    }

    /**
     * Annuler l'abonnement
     */
    public function annuler()
    {
        $user = Auth::user();
        $abonnement = $user->abonnement;

        if ($abonnement) {
            $abonnement->statut = 'resilie';
            $abonnement->save();

            $user->statut_compte = 'inactif';
            $user->save();
        }

        return redirect()->route('abonnement.index')->with('success', 'Votre abonnement a été résilié.');
    }

    /**
     * Historique des paiements
     */
    public function historique()
    {
        $user = Auth::user();
        $abonnement = $user->abonnement;

        if (!$abonnement) {
            return redirect()->route('abonnement.index')->with('error', 'Vous n\'avez pas d\'abonnement.');
        }

        $paiements = $abonnement->paiements()->orderBy('created_at', 'desc')->paginate(15);

        return view('menage.abonnement.historique', compact('paiements', 'abonnement'));
    }
}
