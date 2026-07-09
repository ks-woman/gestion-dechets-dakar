<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockDechet;
use Illuminate\Http\Request;

class StockDechetController extends Controller
{
    public function index()
    {
        //  Charger la relation 'categorie'
        $stocks = StockDechet::with('categorie')->get();
        return view('admin.stocks.index', compact('stocks'));
    }

    public function edit($id)
    {
        $stock = StockDechet::findOrFail($id);
        return view('admin.stocks.edit', compact('stock'));
    }

    public function update(Request $request, $id)
    {
        $stock = StockDechet::findOrFail($id);

        $request->validate([
            'quantite' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
        ]);

        $stock->update([
            'quantite' => $request->quantite,
            'prix_unitaire' => $request->prix_unitaire,
        ]);

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stock mis à jour.');
    }
}
