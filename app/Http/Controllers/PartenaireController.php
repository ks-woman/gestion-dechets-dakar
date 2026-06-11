<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PartenaireController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('partenaire');
    }

    public function dashboard()
    {
        return view('partenaire.dashboard');
    }

    public function dechetsDisponibles()
    {
        $collectes = Collecte::where('statut', 'realisee')
            ->with('user')
            ->orderBy('date_collecte', 'desc')
            ->paginate(20);
        return view('partenaire.dechets', compact('collectes'));
    }

    public function validerReception($id)
    {
        $collecte = Collecte::findOrFail($id);

        if ($collecte->statut === 'realisee') {
            $collecte->statut = 'valorisee';
            $collecte->save();
            return back()->with('success', 'Réception validée avec succès');
        }

        return back()->with('error', 'Cette collecte ne peut pas être valorisée');
    }
}
