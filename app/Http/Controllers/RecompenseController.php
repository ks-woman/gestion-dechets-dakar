<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recompense;
use Illuminate\Http\Request;

class RecompenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $recompenses = Recompense::orderBy('points_requis', 'asc')->paginate(15);
        return view('admin.recompenses.index', compact('recompenses'));
    }

    public function create()
    {
        return view('admin.recompenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_recompense' => 'required|string|max:100',
            'points_requis' => 'required|integer|min:1',
            'type_recompense' => 'required|in:bon_achat,article_physique,reduction,cadeau',
            'description' => 'nullable|string',
            'valeur' => 'nullable|numeric',
            'quantite_disponible' => 'required|integer|min:0',
            'date_expiration' => 'nullable|date|after:today',
        ]);

        Recompense::create($request->all());

        return redirect()->route('admin.recompenses.index')
            ->with('success', 'Récompense créée avec succès !');
    }

    public function edit($id)
    {
        $recompense = Recompense::findOrFail($id);
        return view('admin.recompenses.edit', compact('recompense'));
    }

    public function update(Request $request, $id)
    {
        $recompense = Recompense::findOrFail($id);

        $request->validate([
            'nom_recompense' => 'required|string|max:100',
            'points_requis' => 'required|integer|min:1',
            'type_recompense' => 'required|in:bon_achat,article_physique,reduction,cadeau',
            'description' => 'nullable|string',
            'valeur' => 'nullable|numeric',
            'quantite_disponible' => 'required|integer|min:0',
            'date_expiration' => 'nullable|date',
        ]);

        $recompense->update($request->all());

        return redirect()->route('admin.recompenses.index')
            ->with('success', 'Récompense modifiée avec succès !');
    }

    public function destroy($id)
    {
        $recompense = Recompense::findOrFail($id);
        $recompense->delete();

        return redirect()->route('admin.recompenses.index')
            ->with('success', 'Récompense supprimée avec succès !');
    }
}
