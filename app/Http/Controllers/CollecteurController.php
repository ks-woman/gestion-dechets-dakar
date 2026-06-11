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

        // ... reste du code
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
        $poidsRecyclable = $request->plastiques_metaux + $request->papiers_cartons;
        $poidsOrganique = $request->organiques;
        $poidsResiduel = $request->autres;
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

        // Ajouter les points au client
        $client->ajouterPoints($points);

        // Notification au client
        Notification::create([
            'user_id' => $client->id,
            'titre' => ' Collecte effectuée',
            'message' => "Votre collecte a été réalisée. Vous avez gagné " . (int)$points . " points.",
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

    // Page pour activer un kit
    public function activerKitPage()
    {
        $user = Auth::user();
        $kit = $user->kitTri;

        return view('collecteur.activer-kit', compact('kit'));
    }

    // Activer un kit par scan du QR code
    public function activerKitParScan($code)
    {
        // Trouver le kit par son code unique
        $kit = KitTri::where('code_unique', $code)->first();

        if (!$kit) {
            return redirect()->route('collecteur.dashboard')->with('error', '❌ Code de kit invalide.');
        }

        // Vérifier si le kit est déjà activé
        if ($kit->statut === 'actif') {
            return redirect()->route('collecteur.dashboard')->with('error', '⚠️ Ce kit est déjà activé.');
        }

        // Vérifier si le kit est bien en attente de livraison
        if ($kit->statut !== 'en_attente_livraison') {
            return redirect()->route('collecteur.dashboard')->with('error', '⚠️ Ce kit n\'est pas en attente de livraison.');
        }

        // Récupérer le client
        $client = $kit->user;

        // Activer le kit
        $kit->update([
            'statut' => 'actif',
            'collecteur_id' => Auth::id(),
            'date_distribution' => now(),
            'date_activation' => now()
        ]);

        // Mettre à jour le statut du client (15 jours d'essai)
        $client->statut_compte = 'essai_15j';
        $client->save();

        // Notifier le client que son kit est activé
        Notification::create([
            'user_id' => $client->id,
            'titre' => '✅ Votre kit est activé !',
            'message' => 'Votre kit de tri a été activé par le collecteur. Profitez de 15 jours d\'essai gratuit.',
            'type' => 'kit',
            'est_lu' => false
        ]);

        // Rediriger vers une page de confirmation
        return view('collecteur.activation-succes', compact('kit', 'client'));
    }

    public function scannerPage()
    {
        return view('collecteur.scanner');
    }
}
