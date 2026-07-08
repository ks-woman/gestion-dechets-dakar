<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use App\Models\Commande;
use App\Models\CertificatValorisation;
use App\Models\Notification;
use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\StockDechet;

class PartenaireController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('partenaire');
    }

    // =============================================
    // DASHBOARD
    // =============================================
    public function dashboard()
    {
        // Statistiques globales
        $totalCollectes = Collecte::where('statut', 'valorisee')->count();
        $totalPoids = Collecte::where('statut', 'valorisee')->sum('poids_recyclable') +
            Collecte::where('statut', 'valorisee')->sum('poids_organique') +
            Collecte::where('statut', 'valorisee')->sum('poids_residuel');
        $totalPoints = Collecte::where('statut', 'valorisee')->sum('points_obtenus');
        $enAttente = Collecte::where('statut', 'realisee')->count();

        // Dernières commandes du partenaire
        $dernieresCommandes = Commande::where('partenaire_id', Auth::id())
            ->with('collecte.user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Nombre d'offres disponibles
        $offresDisponibles = Collecte::where('statut', 'realisee')
            ->whereDoesntHave('commande')
            ->count();

        return view('partenaire.dashboard', compact(
            'totalCollectes',
            'totalPoids',
            'totalPoints',
            'enAttente',
            'dernieresCommandes',
            'offresDisponibles'
        ));
    }

    // =============================================
    // LISTE DES DÉCHETS REÇUS
    // =============================================
    public function dechetsRecus()
    {
        $collectes = Collecte::whereIn('statut', ['realisee', 'valorisee'])
            ->with('user')
            ->orderBy('date_collecte', 'desc')
            ->paginate(15);

        return view('partenaire.dechets', compact('collectes'));
    }

    // =============================================
    // VALIDER LA RÉCEPTION DES DÉCHETS
    // =============================================
    public function validerReception($id)
    {
        $collecte = Collecte::findOrFail($id);

        if ($collecte->statut !== 'realisee') {
            return redirect()->back()->with('error', 'Cette collecte ne peut pas être validée.');
        }

        $collecte->statut = 'valorisee';
        $collecte->partenaire_id = Auth::id();
        $collecte->date_reception = now();
        $collecte->save();

        // Notification au collecteur
        Notification::create([
            'user_id' => $collecte->collecteur->user_id ?? null,
            'titre' => '♻️ Déchets valorisés',
            'message' => 'Les déchets de la collecte #' . $collecte->id . ' ont été valorisés par ' . Auth::user()->nom,
            'type' => 'collecte',
            'est_lu' => false,
        ]);

        return redirect()->back()->with('success', 'Réception validée avec succès !');
    }

    // =============================================
    // STATISTIQUES
    // =============================================
    public function statistiques()
    {
        $collectesParMois = Collecte::where('statut', 'valorisee')
            ->selectRaw('MONTH(date_reception) as mois, COUNT(*) as total')
            ->whereYear('date_reception', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        $moisKeys = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $collectesParMoisValues = [];
        for ($i = 1; $i <= 12; $i++) {
            $collectesParMoisValues[] = $collectesParMois[$i] ?? 0;
        }

        $totalRecyclable = Collecte::where('statut', 'valorisee')->sum('poids_recyclable');
        $totalOrganique = Collecte::where('statut', 'valorisee')->sum('poids_organique');
        $totalResiduel = Collecte::where('statut', 'valorisee')->sum('poids_residuel');

        return view('partenaire.statistiques', compact(
            'moisKeys',
            'collectesParMoisValues',
            'totalRecyclable',
            'totalOrganique',
            'totalResiduel'
        ));
    }

    // =============================================
    // FORMULAIRE DE COMMANDE
    // =============================================
    public function showCommandeForm($id)
    {
        $collecte = Collecte::with('user')->findOrFail($id);

        // Vérifier que la collecte est disponible
        if ($collecte->statut !== 'realisee') {
            return redirect()->route('partenaire.offres')->with('error', 'Cette collecte n\'est plus disponible.');
        }

        if ($collecte->commande) {
            return redirect()->route('partenaire.offres')->with('error', 'Cette collecte a déjà été commandée.');
        }

        return view('partenaire.commander', compact('collecte'));
    }

    // =============================================
    // HISTORIQUE DES COMMANDES
    // =============================================
    public function historique()
    {
        $commandes = Commande::where('partenaire_id', Auth::id())
            ->with('collecte.user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Commande::where('partenaire_id', Auth::id())->count(),
            'en_attente' => Commande::where('partenaire_id', Auth::id())->where('statut', 'en_attente')->count(),
            'validees' => Commande::where('partenaire_id', Auth::id())->where('statut', 'validee')->count(),
            'livrees' => Commande::where('partenaire_id', Auth::id())->where('statut', 'livree')->count(),
            'annulees' => Commande::where('partenaire_id', Auth::id())->where('statut', 'annulee')->count(),
        ];

        return view('partenaire.historique', compact('commandes', 'stats'));
    }

    // =============================================
    // GÉNÉRER UN CERTIFICAT DE VALORISATION
    // =============================================
    public function genererCertificat($commandeId)
    {
        $commande = Commande::with('collecte.user')->findOrFail($commandeId);

        // Vérifier que la commande appartient au partenaire
        if ($commande->partenaire_id != Auth::id()) {
            abort(403);
        }

        // Vérifier que la commande est livrée
        if ($commande->statut !== 'livree') {
            return redirect()->route('partenaire.historique')
                ->with('error', 'Le certificat ne peut être généré que pour une commande livrée.');
        }

        // Vérifier qu'un certificat n'existe pas déjà
        $certificatExistant = CertificatValorisation::where('commande_id', $commandeId)->first();
        if ($certificatExistant) {
            return redirect()->route('partenaire.certificat.show', $certificatExistant->id);
        }

        $numeroCertificat = 'CERT-' . strtoupper(uniqid());

        $certificat = CertificatValorisation::create([
            'partenaire_id' => Auth::id(),
            'commande_id' => $commande->id,
            'numero_certificat' => $numeroCertificat,
            'date_emission' => now(),
            'quantite_valorisee' => $commande->quantite,
            'type_valorisation' => 'Recyclage',
            'description' => 'Valorisation de déchets provenant de la collecte #' . $commande->collecte_id,
        ]);

        // Mettre à jour la collecte
        $commande->collecte->update(['statut' => 'valorisee']);

        // Notifier le collecteur
        if ($commande->collecte->collecteur) {
            Notification::create([
                'user_id' => $commande->collecte->collecteur->user_id,
                'titre' => ' Certificat généré',
                'message' => 'Un certificat de valorisation a été émis pour la collecte #' . $commande->collecte_id,
                'type' => 'certificat',
                'est_lu' => false,
            ]);
        }

        return view('partenaire.certificat', compact('certificat', 'commande'));
    }

    // =============================================
    // AFFICHER UN CERTIFICAT EXISTANT
    // =============================================
    public function showCertificat($id)
    {
        $certificat = CertificatValorisation::with('commande.collecte.user')->findOrFail($id);

        if ($certificat->partenaire_id != Auth::id()) {
            abort(403);
        }

        $commande = $certificat->commande;

        return view('partenaire.certificat', compact('certificat', 'commande'));
    }

    // =============================================
    // METTRE À JOUR LE PROFIL PARTENAIRE (BESOINS)
    // =============================================
    public function updateProfil(Request $request)
    {
        $partenaire = Auth::user()->partenaire ?? abort(404);

        $request->validate([
            'type_partenaire' => 'required|string',
            'filiere' => 'required|string',
            'besoins' => 'nullable|string',
        ]);

        $partenaire->update([
            'type_partenaire' => $request->type_partenaire,
            'filiere' => $request->filiere,
            'besoins' => $request->besoins,
        ]);

        return redirect()->route('partenaire.dashboard')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    public function offres()
    {
        $stocks = StockDechet::all();
        return view('partenaire.offres', compact('stocks'));
    }
    public function storeCommande(Request $request)
    {
        $request->validate([
            'type_dechet' => 'required|in:recyclable,organique,residuel',
            'quantite' => 'required|numeric|min:0.1',
            'prix_unitaire' => 'required|numeric|min:0',
        ]);

        $stock = StockDechet::where('type', $request->type_dechet)->first();

        if (!$stock || $stock->quantite < $request->quantite) {
            return back()->with('error', 'Stock insuffisant. Disponible : ' . ($stock->quantite ?? 0) . ' kg');
        }

        // Créer la commande
        $commande = Commande::create([
            'partenaire_id' => Auth::id(),
            'type_dechet' => $request->type_dechet,
            'quantite' => $request->quantite,
            'prix_unitaire' => $request->prix_unitaire,
            'montant_total' => $request->quantite * $request->prix_unitaire,
            'statut' => 'en_attente',
        ]);

        // Réserver le stock (le décrémenter immédiatement ou à la validation)
        StockDechet::decrementer($request->type_dechet, $request->quantite);

        return redirect()->route('partenaire.historique')->with('success', 'Commande passée avec succès.');
    }
}
