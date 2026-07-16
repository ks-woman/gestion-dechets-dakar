<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Collecte;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Statistiques réelles
        $menages = User::where('role', 'menage')->count();
        $collecteurs = User::where('role', 'collecteur')->count();
        $collectes = Collecte::where('statut', 'realisee')->count();

        // Poids total des déchets valorisés (collectes réalisées ou valorisées)
        $poidsRecyclable = Collecte::whereIn('statut', ['realisee', 'valorisee'])->sum('poids_recyclable');
        $poidsOrganique = Collecte::whereIn('statut', ['realisee', 'valorisee'])->sum('poids_organique');
        $poidsResiduel = Collecte::whereIn('statut', ['realisee', 'valorisee'])->sum('poids_residuel');
        $poidsTotal = $poidsRecyclable + $poidsOrganique + $poidsResiduel;

        // Convertir en tonnes (si besoin)
        $tonnes = number_format($poidsTotal / 1000, 1);

        return view('home', compact('menages', 'collecteurs', 'collectes', 'tonnes', 'poidsTotal'));
    }
}
