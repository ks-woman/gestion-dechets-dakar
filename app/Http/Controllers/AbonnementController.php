<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    /**
     * Liste des abonnements
     */
    public function index()
    {
        $abonnements = Abonnement::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

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
        $abonnement = Abonnement::with('user', 'paiements')->findOrFail($id);
        return view('admin.abonnements.show', compact('abonnement'));
    }

    /**
     * Activer un abonnement
     */
    public function activer($id)
    {
        $abonnement = Abonnement::findOrFail($id);

        if ($abonnement->statut === 'actif') {
            return redirect()->back()->with('info', "Cet abonnement est déjà actif.");
        }

        $abonnement->statut = 'actif';
        $abonnement->date_debut_abonnement = now();
        $abonnement->save();

        // Mettre à jour le compte utilisateur
        $user = $abonnement->user;
        if ($user) {
            $user->statut_compte = 'abonne_actif';
            $user->save();
        }

        return redirect()->route('admin.abonnements.index')
            ->with('success', "✅ L'abonnement #{$id} a été activé avec succès.");
    }

    /**
     * Résilier un abonnement
     */
    public function resilier($id)
    {
        $abonnement = Abonnement::findOrFail($id);

        if ($abonnement->statut === 'resilie') {
            return redirect()->back()->with('info', "Cet abonnement est déjà résilié.");
        }

        $abonnement->statut = 'resilie';
        $abonnement->save();

        // Mettre à jour le compte utilisateur
        $user = $abonnement->user;
        if ($user) {
            $user->statut_compte = 'inactif';
            $user->save();
        }

        return redirect()->route('admin.abonnements.index')
            ->with('success', "⛔ L'abonnement #{$id} a été résilié.");
    }

    /**
     * Modifier le montant de l'abonnement
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'montant_mensuel' => 'required|numeric|min:1000',
        ]);

        $abonnement = Abonnement::findOrFail($id);
        $abonnement->montant_mensuel = $request->montant_mensuel;
        $abonnement->save();

        return redirect()->route('admin.abonnements.show', $id)
            ->with('success', "💰 Le montant a été mis à jour : " . number_format($request->montant_mensuel, 0) . " FCFA");
    }

    /**
     * Prolonger la période d'essai
     */
    public function prolonger(Request $request, $id)
    {
        $request->validate([
            'jours' => 'required|integer|min:1|max:90',
        ]);

        $abonnement = Abonnement::findOrFail($id);

        if ($abonnement->statut !== 'essai') {
            return redirect()->back()->with('error', "Seul un abonnement en période d'essai peut être prolongé.");
        }

        $abonnement->date_fin_essai = $abonnement->date_fin_essai->addDays($request->jours);
        $abonnement->save();

        // Mettre à jour le compte utilisateur
        $user = $abonnement->user;
        if ($user) {
            $user->date_fin_essai = $abonnement->date_fin_essai;
            $user->save();
        }

        return redirect()->route('admin.abonnements.show', $id)
            ->with('success', "⏰ La période d'essai a été prolongée de {$request->jours} jours.");
    }

    /**
     * Historique des paiements d'un abonnement
     */
    public function paiements($id)
    {
        $abonnement = Abonnement::with('paiements')->findOrFail($id);
        $paiements = $abonnement->paiements()->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.abonnements.paiements', compact('abonnement', 'paiements'));
    }
}
