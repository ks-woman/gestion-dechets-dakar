<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Reclamation;
use App\Models\Notification;
use Illuminate\Http\Request;

class ReclamationController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Reclamation::with('user');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $reclamations = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Reclamation::count(),
            'ouvertes' => Reclamation::ouvertes()->count(),
            'en_cours' => Reclamation::enCours()->count(),
            'resolues' => Reclamation::resolues()->count(),
            'fermees' => Reclamation::fermees()->count(),
        ];

        return view('admin.reclamations.index', compact('reclamations', 'stats'));
    }

    public function show($id)
    {
        $reclamation = Reclamation::with('user')->findOrFail($id);
        return view('admin.reclamations.show', compact('reclamation'));
    }

    public function update(Request $request, $id)
    {
        $reclamation = Reclamation::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:ouverte,en_cours,resolue,fermee',
            'reponse' => 'nullable|string|max:1000',
        ]);

        $data = ['statut' => $request->statut];

        if ($request->filled('reponse')) {
            $data['reponse'] = $request->reponse;
        }

        if ($request->statut === 'resolue' && $reclamation->statut !== 'resolue') {
            $data['date_resolution'] = now();
        }

        $reclamation->update($data);

        // Créer une notification pour l'utilisateur
        Notification::create([
            'user_id' => $reclamation->user_id,
            'titre' => ' Mise à jour de votre réclamation',
            'message' => "Votre réclamation '{$reclamation->sujet}' a été mise à jour. Statut : " . $request->statut,
            'type' => 'reclamation',
            'est_lu' => false,
        ]);

        return redirect()->route('admin.reclamations.index')
            ->with('success', 'Réclamation mise à jour avec succès.');
    }
}
