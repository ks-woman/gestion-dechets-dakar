<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Commande;
use App\Models\Notification;
use App\Models\StockDechet;
use Illuminate\Http\Request;

class CommandeController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Liste des commandes avec filtres
     */
    public function index(Request $request)
    {
        $query = Commande::with(['partenaire', 'categorie']);

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par collecteur (commandes disponibles pour un collecteur)
        if ($request->filled('collecteur_id')) {
            $query->whereIn('statut', ['en_attente', 'validee'])
                ->whereNull('collecteur_id');
        }

        $commandes = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Commande::count(),
            'en_attente' => Commande::where('statut', 'en_attente')->count(),
            'validees' => Commande::where('statut', 'validee')->count(),
            'affectees' => Commande::where('statut', 'affectee')->count(),
            'livrees' => Commande::where('statut', 'livree')->count(),
            'annulees' => Commande::where('statut', 'annulee')->count(),
        ];

        return view('admin.commandes.index', compact('commandes', 'stats'));
    }

    /**
     * Afficher le détail d'une commande
     */
    public function show($id)
    {
        $commande = Commande::with(['partenaire', 'categorie', 'collecteur.user'])->findOrFail($id);
        return view('admin.commandes.show', compact('commande'));
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function update(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:en_attente,validee,affectee,livree,annulee',
        ]);

        $ancienStatut = $commande->statut;
        $commande->statut = $request->statut;

        if ($request->statut === 'livree' && $ancienStatut !== 'livree') {
            $commande->date_livraison = now();
        }

        // Si on annule, on remet le stock
        if ($request->statut === 'annulee' && $ancienStatut !== 'annulee') {
            StockDechet::incrementer($commande->categorie_id, $commande->quantite);
        }

        $commande->save();

        // Notifications selon le statut
        if ($request->statut === 'validee' && $ancienStatut === 'en_attente') {
            Notification::create([
                'user_id' => $commande->partenaire_id,
                'titre' => '✅ Commande validée',
                'message' => 'Votre commande #' . $commande->id . ' a été validée.',
                'type' => 'commande',
                'est_lu' => false,
            ]);
        }

        if ($request->statut === 'livree' && $ancienStatut !== 'livree') {
            Notification::create([
                'user_id' => $commande->partenaire_id,
                'titre' => '🚚 Commande livrée',
                'message' => 'Votre commande #' . $commande->id . ' a été livrée.',
                'type' => 'commande',
                'est_lu' => false,
            ]);
        }

        if ($request->statut === 'annulee' && $ancienStatut !== 'annulee') {
            Notification::create([
                'user_id' => $commande->partenaire_id,
                'titre' => '❌ Commande annulée',
                'message' => 'Votre commande #' . $commande->id . ' a été annulée.',
                'type' => 'commande',
                'est_lu' => false,
            ]);
        }

        return redirect()->route('admin.commandes.index')
            ->with('success', 'Statut de la commande mis à jour.');
    }
}
