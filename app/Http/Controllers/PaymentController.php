<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class PaymentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function souscrire(Request $request)
    {
        $user = Auth::user();

        if ($user->abonnement && $user->abonnement->statut === 'actif') {
            return redirect()->back()->with('error', 'Vous êtes déjà abonné.');
        }

        $mode = $request->input('mode_paiement', 'paiementMobile');
        $service = new PaymentService();

        // Si c'est une simulation
        if ($mode === 'simulation') {
            $service->simulerPaiement($user, 'paiementMobile');
            return redirect()->route('abonnement.index')->with('success', ' Abonnement activé (simulation)');
        }

        $result = $service->initierPaiement($user, 5000, $mode);

        if ($result['redirect_url']) {
            return redirect()->away($result['redirect_url']);
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'initiation du paiement.');
    }

    public function callback(Request $request)
    {
        $gateway = $request->input('gateway', 'wave');
        $service = new PaymentService();
        $paiement = $service->traiterCallback($request->all(), $gateway);

        if ($paiement && $paiement->statut === 'valide') {
            return redirect()->route('abonnement.index')->with('success', ' Paiement validé, votre abonnement est actif.');
        }

        return redirect()->route('abonnement.index')->with('error', ' Paiement non validé. Veuillez réessayer.');
    }

    public function webhook(Request $request, $gateway)
    {
        $service = new PaymentService();
        $paiement = $service->traiterCallback($request->all(), $gateway);

        return response()->json(['status' => 'ok']);
    }

    public function historique()
    {
        $paiements = Paiement::whereHas('abonnement', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('created_at', 'desc')->paginate(15);

        return view('payment.historique', compact('paiements'));
    }
}
