<?php

namespace App\Http\Controllers;

use App\Models\Recompense;
use App\Models\EchangeRecompense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecompenseUserController extends Controller
{
    // Le constructeur avec $this->middleware() est supprimé car les middlewares sont gérés dans les routes
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // Catalogue pour ménage/entreprise
    public function catalogue()
    {
        $user = Auth::user();

        $recompenses = Recompense::where('quantite_disponible', '>', 0)
            ->where(function ($query) {
                $query->whereNull('date_expiration')
                    ->orWhere('date_expiration', '>=', now());
            })
            ->orderBy('points_requis', 'asc')
            ->paginate(12);

        // ✅ Utiliser la relation définie dans User
        $totalEchanges = $user->echangeRecompenses()->count();
        $totalPointsDepenses = $user->echangeRecompenses()->sum('points_utilises');

        return view('recompenses.catalogue', compact('recompenses', 'totalEchanges', 'totalPointsDepenses'));
    }
    // Détail d'une récompense
    public function show($id)
    {
        $recompense = Recompense::findOrFail($id);
        return view('recompenses.show', compact('recompense'));
    }

    // Échanger des points
    public function echanger(Request $request, $id)
    {
        $user = Auth::user();
        $recompense = Recompense::findOrFail($id);

        if ($recompense->quantite_disponible <= 0) {
            return redirect()->back()->with('error', 'Cette récompense n\'est plus disponible.');
        }

        if ($recompense->date_expiration && $recompense->date_expiration < now()) {
            return redirect()->back()->with('error', 'Cette récompense a expiré.');
        }

        if ($user->score_total < $recompense->points_requis) {
            return redirect()->back()->with('error', 'Vous n\'avez pas assez de points. Il vous manque ' . ($recompense->points_requis - $user->score_total) . ' points.');
        }

        EchangeRecompense::create([
            'user_id' => $user->id,
            'recompense_id' => $recompense->id,
            'points_utilises' => $recompense->points_requis,
            'date_echange' => now(),
            'statut' => 'valide'
        ]);

        $user->score_total -= $recompense->points_requis;
        $user->save();

        $recompense->quantite_disponible -= 1;
        $recompense->save();

        return redirect()->route('recompenses.catalogue')
            ->with('success', 'Félicitations ! Vous avez échangé vos points contre "' . $recompense->nom_recompense . '" !');
    }

    // Historique des échanges
    public function historique()
    {
        $user = Auth::user();

        $echanges = EchangeRecompense::where('user_id', $user->id)
            ->with('recompense')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('recompenses.historique', compact('echanges'));
    }

    // Catalogue pour le collecteur (lecture seule)
    public function collecteurCatalogue()
    {
        $recompenses = Recompense::where('quantite_disponible', '>', 0)
            ->where(function ($query) {
                $query->whereNull('date_expiration')
                    ->orWhere('date_expiration', '>=', now());
            })
            ->orderBy('points_requis', 'asc')
            ->paginate(12);

        return view('collecteur.recompenses', compact('recompenses'));
    }
}
