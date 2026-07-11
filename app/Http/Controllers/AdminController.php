<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Collecte;
use App\Models\KitTri;
use App\Models\Menage;
use App\Models\Entreprise;
use App\Models\Collecteur;
use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_menages' => User::where('role', 'menage')->count(),
            'total_entreprises' => User::where('role', 'entreprise')->count(),
            'total_collecteurs' => User::where('role', 'collecteur')->count(),
            'total_collectes' => Collecte::count(),
            'total_points_distribues' => User::sum('score_total'),
        ];

        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }

    public function utilisateurs(Request $request)
    {
        $query = User::with(['menage', 'entreprise', 'collecteur']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.utilisateurs', compact('users'));
    }

    public function collectes()
    {
        $collectes = Collecte::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.collectes', compact('collectes'));
    }

    public function kits()
    {
        $kits = KitTri::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.kits', compact('kits'));
    }

    public function statistiques()
    {
        // Collectes par mois
        $collectesParMois = Collecte::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        $moisKeys = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $collectesParMoisValues = [];
        for ($i = 1; $i <= 12; $i++) {
            $collectesParMoisValues[] = $collectesParMois[$i] ?? 0;
        }

        // Points par mois
        $pointsParMois = Collecte::selectRaw('MONTH(created_at) as mois, SUM(points_obtenus) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        $pointsParMoisValues = [];
        for ($i = 1; $i <= 12; $i++) {
            $pointsParMoisValues[] = $pointsParMois[$i] ?? 0;
        }

        // Totaux déchets
        $totalRecyclable = Collecte::sum('poids_recyclable');
        $totalOrganique = Collecte::sum('poids_organique');
        $totalResiduel = Collecte::sum('poids_residuel');

        // Totaux généraux
        $totalPointsGeneraux = User::sum('score_total');
        $totalUsers = User::count();
        $totalCollectes = Collecte::count();

        // Répartition par rôle
        $totalMenages = User::where('role', 'menage')->count();
        $totalEntreprises = User::where('role', 'entreprise')->count();
        $totalCollecteurs = User::where('role', 'collecteur')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalPartenaires = User::where('role', 'partenaire')->count();

        // Top utilisateurs
        $topUsers = User::withCount('collectes')
            ->orderBy('score_total', 'desc')
            ->take(5)
            ->get();

        return view('admin.statistiques', compact(
            'moisKeys',
            'collectesParMoisValues',
            'pointsParMoisValues',
            'totalRecyclable',
            'totalOrganique',
            'totalResiduel',
            'totalPointsGeneraux',
            'totalUsers',
            'totalCollectes',
            'totalMenages',
            'totalEntreprises',
            'totalCollecteurs',
            'totalAdmins',
            'totalPartenaires',
            'topUsers'
        ));
    }

    public function createUtilisateur()
    {
        return view('admin.utilisateurs-create');
    }

    public function storeUtilisateur(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'quartier' => 'required|string|max:100',
            'role' => 'required|in:menage,entreprise,collecteur,admin,partenaire',
            'mot_passe' => 'required|string|min:6',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'quartier' => $request->quartier,
            'mot_passe' => bcrypt($request->mot_passe),
            'role' => $request->role,
            'statut_compte' => 'inscrit',
            'score_total' => 0,
            'date_inscription' => now(),
        ]);

        // Créer le profil spécifique selon le rôle
        if ($request->role == 'menage') {
            Menage::create(['user_id' => $user->id, 'nombre_personnes' => 1, 'type_logement' => 'appartement']);
        } elseif ($request->role == 'entreprise') {
            Entreprise::create(['user_id' => $user->id, 'numero_registre_commerce' => 'AUTO', 'type_activite' => 'Autre']);
        } elseif ($request->role == 'collecteur') {
            Collecteur::create(['user_id' => $user->id, 'matricule' => 'COL' . $user->id, 'vehicule_type' => 'camion']);
        } elseif ($request->role == 'partenaire') {
            Partenaire::create(['user_id' => $user->id, 'type_partenaire' => 'Autre', 'filiere' => 'Recyclage']);
        }

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur créé avec succès !');
    }

    public function editUtilisateur($id)
    {
        $user = User::findOrFail($id);
        return view('admin.utilisateurs-edit', compact('user'));
    }

    public function updateUtilisateur(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'quartier' => 'required|string|max:100',
            'role' => 'required|in:menage,entreprise,collecteur,admin,partenaire',
            'statut_compte' => 'required|string',
        ]);

        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'quartier' => $request->quartier,
            'role' => $request->role,
            'statut_compte' => $request->statut_compte,
        ]);

        if ($request->filled('mot_passe')) {
            $user->mot_passe = bcrypt($request->mot_passe);
            $user->save();
        }

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur modifié avec succès !');
    }

    public function destroyUtilisateur($id)
    {
        $user = User::findOrFail($id);

        if ($user->role == 'admin') {
            return redirect()->back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur supprimé avec succès !');
    }


    public function collecteursDisponibilite()
    {
        // Récupérer tous les collecteurs avec leur utilisateur
        $collecteurs = Collecteur::with('user')->get();

        // Enrichir chaque collecteur avec ses statistiques du jour
        $collecteurs->each(function ($collecteur) {
            $collecteur->collectes_aujourdhui = Collecte::where('collecteur_id', $collecteur->id)
                ->whereDate('date_collecte', today())
                ->count();

            $collecteur->commandes_en_cours = Commande::where('collecteur_id', $collecteur->id)
                ->whereIn('statut', ['affectee', 'en_livraison'])
                ->count();

            // Déterminer le statut de disponibilité
            // Règle : occupé si plus de 3 collectes + commandes en cours > 2
            // Partiel si entre 1 et 3, Disponible si 0
            $totalActivites = $collecteur->collectes_aujourdhui + $collecteur->commandes_en_cours;

            if ($totalActivites == 0) {
                $collecteur->disponible = true;
                $collecteur->partiellement_disponible = false;
            } elseif ($totalActivites <= 3) {
                $collecteur->disponible = false;
                $collecteur->partiellement_disponible = true;
            } else {
                $collecteur->disponible = false;
                $collecteur->partiellement_disponible = false;
            }
        });

        return view('admin.collecteurs', compact('collecteurs'));
    }

/**
 * Récupère les informations d'une commande pour le modal
 */
public function commandeInfos($id)
{
    $commande = Commande::with(['partenaire', 'categorie'])->findOrFail($id);
    
    return response()->json([
        'id' => $commande->id,
        'partenaire' => $commande->partenaire->prenom . ' ' . $commande->partenaire->nom,
        'categorie' => $commande->categorie->nom ?? 'Non défini',
        'quantite' => number_format($commande->quantite, 1),
        'montant' => number_format($commande->montant_total, 0, ',', ' '),
        'adresse' => $commande->partenaire->adresse ?? 'Non renseignée',
    ]);
}

/**
 * Récupère la liste des collecteurs disponibles pour une commande
 */
public function collecteursDisponibles($commandeId)
{
    $commande = Commande::findOrFail($commandeId);
    
    // Collecteurs déjà occupés aujourd'hui (collectes ou commandes en cours)
    $date = now()->toDateString();
    
    $collecteursOccupes = Collecte::whereDate('date_collecte', $date)
        ->whereIn('statut', ['planifiee', 'en_cours'])
        ->pluck('collecteur_id')
        ->merge(
            Commande::whereDate('date_affectation', $date)
                ->whereIn('statut', ['affectee', 'en_livraison'])
                ->pluck('collecteur_id')
        )
        ->unique()
        ->toArray();

    // Collecteurs disponibles (non occupés, disponibles, et pas déjà affectés à cette commande)
    $collecteurs = Collecteur::with('user')
        ->whereNotIn('id', $collecteursOccupes)
        ->where('disponibilite', true)
        ->orderBy('id')
        ->get();
        }

        // Ajouter des informations utiles pour l'affichage
        $collecteurs->each(function ($collecteur) {
            $zone = $collecteur->zones()->first();
            $collecteur->zone_nom = $zone ? $zone->nom : 'Aucune zone';
            $collecteur->activites_aujourdhui = Collecte::where('collecteur_id', $collecteur->id)
                ->whereDate('date_collecte', today())
                ->whereIn('statut', ['planifiee', 'en_cours'])
                ->count() + Commande::where('collecteur_id', $collecteur->id)
                ->whereIn('statut', ['affectee', 'en_livraison'])
                ->count();
        });

        return response()->json([
            'collecteurs' => $collecteurs,
            'zone_partenaire' => $partenaire->quartier,
            'nb_zones' => count($collecteursIdsZone),
            'nb_disponibles' => $collecteurs->count(),
            'zone_vide' => empty($collecteursIdsZone),
            'voir_tous' => $voirTous,
            'success' => true,
        ]);
    }

    // =============================================
    // AFFECTER UN COLLECTEUR À UNE COMMANDE
    // =============================================
public function affecterCollecteur(Request $request, $id)
{
    $request->validate([
        'collecteur_id' => 'required|exists:collecteurs,id',
    ]);

    $commande = Commande::findOrFail($id);
    
    // Vérifier que la commande est dans un état valide (en attente ou validée)
    if (!in_array($commande->statut, ['en_attente', 'validee'])) {
        return redirect()->back()->with('error', 'Cette commande ne peut pas être affectée.');
    }

    // Vérifier que le collecteur n'est pas déjà occupé
    $collecteur = Collecteur::find($request->collecteur_id);
    // Ici on pourrait faire une vérification supplémentaire (optionnelle)

    // Affecter le collecteur
    $commande->collecteur_id = $request->collecteur_id;
    $commande->statut = 'affectee';
    $commande->date_affectation = now();
    $commande->save();

    // Notification au collecteur
    \App\Models\Notification::create([
        'user_id' => $collecteur->user_id,
        'titre' => ' Nouvelle livraison',
        'message' => 'Vous avez été affecté à la livraison de la commande #' . $commande->id . ' pour le partenaire ' . $commande->partenaire->nom . ' ' . $commande->partenaire->prenom,
        'type' => 'commande',
        'est_lu' => false,
    ]);

    // Notification au partenaire (optionnelle)
    \App\Models\Notification::create([
        'user_id' => $commande->partenaire_id,
        'titre' => ' Commande en cours de livraison',
        'message' => 'Votre commande #' . $commande->id . ' a été prise en charge par le collecteur ' . $collecteur->user->prenom . ' ' . $collecteur->user->nom,
        'type' => 'commande',
        'est_lu' => false,
    ]);

    return redirect()->route('admin.commandes.index')->with('success', 'Collecteur affecté avec succès !');
    }
}
