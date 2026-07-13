<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paiement;
use App\Services\PaiementService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class AbonnementController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $abonnement = $user->abonnement;
        $paiements = Paiement::whereHas('abonnement', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('created_at', 'desc')->take(10)->get();

        return view('abonnement.index', compact('user', 'abonnement', 'paiements'));
    }

    public function souscrire(Request $request)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est déjà abonné
        if ($user->abonnement && $user->abonnement->estActif()) {
            return redirect()->back()->with('error', 'Vous êtes déjà abonné.');
        }

        $service = new PaiementService();
        $paiement = $service->simulerPaiement($user, 'wave');

        return redirect()->route('abonnement.index')->with('success', '✅ Votre abonnement est activé !');
    }

    public function annuler()
    {
        $user = Auth::user();
        $abonnement = $user->abonnement;
        if ($abonnement) {
            $abonnement->statut = 'resilie';
            $abonnement->save();
        }
        $user->statut_compte = 'inactif';
        $user->abonnement_statut = 'inactif';
        $user->save();

        Notification::create([
            'user_id' => $user->id,
            'titre' => ' Abonnement annulé',
            'message' => 'Votre abonnement a été annulé. Vous pouvez le réactiver à tout moment.',
            'type' => 'abonnement',
            'est_lu' => false,
        ]);

        return redirect()->route('abonnement.index')->with('success', 'Votre abonnement a été annulé.');
    }

    public function historique()
    {
        $user = Auth::user();
        $paiements = Paiement::whereHas('abonnement', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('created_at', 'desc')->paginate(15);

        return view('abonnement.historique', compact('paiements'));
    }
}
