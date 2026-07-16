<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use App\Models\Commande;
use App\Models\CertificatValorisation;
use App\Models\Notification;
use App\Models\Partenaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\StockDechet;
use App\Models\CategorieDechet;

class PartenaireController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('partenaire');
    }

    /**
     * Vérifier que l'utilisateur est bien un partenaire
     */
    private function checkPartenaire()
    {
        $user = Auth::user();

        if (!$user || !$user->isPartenaire()) {
            abort(403, 'Accès réservé aux partenaires.');
        }
    }

    // =============================================
    // DASHBOARD
    // =============================================
    public function dashboard()
    {
        $this->checkPartenaire();

        // 📌 Total des commandes passées par ce partenaire
        $totalCommandes = Commande::where('partenaire_id', Auth::id())->count();

        // 📌 Quantité totale commandée par ce partenaire
        $quantiteTotale = Commande::where('partenaire_id', Auth::id())->sum('quantite');

        // 📌 Offres disponibles
        $offresDisponibles = StockDechet::where('quantite', '>', 0)->count();

        // 📌 Dernières commandes
        $dernieresCommandes = Commande::where('partenaire_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 📌 Notifications
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $nonLues = Notification::where('user_id', Auth::id())
            ->where('est_lu', false)
            ->count();

        return view('partenaire.dashboard', compact(
            'totalCommandes',
            'quantiteTotale',
            'offresDisponibles',
            'dernieresCommandes',
            'notifications',
            'nonLues'
        ));
    }
    // =============================================
    // LISTE DES DÉCHETS REÇUS
    // =============================================
    public function dechetsRecus()
    {
        $this->checkPartenaire();

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
        $this->checkPartenaire();

        $collecte = Collecte::findOrFail($id);

        if ($collecte->statut !== 'realisee') {
            return redirect()->back()->with('error', 'Cette collecte ne peut pas être validée.');
        }

        $collecte->statut = 'valorisee';
        $collecte->partenaire_id = Auth::id();
        $collecte->date_reception = now();
        $collecte->save();

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
    // FORMULAIRE DE COMMANDE
    // =============================================
    public function showCommandeForm($id)
    {
        $this->checkPartenaire();

        $collecte = Collecte::with('user')->findOrFail($id);

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
        $this->checkPartenaire();

        $commandes = Commande::where('partenaire_id', Auth::id())
            ->with(['collecte.user', 'categorie', 'certificat'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Commande::where('partenaire_id', Auth::id())->count(),
            'en_attente' => Commande::where('partenaire_id', Auth::id())->where('statut', 'en_attente')->count(),
            'validees' => Commande::where('partenaire_id', Auth::id())->where('statut', 'validee')->count(),
            'livrees' => Commande::where('partenaire_id', Auth::id())->where('statut', 'livree')->count(),
            'recues' => Commande::where('partenaire_id', Auth::id())->where('statut', 'recue')->count(),
            'annulees' => Commande::where('partenaire_id', Auth::id())->where('statut', 'annulee')->count(),
        ];

        return view('partenaire.historique', compact('commandes', 'stats'));
    }

    // =============================================
    // GÉNÉRER UN CERTIFICAT DE VALORISATION
    // =============================================
    public function genererCertificat($commandeId)
    {
        $this->checkPartenaire();

        $commande = Commande::with('collecte.user')->findOrFail($commandeId);

        if ($commande->partenaire_id != Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à générer un certificat pour cette commande.');
        }

        if ($commande->statut !== 'livree' && $commande->statut !== 'recue') {
            return redirect()->route('partenaire.historique')
                ->with('error', 'Le certificat ne peut être généré que pour une commande livrée ou réceptionnée.');
        }

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

        $commande->collecte->update(['statut' => 'valorisee']);

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
        $this->checkPartenaire();

        $certificat = CertificatValorisation::with('commande.collecte.user')->findOrFail($id);

        if ($certificat->partenaire_id != Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce certificat.');
        }

        $commande = $certificat->commande;

        return view('partenaire.certificat', compact('certificat', 'commande'));
    }

    // =============================================
    // METTRE À JOUR LE PROFIL PARTENAIRE (BESOINS)
    // =============================================
    public function updateProfil(Request $request)
    {
        $this->checkPartenaire();

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

    // =============================================
    // OFFRES (liste des stocks disponibles > 0)
    // =============================================
    public function offres()
    {
        $this->checkPartenaire();

        $stocks = StockDechet::with('categorie')
            ->whereHas('categorie', function ($query) {
                $query->where('est_actif', true);
            })
            ->where('quantite', '>', 0)
            ->orderBy('quantite', 'desc')
            ->get();

        return view('partenaire.offres', compact('stocks'));
    }

    // =============================================
    // PASSER UNE COMMANDE
    // =============================================
    public function storeCommande(Request $request)
    {
        $this->checkPartenaire();

        $request->validate([
            'categorie_id' => 'required|exists:categories_dechet,id',
            'quantite' => 'required|numeric|min:0.1',
            'prix_unitaire' => 'required|numeric|min:0',
        ]);

        $stock = StockDechet::where('categorie_id', $request->categorie_id)->first();

        if (!$stock || $stock->quantite < $request->quantite) {
            return back()->with('error', 'Stock insuffisant.');
        }

        $commande = Commande::create([
            'partenaire_id' => Auth::id(),
            'categorie_id' => $request->categorie_id,
            'quantite' => $request->quantite,
            'prix_unitaire' => $request->prix_unitaire,
            'montant_total' => $request->quantite * $request->prix_unitaire,
            'statut' => 'en_attente',
        ]);

        StockDechet::decrementer($request->categorie_id, $request->quantite);

        return redirect()->route('partenaire.historique')->with('success', 'Commande passée avec succès.');
    }

    // =============================================
    // STATISTIQUES DU PARTENAIRE
    // =============================================
    public function statistiques()
    {
        $this->checkPartenaire();

        $user = Auth::id();

        $totalCommandes = Commande::where('partenaire_id', $user)->count();
        $quantiteTotale = Commande::where('partenaire_id', $user)->sum('quantite');
        $montantTotal = Commande::where('partenaire_id', $user)->sum('montant_total');

        $categories = CategorieDechet::pluck('nom');
        $volumesParCategorie = [];
        foreach (CategorieDechet::all() as $cat) {
            $volumesParCategorie[] = Commande::where('partenaire_id', $user)
                ->where('categorie_id', $cat->id)
                ->sum('quantite');
        }

        $moisCommandes = [];
        $nbCommandesParMois = [];
        for ($i = 5; $i >= 0; $i--) {
            $mois = now()->subMonths($i);
            $moisCommandes[] = $mois->format('M Y');
            $nbCommandesParMois[] = Commande::where('partenaire_id', $user)
                ->whereYear('created_at', $mois->year)
                ->whereMonth('created_at', $mois->month)
                ->count();
        }

        return view('partenaire.statistiques', compact(
            'totalCommandes',
            'quantiteTotale',
            'montantTotal',
            'categories',
            'volumesParCategorie',
            'moisCommandes',
            'nbCommandesParMois'
        ));
    }

    // =============================================
    // CONFIRMER LA RÉCEPTION D'UNE COMMANDE
    // =============================================
    public function confirmerReception($id)
    {
        $this->checkPartenaire();

        $commande = Commande::with(['partenaire', 'collecte', 'categorie'])->findOrFail($id);

        // Vérifier que la commande appartient bien au partenaire
        if ($commande->partenaire_id != Auth::id()) {
            abort(403, 'Cette commande ne vous appartient pas.');
        }

        // Vérifier que la commande est en statut "livree"
        if ($commande->statut !== 'livree') {
            return redirect()->back()->with('error', 'Seule une commande livrée peut être réceptionnée.');
        }

        // Mettre à jour le statut
        $commande->statut = 'recue';
        $commande->date_reception = now();
        $commande->save();

        // Mettre à jour la collecte associée si elle existe
        if ($commande->collecte) {
            $commande->collecte->update([
                'statut' => 'valorisee',
                'partenaire_id' => Auth::id(),
                'date_reception' => now(),
            ]);
        }

        // Générer automatiquement le certificat de valorisation
        $certificat = $this->genererCertificatReception($commande);

        // Notifier le collecteur
        if ($commande->collecteur_id) {
            $collecteur = User::find($commande->collecteur_id);
            if ($collecteur) {
                Notification::create([
                    'user_id' => $collecteur->id,
                    'titre' => ' Réception confirmée',
                    'message' => 'Le partenaire ' . Auth::user()->nom . ' a confirmé la réception de la commande #' . $commande->id,
                    'type' => 'commande',
                    'est_lu' => false,
                ]);
            }
        }

        // Notifier l'admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'titre' => ' Réception validée',
                'message' => 'Le partenaire ' . Auth::user()->nom . ' a réceptionné la commande #' . $commande->id,
                'type' => 'commande',
                'est_lu' => false,
            ]);
        }

        return redirect()->route('partenaire.historique')
            ->with('success', ' Réception confirmée ! Le certificat de valorisation a été généré automatiquement.');
    }

    /**
     * Générer le certificat de valorisation (appelé automatiquement)
     */
    private function genererCertificatReception($commande)
    {
        $numeroCertificat = 'CERT-' . strtoupper(uniqid());

        return CertificatValorisation::create([
            'partenaire_id' => Auth::id(),
            'commande_id' => $commande->id,
            'numero_certificat' => $numeroCertificat,
            'date_emission' => now(),
            'quantite_valorisee' => $commande->quantite,
            'type_valorisation' => $commande->categorie->nom ?? 'Recyclage',
            'description' => 'Valorisation de déchets ' . ($commande->categorie->nom ?? '') . ' provenant de la commande #' . $commande->id,
        ]);
    }
}
