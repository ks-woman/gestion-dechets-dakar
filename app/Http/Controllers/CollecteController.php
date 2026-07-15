<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use App\Models\DemandeCollecte;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // ✅ AJOUT
use App\Models\StockDechet;

class CollecteController extends BaseController
{
    use AuthorizesRequests; // ✅ AJOUT

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le formulaire de demande de collecte
     */
    public function showDemanderCollecte()
    {
        // Le middleware menage filtre déjà, pas besoin de re-vérifier
        // if (Gate::denies('create', Collecte::class)) {
        //     abort(403, 'Vous n\'êtes pas autorisé à demander une collecte.');
        // }

        return view('collecte.demander');
    }

    /**
     * Traiter la demande de collecte
     */
    public function demanderCollecte(Request $request)
    {
        $user = Auth::user();

        // Les vérifications sont déjà faites par le middleware et les méthodes ci-dessous
        // $this->authorize('create', Collecte::class);

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

    /**
     * Historique des collectes
     */
    public function historique()
    {
        $user = Auth::user();

        // Pas besoin de policy car on filtre par user_id
        $collectes = Collecte::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('collecte.historique', compact('collectes'));
    }

    /**
     * Dashboard ménage
     */
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

    /**
     * Statistiques
     */
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

    /**
     * Enregistrer une collecte (réservé aux collecteurs)
     * Cette méthode est appelée par le CollecteurController via une route distincte
     */
    public function enregistrerCollecte(Request $request)
    {
        $collecteur = Auth::user();

        // Vérification manuelle car le middleware collecteur n'est pas appliqué à cette route
        if (!$collecteur instanceof User || $collecteur->role !== 'collecteur') {
            abort(403, 'Seul un collecteur peut enregistrer une collecte.');
        }

        $client = User::findOrFail($request->user_id);

        // Vérifier si une collecte a déjà été faite aujourd'hui pour ce client
        $collecteExistante = Collecte::where('user_id', $client->id)
            ->whereDate('date_collecte', today())
            ->where('statut', 'realisee')
            ->exists();

        if ($collecteExistante) {
            return redirect()->route('collecteur.tournee')->with('error', 'Ce client a déjà été collecté aujourd\'hui.');
        }

        // Récupérer la collecte planifiée du jour (si elle existe)
        $collectePlanifiee = Collecte::where('user_id', $client->id)
            ->whereDate('date_collecte', today())
            ->where('statut', 'planifiee')
            ->first();

        if (!$collectePlanifiee) {
            return redirect()->route('collecteur.tournee')->with('error', 'Aucune collecte planifiée pour ce client aujourd\'hui.');
        }

        // Calcul des poids et points
        $poidsRecyclable = ($request->plastiques_metaux ?? 0) + ($request->papiers_cartons ?? 0);
        $poidsOrganique = $request->organiques ?? 0;
        $poidsResiduel = $request->autres ?? 0;
        $points = ($poidsRecyclable * 1) + ($poidsOrganique * 0.5);

        // Mettre à jour la collecte existante
        $collectePlanifiee->update([
            'statut' => 'realisee',
            'poids_recyclable' => $poidsRecyclable,
            'poids_organique' => $poidsOrganique,
            'poids_residuel' => $poidsResiduel,
            'points_obtenus' => (int)$points,
            'collecteur_id' => $collecteur->id,
        ]);

        // Incrémenter les stocks de déchets
        // Note : cette partie utilise un système de stock par type, qui peut être amélioré
        // avec les catégories dynamiques comme dans CollecteurController
        StockDechet::incrementer('recyclable', $poidsRecyclable);
        StockDechet::incrementer('organique', $poidsOrganique);
        StockDechet::incrementer('residuel', $poidsResiduel);

        // Ajouter les points au client
        $client->ajouterPoints($points);

        // Notification au client
        Notification::create([
            'user_id' => $client->id,
            'titre' => '♻️ Collecte effectuée',
            'message' => "Votre collecte a été réalisée. Vous avez gagné " . (int)$points . " points.",
            'type' => 'collecte',
            'est_lu' => false
        ]);

        return redirect()->route('collecteur.tournee')->with('success', 'Collecte enregistrée avec succès !');
    }
}
