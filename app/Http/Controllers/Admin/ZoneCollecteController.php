<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use App\Models\ZoneCollecte;
use App\Models\Collecteur;
use Illuminate\Http\Request;

class ZoneCollecteController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $zones = ZoneCollecte::with('collecteurs')->orderBy('nom')->paginate(15);
        return view('admin.zones.index', compact('zones'));
    }

    public function create()
    {
        $collecteurs = Collecteur::with('user')->get();
        // Liste des quartiers de Dakar (vous pouvez l'enrichir)
        $quartiersDisponibles = [
            'Pikine',
            'Guediawaye',
            'Yoff',
            'Parcelles Assainies',
            'Grand Yoff',
            'Ouakam',
            'Ngor',
            'Almadies',
            'Mermoz',
            'Fann',
            'Point E',
            'Sicap',
            'Mbao',
            'Nord Foire',
            'Keur Massar'
        ];
        return view('admin.zones.create', compact('collecteurs', 'quartiersDisponibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100|unique:zones_collecte',
            'description' => 'nullable|string',
            'quartiers' => 'required|array|min:1',
            'collecteurs' => 'nullable|array',
        ]);

        $zone = ZoneCollecte::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'quartiers' => $request->quartiers,
        ]);

        if ($request->filled('collecteurs')) {
            $zone->collecteurs()->sync($request->collecteurs);
        }

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone créée avec succès.');
    }

    public function show($id)
    {
        $zone = ZoneCollecte::with('collecteurs.user')->findOrFail($id);
        $clients = $zone->clients()->paginate(10);
        return view('admin.zones.show', compact('zone', 'clients'));
    }

    public function edit($id)
    {
        $zone = ZoneCollecte::with('collecteurs')->findOrFail($id);
        $collecteurs = Collecteur::with('user')->get();
        $quartiersDisponibles = [
            'Pikine',
            'Guediawaye',
            'Yoff',
            'Parcelles Assainies',
            'Grand Yoff',
            'Ouakam',
            'Ngor',
            'Almadies',
            'Mermoz',
            'Fann',
            'Point E',
            'Sicap',
            'Mbao',
            'Keur Massar'
        ];
        return view('admin.zones.edit', compact('zone', 'collecteurs', 'quartiersDisponibles'));
    }

    public function update(Request $request, $id)
    {
        $zone = ZoneCollecte::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:100|unique:zones_collecte,nom,' . $id,
            'description' => 'nullable|string',
            'quartiers' => 'required|array|min:1',
            'collecteurs' => 'nullable|array',
        ]);

        $zone->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'quartiers' => $request->quartiers,
        ]);

        if ($request->filled('collecteurs')) {
            $zone->collecteurs()->sync($request->collecteurs);
        } else {
            $zone->collecteurs()->detach();
        }

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone mise à jour.');
    }

    public function destroy($id)
    {
        $zone = ZoneCollecte::findOrFail($id);
        $zone->collecteurs()->detach();
        $zone->delete();

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone supprimée.');
    }
}
