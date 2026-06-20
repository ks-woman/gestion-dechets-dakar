<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Collecte;
use App\Models\KitTri;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

        // Vérifier si l'utilisateur a un kit actif
        $kit = $user->kitTri;

        if (!$kit || $kit->statut !== 'actif') {
            return redirect()->back()->with('error', 'Vous devez d\'abord activer votre kit de tri pour demander une collecte.');
        }

        // Vérifier si l'utilisateur peut demander une collecte
        $estEnPeriodeEssai = $user->estEnPeriodeEssai();

        if (!$estEnPeriodeEssai && !$user->estAbonneActif()) {
            return redirect()->back()->with('error', 'Vous devez être en période d\'essai ou abonné actif pour demander une collecte.');
        }

        // ... reste du code (cette méthode n'est pas terminée dans votre version)
        return redirect()->back()->with('error', 'Cette fonctionnalité n\'est pas encore implémentée.');
    }

    // Afficher la tournée du collecteur
    public function tournee()
    {
        $collecteur = Auth::user();

        // Récupérer les clients du quartier qui ont une collecte planifiée aujourd'hui
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

        return view('collecteur.tournee', compact('clients'));
    }

    // Afficher le formulaire d'enregistrement pour un client
    public function formEnregistrerCollecte($id)
    {
        $client = User::findOrFail($id);
        return view('collecteur.enregistrer-collecte', compact('client'));
    }

    // Enregistrer la collecte (avec vérification anti-doublon)
    public function enregistrerCollecte(Request $request)
    {
        $collecteur = Auth::user();
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
        $poidsPlastiquesMetaux = $request->plastiques_metaux ?? 0;
        $poidsPapiersCartons = $request->papiers_cartons ?? 0;
        $poidsRecyclable = $poidsPlastiquesMetaux + $poidsPapiersCartons;
        $poidsOrganique = $request->organiques ?? 0;
        $poidsResiduel = $request->autres ?? 0;
        $points = ($poidsRecyclable * 1) + ($poidsOrganique * 0.5);

        // Mettre à jour la collecte existante
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

        // Ajouter les points au client
        $client->ajouterPoints((int)round($points));

        // Notification au client
        Notification::create([
            'user_id' => $client->id,
            'titre' => '✅ Collecte effectuée',
            'message' => "Votre collecte a été réalisée. Vous avez gagné " . (int)round($points) . " points. Poids total : " . ($poidsRecyclable + $poidsOrganique + $poidsResiduel) . " kg.",
            'type' => 'collecte',
            'est_lu' => false
        ]);

        return redirect()->route('collecteur.tournee')->with('success', 'Collecte enregistrée avec succès !');
    }

    // Page pour sélectionner le client avant d'enregistrer une collecte
    public function enregistrerCollectePage()
    {
        $collecteur = Auth::user();

        // Récupérer les clients du quartier qui ont une collecte planifiée aujourd'hui
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

    // Page pour activer un kit (redirige vers le scanner)
    public function activerKitPage()
    {
        return redirect()->route('collecteur.scanner');
    }

    // Activer un kit via la route GET (scan direct)
    public function activerKitParScan($code)
    {
        $kit = KitTri::where('code_unique', $code)->first();

        if (!$kit) {
            return redirect()->route('collecteur.dashboard')->with('error', '❌ Code de kit invalide.');
        }

        if ($kit->statut === 'actif') {
            return redirect()->route('collecteur.dashboard')->with('error', '⚠️ Ce kit est déjà activé.');
        }

        if ($kit->statut !== 'en_attente') {
            return redirect()->route('collecteur.dashboard')->with('error', '⚠️ Ce kit n\'est pas en attente de livraison.');
        }

        $menage = User::find($kit->user_id);

        if (!$menage) {
            return redirect()->route('collecteur.dashboard')->with('error', '❌ Aucun ménage associé à ce kit.');
        }

        // Vérifier si le ménage n'a pas déjà un kit actif
        if ($menage->kitTri && $menage->kitTri->statut === 'actif') {
            return redirect()->route('collecteur.dashboard')->with('error', '⚠️ Ce ménage a déjà un kit actif.');
        }

        // Activer le kit
        $kit->update([
            'statut' => 'actif',
            'date_distribution' => now(),
            'date_activation' => now()
        ]);

        // Mettre le ménage en période d'essai
        $menage->statut_compte = 'essai_15j';
        $menage->date_debut_essai = now();
        $menage->date_fin_essai = now()->addDays(15);
        $menage->save();

        // Notifier le client
        Notification::create([
            'user_id' => $menage->id,
            'titre' => '✅ Votre kit est activé !',
            'message' => 'Votre kit de tri a été activé par le collecteur. Profitez de 15 jours d\'essai gratuit.',
            'type' => 'kit',
            'est_lu' => false
        ]);

        return view('collecteur.activation-succes', compact('kit', 'menage'));
    }

    // Page du scanner
    public function scannerPage()
    {
        return view('collecteur.scanner');
    }

    // Activer un kit via AJAX (POST)
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

        // Vérifier si le ménage n'a pas déjà un kit actif
        if ($menage->kitTri && $menage->kitTri->statut === 'actif') {
            return response()->json(['error' => 'Ce ménage a déjà un kit actif.'], 400);
        }

        // Activer le kit
        $kit->statut = 'actif';
        $kit->date_distribution = now();
        $kit->date_activation = now();
        $kit->save();

        // Mettre le ménage en période d'essai
        $menage->statut_compte = 'essai_15j';
        $menage->date_debut_essai = now();
        $menage->date_fin_essai = now()->addDays(15);
        $menage->save();

        // Notification
        Notification::create([
            'user_id' => $menage->id,
            'titre' => '✅ Votre kit est activé !',
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
}
