<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use App\Models\DemandeCollecte;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class CollecteController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Afficher le formulaire de demande de collecte
    public function showDemanderCollecte()
    {
        return view('collecte.demander');
    }

    // Traiter la demande de collecte
    public function demanderCollecte(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403, 'Utilisateur non authentifié.');
        }

        // Vérifier si l'utilisateur peut demander une collecte
        if (!$user->estEnPeriodeEssai() && !$user->estAbonneActif()) {
            return redirect()->back()->with('error', 'Vous devez être en période d\'essai ou abonné actif pour demander une collecte.');
        }

        $request->validate([
            'date_souhaitee' => 'required|date|after_or_equal:today',
            'adresse' => 'nullable|string',
            'instructions' => 'nullable|string'
        ]);

        // Créer la demande de collecte
        $demande = DemandeCollecte::create([
            'user_id' => $user->id,
            'date_souhaitee' => $request->date_souhaitee,
            'instructions' => $request->instructions,
            'statut' => 'en_attente'
        ]);

        // Créer la collecte liée à la demande
        $collecte = Collecte::create([
            'user_id' => $user->id,
            'date_demande' => now(),
            'date_collecte' => $request->date_souhaitee,
            'adresse' => $request->adresse ?? $user->adresse,
            'instructions' => $request->instructions,
            'statut' => 'planifiee'
        ]);

        // Notifier tous les collecteurs
        $collecteurs = User::where('role', 'collecteur')->get();

        foreach ($collecteurs as $collecteur) {
            Notification::create([
                'user_id' => $collecteur->id,
                'titre' => 'Nouvelle demande de collecte',
                'message' => $user->prenom . ' ' . $user->nom . ' a demandé une collecte le ' . date('d/m/Y', strtotime($request->date_souhaitee)),
                'type' => 'collecte',
                'est_lu' => false
            ]);
        }

        return redirect()->route('collectes')->with('success', 'Demande de collecte enregistrée ! Un collecteur vous contactera.');
    }

    // Historique des collectes
    public function historique()
    {
        $user = Auth::user();
        $collectes = Collecte::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('collecte.historique', compact('collectes'));
    }

    // Dashboard ménage (UNE SEULE FOIS)
    public function dashboard()
    {
        $user = Auth::user();
        $collectes = Collecte::where('user_id', $user->id)
            ->orderBy('date_collecte', 'desc')
            ->take(5)
            ->get();

        $totalCollectes = Collecte::where('user_id', $user->id)->count();

        // Prochaine collecte planifiée
        $prochaineCollecte = Collecte::where('user_id', $user->id)
            ->where('statut', 'planifiee')
            ->whereDate('date_collecte', '>=', today())
            ->orderBy('date_collecte', 'asc')
            ->first();

        return view('menage.dashboard', compact('user', 'collectes', 'totalCollectes', 'prochaineCollecte'));
    }

    // Statistiques
    public function statistiques()
    {
        $user = Auth::user();

        // Toutes les collectes de l'utilisateur
        $collectes = Collecte::where('user_id', $user->id)
            ->orderBy('date_collecte', 'desc')
            ->get();

        // Totaux
        $totalCollectes = $collectes->count();
        $totalRecyclable = $collectes->sum('poids_recyclable');
        $totalOrganique = $collectes->sum('poids_organique');
        $totalResiduel = $collectes->sum('poids_residuel');
        $totalPoints = $user->score_total;

        // Collectes par mois (pour le graphique)
        $collectesParMois = [];
        $pointsParMois = [];

        foreach ($collectes as $collecte) {
            $mois = \Carbon\Carbon::parse($collecte->date_collecte)->format('M Y');

            if (!isset($collectesParMois[$mois])) {
                $collectesParMois[$mois] = 0;
                $pointsParMois[$mois] = 0;
            }

            $collectesParMois[$mois]++;
            $pointsParMois[$mois] += $collecte->points_obtenus;
        }

        return view('menage.statistiques', compact(
            'collectes',
            'totalCollectes',
            'totalRecyclable',
            'totalOrganique',
            'totalResiduel',
            'totalPoints',
            'collectesParMois',
            'pointsParMois'
        ));
    }
}
