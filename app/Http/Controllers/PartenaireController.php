<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class PartenaireController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('partenaire');
    }

    // Dashboard partenaire
    public function dashboard()
    {
        // Statistiques
        $totalCollectes = Collecte::where('statut', 'valorisee')->count();
        $totalPoids = Collecte::where('statut', 'valorisee')->sum('poids_recyclable') +
            Collecte::where('statut', 'valorisee')->sum('poids_organique') +
            Collecte::where('statut', 'valorisee')->sum('poids_residuel');
        $totalPoints = Collecte::where('statut', 'valorisee')->sum('points_obtenus');
        $enAttente = Collecte::where('statut', 'realisee')->count();

        // Dernières collectes reçues
        $dernieresCollectes = Collecte::whereIn('statut', ['realisee', 'valorisee'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('partenaire.dashboard', compact('totalCollectes', 'totalPoids', 'totalPoints', 'enAttente', 'dernieresCollectes'));
    }

    // Liste des déchets reçus (à valoriser)
    public function dechetsRecus()
    {
        $collectes = Collecte::whereIn('statut', ['realisee', 'valorisee'])
            ->with('user')
            ->orderBy('date_collecte', 'desc')
            ->paginate(15);

        return view('partenaire.dechets', compact('collectes'));
    }

    // Valider la réception des déchets
    public function validerReception($id)
    {
        $collecte = Collecte::findOrFail($id);

        if ($collecte->statut !== 'realisee') {
            return redirect()->back()->with('error', 'Cette collecte ne peut pas être validée.');
        }

        $collecte->statut = 'valorisee';
        $collecte->partenaire_id = Auth::id();
        $collecte->date_reception = now();
        $collecte->save();

        return redirect()->back()->with('success', 'Réception validée avec succès !');
    }

    // Statistiques du partenaire
    public function statistiques()
    {
        $collectesParMois = Collecte::where('statut', 'valorisee')
            ->selectRaw('MONTH(date_reception) as mois, COUNT(*) as total')
            ->whereYear('date_reception', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        $moisKeys = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $collectesParMoisValues = [];
        for ($i = 1; $i <= 12; $i++) {
            $collectesParMoisValues[] = $collectesParMois[$i] ?? 0;
        }

        $totalRecyclable = Collecte::where('statut', 'valorisee')->sum('poids_recyclable');
        $totalOrganique = Collecte::where('statut', 'valorisee')->sum('poids_organique');
        $totalResiduel = Collecte::where('statut', 'valorisee')->sum('poids_residuel');

        return view('partenaire.statistiques', compact('moisKeys', 'collectesParMoisValues', 'totalRecyclable', 'totalOrganique', 'totalResiduel'));
    }
}
