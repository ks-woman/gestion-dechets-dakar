<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Collecte;
use App\Models\KitTri;
use App\Models\StockDechet;
use App\Models\CategorieDechet;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Recompense;
use App\Models\PrimeCollecteur;
use App\Models\Anomalie;

class CollecteurController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('collecteur');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $collecteur = $user->collecteur;

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $nonLues = Notification::where('user_id', $user->id)
            ->where('est_lu', false)
            ->count();

        $kitsALivrer = KitTri::where('statut', 'en_attente')
            ->with('user')
            ->get();

        $collectesAujourdhui = Collecte::where('collecteur_id', $collecteur->id)
            ->whereDate('date_collecte', today())
            ->count();

        $collectesMois = Collecte::where('collecteur_id', $collecteur->id)
            ->whereMonth('date_collecte', now()->month)
            ->count();

        $collectes = Collecte::where('statut', 'planifiee')
            ->where(function ($query) use ($collecteur) {
                $query->whereNull('collecteur_id')
                    ->orWhere('collecteur_id', $collecteur->id);
            })
            ->with('user')
            ->orderBy('date_collecte', 'asc')
            ->get();

        return view('collecteur.dashboard', compact(
            'notifications',
            'nonLues',
            'kitsALivrer',
            'collectesAujourdhui',
            'collectesMois',
            'collectes'
        ));
    }

    public function demanderCollecte(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403, 'Utilisateur non authentifié.');
        }

        $kit = $user->kitTri;

        if (!$kit || $kit->statut !== 'actif') {
            return redirect()->back()->with('error', 'Vous devez d\'abord activer votre kit de tri pour demander une collecte.');
        }

        $estEnPeriodeEssai = $user->estEnPeriodeEssai();

        if (!$estEnPeriodeEssai && !$user->estAbonneActif()) {
            return redirect()->back()->with('error', 'Vous devez être en période d\'essai ou abonné actif pour demander une collecte.');
        }

        return redirect()->back()->with('error', 'Cette fonctionnalité n\'est pas encore implémentée.');
    }

    public function tournee()
    {
        $collecteur = Auth::user()->collecteur;
        $zones = $collecteur->zones;
        $quartiers = [];

        foreach ($zones as $zone) {
            $quartiers = array_merge($quartiers, $zone->quartiers ?? []);
        }
        $quartiers = array_unique($quartiers);

        $clients = User::whereIn('role', ['menage', 'entreprise'])
            ->when(!empty($quartiers), function ($query) use ($quartiers) {
                return $query->whereIn('quartier', $quartiers);
            })
            ->whereHas('collectes', function ($query) {
                $query->whereDate('date_collecte', today())
                    ->where('statut', 'planifiee');
            })
            ->with(['collectes' => function ($query) {
                $query->whereDate('date_collecte', today())
                    ->where('statut', 'planifiee');
            }])
            ->get();

        return view('collecteur.tournee', compact('clients'));
    }

    public function formEnregistrerCollecte($id)
    {
        $client = User::findOrFail($id);
        return view('collecteur.enregistrer-collecte', compact('client'));
    }

    public function enregistrerCollecte(Request $request)
    {
        $collecteur = Auth::user();
        $client = User::findOrFail($request->user_id);

        $collecteExistante = Collecte::where('user_id', $client->id)
            ->whereDate('date_collecte', today())
            ->where('statut', 'realisee')
            ->exists();

        if ($collecteExistante) {
            return redirect()->route('collecteur.tournee')->with('error', 'Ce client a déjà été collecté aujourd\'hui.');
        }

        $collectePlanifiee = Collecte::where('user_id', $client->id)
            ->whereDate('date_collecte', today())
            ->where('statut', 'planifiee')
            ->first();

        if (!$collectePlanifiee) {
            return redirect()->route('collecteur.tournee')->with('error', 'Aucune collecte planifiée pour ce client aujourd\'hui.');
        }

        $poidsPlastiquesMetaux = $request->plastiques_metaux ?? 0;
        $poidsPapiersCartons = $request->papiers_cartons ?? 0;
        $poidsRecyclable = $poidsPlastiquesMetaux + $poidsPapiersCartons;
        $poidsOrganique = $request->organiques ?? 0;
        $poidsResiduel = $request->autres ?? 0;
        $points = ($poidsRecyclable * 1) + ($poidsOrganique * 0.5);

        $collectePlanifiee->update([
            'statut' => 'realisee',
            'poids_recyclable' => $poidsRecyclable,
            'poids_organique' => $poidsOrganique,
            'poids_residuel' => $poidsResiduel,
            'points_obtenus' => (int)round($points),
            'collecteur_id' => $collecteur->id,
            'details_poids' => json_encode([
                'plastiques_metaux' => $poidsPlastiquesMetaux,
                'papiers_cartons' => $poidsPapiersCartons,
                'organiques' => $poidsOrganique,
                'autres' => $poidsResiduel
            ])
        ]);

        // ============================================================
        // 🆕 INCÉMENTER LES STOCKS PAR CATÉGORIE
        // ============================================================
        $categories = CategorieDechet::where('est_actif', true)->get();

        foreach ($categories as $categorie) {
            $poids = 0;

            // Correspondance entre les champs du formulaire et les catégories
            switch ($categorie->nom) {
                case 'Plastique':
                    $poids = $poidsPlastiquesMetaux;
                    break;
                case 'Papier / Carton':
                    $poids = $poidsPapiersCartons;
                    break;
                case 'Organique':
                    $poids = $poidsOrganique;
                    break;
                case 'Résiduel':
                    $poids = $poidsResiduel;
                    break;
                // Ajoutez d'autres cas si vous avez des champs spécifiques
                default:
                    $poids = 0;
                    break;
            }

            if ($poids > 0) {
                StockDechet::incrementer($categorie->id, $poids);
            }
        }

        // Ajouter les points au client
        $client->ajouterPoints((int)round($points));

        Notification::create([
            'user_id' => $client->id,
            'titre' => ' Collecte effectuée',
            'message' => "Votre collecte a été réalisée. Vous avez gagné " . (int)round($points) . " points.",
            'type' => 'collecte',
            'est_lu' => false
        ]);

        return redirect()->route('collecteur.tournee')->with('success', 'Collecte enregistrée avec succès !');
    }

    public function enregistrerCollectePage()
    {
        $collecteur = Auth::user();

        $clients = User::whereIn('role', ['menage', 'entreprise'])
            ->where('quartier', $collecteur->quartier)
            ->whereHas('collectes', function ($query) {
                $query->whereDate('date_collecte', today())
                    ->where('statut', 'planifiee');
            })
            ->with(['collectes' => function ($query) {
                $query->whereDate('date_collecte', today())
                    ->where('statut', 'planifiee');
            }])
            ->get();

        return view('collecteur.selectionner-collecte', compact('clients'));
    }

    public function activerKitPage()
    {
        return redirect()->route('collecteur.scanner');
    }

    public function activerKitParScan($code)
    {
        $kit = KitTri::where('code_unique', $code)->first();

        if (!$kit) {
            return redirect()->route('collecteur.dashboard')->with('error', ' Code de kit invalide.');
        }

        if ($kit->statut === 'actif') {
            return redirect()->route('collecteur.dashboard')->with('error', ' Ce kit est déjà activé.');
        }

        if ($kit->statut !== 'en_attente') {
            return redirect()->route('collecteur.dashboard')->with('error', ' Ce kit n\'est pas en attente de livraison.');
        }

        $menage = User::find($kit->user_id);

        if (!$menage) {
            return redirect()->route('collecteur.dashboard')->with('error', ' Aucun ménage associé à ce kit.');
        }

        if ($menage->kitTri && $menage->kitTri->statut === 'actif') {
            return redirect()->route('collecteur.dashboard')->with('error', ' Ce ménage a déjà un kit actif.');
        }

        $kit->update([
            'statut' => 'actif',
            'date_distribution' => now(),
            'date_activation' => now()
        ]);

        $menage->statut_compte = 'essai_15j';
        $menage->date_debut_essai = now();
        $menage->date_fin_essai = now()->addDays(15);
        $menage->save();

        Notification::create([
            'user_id' => $menage->id,
            'titre' => 'Votre kit est activé !',
            'message' => 'Votre kit de tri a été activé par le collecteur. Profitez de 15 jours d\'essai gratuit.',
            'type' => 'kit',
            'est_lu' => false
        ]);

        return view('collecteur.activation-succes', compact('kit', 'menage'));
    }

    public function scannerPage()
    {
        return view('collecteur.scanner');
    }

    public function activerKit(Request $request)
    {
        $request->validate([
            'code_kit' => 'required|string'
        ]);

        $kit = KitTri::where('code_unique', $request->code_kit)->first();

        if (!$kit) {
            return response()->json(['error' => 'Kit introuvable.'], 404);
        }

        if ($kit->statut !== 'en_attente') {
            return response()->json(['error' => 'Ce kit n\'est pas en attente de livraison.'], 400);
        }

        if (!$kit->user_id) {
            return response()->json(['error' => 'Ce kit n\'est associé à aucun ménage.'], 400);
        }

        $menage = User::find($kit->user_id);

        if (!$menage) {
            return response()->json(['error' => 'Le ménage associé n\'existe pas.'], 404);
        }

        if ($menage->kitTri && $menage->kitTri->statut === 'actif') {
            return response()->json(['error' => 'Ce ménage a déjà un kit actif.'], 400);
        }

        $kit->statut = 'actif';
        $kit->date_distribution = now();
        $kit->date_activation = now();
        $kit->save();

        $menage->statut_compte = 'essai_15j';
        $menage->date_debut_essai = now();
        $menage->date_fin_essai = now()->addDays(15);
        $menage->save();

        Notification::create([
            'user_id' => $menage->id,
            'titre' => ' Votre kit est activé !',
            'message' => 'Votre kit de tri a été livré et activé. Profitez de 15 jours d\'essai gratuit.',
            'type' => 'kit',
            'est_lu' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kit activé avec succès !',
            'menage' => $menage->prenom . ' ' . $menage->nom
        ]);
    }

    public function kitsALivrer()
    {
        $kits = KitTri::where('statut', 'en_attente')
            ->whereHas('user', function ($query) {
                $query->whereIn('role', ['menage', 'entreprise']);
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('collecteur.kits-a-livrer', compact('kits'));
    }

    public function recompenses()
    {
        $recompenses = Recompense::disponibles()
            ->orderBy('points_requis', 'asc')
            ->paginate(12);

        return view('collecteur.recompenses', compact('recompenses'));
    }

    public function primes()
    {
        $collecteur = Auth::user()->collecteur;

        $primes = PrimeCollecteur::where('collecteur_id', $collecteur->id)
            ->orderBy('annee', 'desc')
            ->orderBy('mois', 'desc')
            ->paginate(12);

        $totalPrimes = PrimeCollecteur::where('collecteur_id', $collecteur->id)
            ->where('statut', 'valide')
            ->sum('montant_total');

        return view('collecteur.primes', compact('primes', 'totalPrimes'));
    }

    public function historique(Request $request)
    {
        $collecteur = Auth::user()->collecteur;

        $query = Collecte::where('collecteur_id', $collecteur->id)->with('user');

        if ($request->filled('date_debut')) {
            $query->whereDate('date_collecte', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_collecte', '<=', $request->date_fin);
        }
        if ($request->filled('client')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->client . '%')
                    ->orWhere('prenom', 'like', '%' . $request->client . '%');
            });
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $collectes = $query->orderBy('date_collecte', 'desc')->paginate(15);

        $stats = [
            'total' => Collecte::where('collecteur_id', $collecteur->id)->count(),
            'total_poids' => Collecte::where('collecteur_id', $collecteur->id)->sum('poids_recyclable') +
                Collecte::where('collecteur_id', $collecteur->id)->sum('poids_organique') +
                Collecte::where('collecteur_id', $collecteur->id)->sum('poids_residuel'),
            'total_points' => Collecte::where('collecteur_id', $collecteur->id)->sum('points_obtenus'),
        ];

        return view('collecteur.historique', compact('collectes', 'stats'));
    }

    public function statistiques()
    {
        $collecteur = Auth::user()->collecteur;
        $collecteurId = $collecteur->id;

        $totalCollectes = Collecte::where('collecteur_id', $collecteurId)->count();

        $totalPoids = Collecte::where('collecteur_id', $collecteurId)
            ->selectRaw('SUM(poids_recyclable) as recyclable,
                     SUM(poids_organique) as organique,
                     SUM(poids_residuel) as residuel,
                     SUM(poids_recyclable + poids_organique + poids_residuel) as total')
            ->first();

        $collectesParMois = Collecte::where('collecteur_id', $collecteurId)
            ->selectRaw('MONTH(date_collecte) as mois, COUNT(*) as total')
            ->whereYear('date_collecte', now()->year)
            ->groupBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        $totalPoints = Collecte::where('collecteur_id', $collecteurId)->sum('points_obtenus');

        $topClients = Collecte::where('collecteur_id', $collecteurId)
            ->with('user')
            ->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $dernieresCollectes = Collecte::where('collecteur_id', $collecteurId)
            ->with('user')
            ->orderBy('date_collecte', 'desc')
            ->limit(10)
            ->get();

        return view('collecteur.statistiques', compact(
            'totalCollectes',
            'totalPoids',
            'collectesParMois',
            'totalPoints',
            'topClients',
            'dernieresCollectes'
        ));
    }

    public function creerAnomalie($id)
    {
        $client = User::findOrFail($id);
        return view('collecteur.anomalie-creer', compact('client'));
    }

    public function storeAnomalie(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type_anomalie' => 'required|string|in:acces_impossible,absence_tri,dechet_dangereux,client_absent,autre',
            'description' => 'required|string|max:500',
            'photo' => 'nullable|image|max:2048'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('anomalies', 'public');
        }

        $anomalie = Anomalie::create([
            'collecteur_id' => Auth::user()->collecteur->id,
            'user_id' => $request->user_id,
            'type' => $request->type_anomalie,
            'description' => $request->description,
            'photo' => $photoPath,
            'statut' => 'en_attente',
            'date_signalement' => now(),
        ]);

        return redirect()->route('collecteur.tournee')
            ->with('success', 'Anomalie signalée avec succès !');
    }
}
