<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\User;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    public function __construct()
    {
        // Some base Controller implementations may not provide the middleware() helper.
        if (method_exists($this, 'middleware')) {
            $this->middleware(['auth', 'admin']);
        }
    }

    /**
     * Liste des abonnements avec filtres
     */
    public function index(Request $request)
    {
        $query = Abonnement::with('user');

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par utilisateur (recherche)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $abonnements = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => Abonnement::count(),
            'essai' => Abonnement::where('statut', 'essai')->count(),
            'actif' => Abonnement::where('statut', 'actif')->count(),
            'expire' => Abonnement::where('statut', 'expire')->count(),
            'resilie' => Abonnement::where('statut', 'resilie')->count(),
        ];

        return view('admin.abonnements.index', compact('abonnements', 'stats'));
    }

    /**
     * Détail d'un abonnement
     */
    public function show($id)
    {
        $abonnement = Abonnement::with(['user', 'paiements'])->findOrFail($id);
        return view('admin.abonnements.show', compact('abonnement'));
    }

    /**
     * Mettre à jour le statut d'un abonnement
     */
    public function update(Request $request, $id)
    {
        $abonnement = Abonnement::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:essai,actif,expire,resilie',
        ]);

        $ancienStatut = $abonnement->statut;
        $abonnement->statut = $request->statut;
        $abonnement->save();

        // Mettre à jour le statut du compte utilisateur
        $user = $abonnement->user;
        if ($user) {
            switch ($request->statut) {
                case 'actif':
                    $user->statut_compte = 'abonne_actif';
                    $user->abonnement_statut = 'actif';
                    break;
                case 'essai':
                    $user->statut_compte = 'essai_15j';
                    $user->abonnement_statut = 'essai';
                    break;
                case 'expire':
                    $user->statut_compte = 'en_attente_paiement';
                    $user->abonnement_statut = 'expire';
                    break;
                case 'resilie':
                    $user->statut_compte = 'inactif';
                    $user->abonnement_statut = 'inactif';
                    break;
            }
            $user->save();
        }

        // Notification à l'utilisateur (optionnel)
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'titre' => ' Mise à jour de votre abonnement',
            'message' => "Votre abonnement est passé de '{$ancienStatut}' à '{$request->statut}' par l'administrateur.",
            'type' => 'abonnement',
            'est_lu' => false,
        ]);

        return redirect()->route('admin.abonnements.index')
            ->with('success', "Statut de l'abonnement mis à jour avec succès.");
    }

    /**
     * Activer manuellement un abonnement (bouton rapide)
     */
    public function activer($id)
    {
        $abonnement = Abonnement::findOrFail($id);
        $abonnement->statut = 'actif';
        $abonnement->date_debut_abonnement = now();
        $abonnement->save();

        $user = $abonnement->user;
        if ($user) {
            $user->statut_compte = 'abonne_actif';
            $user->abonnement_statut = 'actif';
            $user->save();
        }

        return redirect()->back()->with('success', 'Abonnement activé avec succès.');
    }
}
