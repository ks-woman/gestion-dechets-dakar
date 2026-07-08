<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorieDechet;
use Illuminate\Http\Request;

class CategorieDechetController extends Controller
{
    public function index()
    {
        $categories = CategorieDechet::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:50|unique:categories_dechet',
            'icone' => 'nullable|string|max:10',
            'couleur' => 'nullable|string|max:20',
            'est_actif' => 'boolean',
        ]);

        $categorie = CategorieDechet::create($request->all());

        // Créer automatiquement un stock pour cette catégorie
        \App\Models\StockDechet::create([
            'categorie_id' => $categorie->id,
            'quantite' => 0,
            'prix_unitaire' => 0,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit($id)
    {
        $categorie = CategorieDechet::findOrFail($id);
        return view('admin.categories.edit', compact('categorie'));
    }

    public function update(Request $request, $id)
    {
        $categorie = CategorieDechet::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:50|unique:categories_dechet,nom,' . $id,
            'icone' => 'nullable|string|max:10',
            'couleur' => 'nullable|string|max:20',
            'est_actif' => 'boolean',
        ]);

        $categorie->update($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour.');
    }

    public function destroy($id)
    {
        $categorie = CategorieDechet::findOrFail($id);

        // Supprimer le stock associé
        if ($categorie->stock) {
            $categorie->stock->delete();
        }
        $categorie->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée.');
    }
}
