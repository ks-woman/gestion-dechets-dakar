<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends BaseController
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le formulaire de contact
     */
    public function create()
    {
        return view('reclamation.create');
    }

    /**
     * Envoyer une réclamation / message à l'admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'sujet' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Créer la réclamation
        $reclamation = Reclamation::create([
            'user_id' => $user->id,
            'sujet' => $request->sujet,
            'description' => $request->description,
            'statut' => 'ouverte',
        ]);

        // Notifier tous les administrateurs
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'titre' => ' Nouvelle réclamation de ' . $user->prenom . ' ' . $user->nom,
                'message' => 'Sujet : ' . $request->sujet . "\n" . substr($request->description, 0, 100) . '...',
                'type' => 'reclamation',
                'est_lu' => false,
            ]);
        }

        return redirect()->route('reclamation.merci')->with('success', 'Votre message a été envoyé à l\'administration. Nous vous répondrons dans les plus brefs délais.');
    }

    /**
     * Page de remerciement après envoi
     */
    public function merci()
    {
        return view('reclamation.merci');
    }
}
