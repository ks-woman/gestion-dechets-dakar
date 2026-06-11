<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Collecte;
use App\Models\KitTri;
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

    public function utilisateurs()
    {
        $users = User::with(['menage', 'entreprise', 'collecteur'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

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

        // Top utilisateurs
        $topUsers = User::orderBy('score_total', 'desc')->take(5)->get();

        return view('admin.statistiques', compact(
            'moisKeys',
            'collectesParMoisValues',
            'pointsParMoisValues',
            'totalRecyclable',
            'totalOrganique',
            'totalResiduel',
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
}
