<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Notification;
use App\Models\StockDechet;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $query = Commande::with('partenaire');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $commandes = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Commande::count(),
            'en_attente' => Commande::where('statut', 'en_attente')->count(),
            'validees' => Commande::where('statut', 'validee')->count(),
            'livrees' => Commande::where('statut', 'livree')->count(),
            'annulees' => Commande::where('statut', 'annulee')->count(),
        ];

        return view('admin.commandes.index', compact('commandes', 'stats'));
    }

    public function show($id)
    {
        $commande = Commande::with('partenaire')->findOrFail($id);
        return view('admin.commandes.show', compact('commande'));
    }

    public function update(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:en_attente,validee,livree,annulee',
        ]);

        $ancienStatut = $commande->statut;
        $commande->statut = $request->statut;

        if ($request->statut === 'livree' && $ancienStatut !== 'livree') {
            $commande->date_livraison = now();
        }

        // Si on annule, on remet le stock
        if ($request->statut === 'annulee' && $ancienStatut !== 'annulee') {
            StockDechet::incrementer($commande->type_dechet, $commande->quantite);
        }

        // Notifications
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

        $commande->save();

        return redirect()->route('admin.commandes.index')
            ->with('success', 'Commande mise à jour.');
    }
}
