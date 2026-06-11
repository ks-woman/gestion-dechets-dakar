<?php

namespace App\Http\Controllers;

use App\Models\KitTri;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KitController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Afficher le formulaire de demande de kit
    public function showDemanderKit()
    {
        return view('kit.demander');
    }

    // Traiter la demande de kit
    public function demanderKit(Request $request)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur a déjà un kit
        if ($user->kitTri) {
            return redirect()->route('menage.dashboard')->with('error', 'Vous avez déjà demandé un kit.');
        }

        // Vérifier le statut du compte
        if ($user->statut_compte !== 'inscrit') {
            return redirect()->route('menage.dashboard')->with('error', 'Vous ne pouvez pas demander de kit actuellement.');
        }

        // Générer un code unique pour le kit
        $codeUnique = 'KIT-' . strtoupper(uniqid());

        // Générer l'URL que le collecteur scannera
        $urlScan = route('collecteur.activer-kit.par-scan', ['code' => $codeUnique]);

        // Créer le kit - AJOUTE code_qr ICI
        $kit = KitTri::create([
            'user_id' => $user->id,
            'code_unique' => $codeUnique,
            'code_qr' => $codeUnique,  // ← AJOUTE CETTE LIGNE
            'url_qr' => $urlScan,
            'type_kit' => $request->type_kit,
            'date_demande' => now(),
            'statut' => 'en_attente'
        ]);

        // Générer le QR code en image (base64 pour l'affichage)
        $qrCodeImage = $this->genererQRCodeBase64($urlScan);

        // Notifier tous les collecteurs
        $collecteurs = User::where('role', 'collecteur')->get();

        foreach ($collecteurs as $collecteur) {
            Notification::create([
                'user_id' => $collecteur->id,
                'titre' => '📦 Nouveau kit à livrer',
                'message' => $user->prenom . ' ' . $user->nom . ' a demandé un kit à ' . $user->adresse,
                'type' => 'kit',
                'qr_code' => $qrCodeImage,
                'kit_id' => $kit->id,
                'est_lu' => false
            ]);
        }

        // Notifier le client
        Notification::create([
            'user_id' => $user->id,
            'titre' => '🎫 Votre kit est en préparation',
            'message' => 'Votre kit de tri est en cours de préparation. Un collecteur vous contactera pour la livraison.',
            'type' => 'kit',
            'qr_code' => $qrCodeImage,
            'est_lu' => false
        ]);

        // Mettre à jour le statut du compte
        $user->statut_compte = 'commande_kit';
        $user->save();

        return redirect()->route('menage.dashboard')->with('success', 'Demande de kit enregistrée ! Vous recevrez votre kit sous 48h.');
    }

    // Méthode pour générer un QR code en base64
    private function genererQRCodeBase64($data)
    {
        $size = '200x200';
        $encodedData = urlencode($data);
        $qrUrl = "https://chart.googleapis.com/chart?chs={$size}&cht=qr&chl={$encodedData}";

        $qrImage = @file_get_contents($qrUrl);
        if ($qrImage === false) {
            // Fallback : générer un QR code avec une autre API
            return "data:image/png;base64," . base64_encode($data);
        }

        return 'data:image/png;base64,' . base64_encode($qrImage);
    }

    // Activer le kit (pour le collecteur)
    public function activerKit(Request $request)
    {
        $request->validate([
            'code_kit' => 'required|string'
        ]);

        // Chercher le kit par code_unique ou code_qr
        $kit = KitTri::where('code_unique', $request->code_kit)
            ->orWhere('code_qr', $request->code_kit)
            ->first();

        if (!$kit) {
            return redirect()->back()->with('error', '❌ Code de kit invalide.');
        }

        // Vérifier si le kit est déjà activé
        if ($kit->statut === 'actif') {
            return redirect()->back()->with('error', '⚠️ Ce kit est déjà activé.');
        }

        // Vérifier si le kit est en attente
        if ($kit->statut !== 'en_attente') {
            return redirect()->back()->with('error', '⚠️ Ce kit n\'est pas en attente d\'activation.');
        }

        // Récupérer le client
        $user = $kit->user;

        // Activer le kit
        $kit->update([
            'statut' => 'actif',
            'date_distribution' => now(),
            'date_activation' => now()
        ]);

        // Mettre à jour le statut du client (15 jours d'essai)
        if ($user && $user->role === 'menage') {
            $user->statut_compte = 'essai_15j';
            $user->date_debut_essai = now();
            $user->date_fin_essai = now()->addDays(15);
            $user->save();
        }

        // Notifier le client
        Notification::create([
            'user_id' => $user->id,
            'titre' => '✅ Votre kit est activé !',
            'message' => 'Votre kit de tri a été activé. Profitez de 15 jours d\'essai gratuit.',
            'type' => 'kit',
            'est_lu' => false
        ]);

        // Notifier le collecteur qui a activé
        if (Auth::check()) {
            Notification::create([
                'user_id' => Auth::id(),
                'titre' => '🔧 Kit activé',
                'message' => 'Vous avez activé le kit de ' . ($user->prenom ?? 'client') . ' ' . ($user->nom ?? ''),
                'type' => 'kit',
                'est_lu' => false
            ]);
        }

        return redirect()->route('collecteur.dashboard')->with('success', '✅ Kit activé avec succès !');
    }
}
