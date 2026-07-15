<?php

namespace App\Http\Controllers;

use App\Models\KitTri;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // ✅ AJOUT

class KitController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests; // ✅ AJOUT

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le formulaire de demande de kit
     */
    public function showDemanderKit()
    {
        // Le middleware menage filtre déjà, donc cette vérification est redondante mais on la garde si vous voulez
        // $this->authorize('create', KitTri::class);

        return view('kit.demander');
    }

    /**
     * Traiter la demande de kit
     */
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

        // Créer le kit
        $kit = KitTri::create([
            'user_id' => $user->id,
            'code_qr' => $codeUnique,
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

    /**
     * Générer un QR code en base64
     */
    private function genererQRCodeBase64($data)
    {
        $size = '200x200';
        $encodedData = urlencode($data);
        $qrUrl = "https://chart.googleapis.com/chart?chs={$size}&cht=qr&chl={$encodedData}";

        $qrImage = @file_get_contents($qrUrl);
        if ($qrImage === false) {
            return "data:image/png;base64," . base64_encode($data);
        }

        return 'data:image/png;base64,' . base64_encode($qrImage);
    }

    /**
     * Activer le kit (pour le collecteur) - via API JSON
     */
    public function activerKit(Request $request)
    {
        // Le middleware collecteur filtre déjà, donc pas besoin de re-vérifier
        // $this->authorize('activer-kit');

        try {
            // Validation
            $request->validate([
                'code_kit' => 'required|string'
            ]);

            // Recherche du kit
            $kit = KitTri::where('code_qr', $request->code_kit)->first();

            if (!$kit) {
                return response()->json(['error' => 'Kit introuvable.'], 404);
            }

            if ($kit->statut === 'actif') {
                return response()->json(['error' => 'Ce kit est déjà activé.'], 400);
            }

            if ($kit->statut !== 'en_attente') {
                return response()->json(['error' => 'Ce kit n\'est pas en attente d\'activation.'], 400);
            }

            $user = $kit->user;

            if (!$user) {
                return response()->json(['error' => 'Aucun ménage associé à ce kit.'], 404);
            }

            // Activer le kit
            $kit->statut = 'actif';
            $kit->date_distribution = now();
            $kit->date_activation = now();
            $kit->save();

            // Mettre le ménage en période d'essai
            $user->statut_compte = 'essai_15j';
            $user->date_debut_essai = now();
            $user->date_fin_essai = now()->addDays(15);
            $user->save();

            // Notification au ménage
            Notification::create([
                'user_id' => $user->id,
                'titre' => '✅ Votre kit est activé !',
                'message' => 'Votre kit de tri a été activé. Profitez de 15 jours d\'essai gratuit.',
                'type' => 'kit',
                'est_lu' => false
            ]);

            // Notification au collecteur
            if (Auth::check()) {
                Notification::create([
                    'user_id' => Auth::id(),
                    'titre' => '🔧 Kit activé',
                    'message' => 'Vous avez activé le kit de ' . ($user->prenom ?? 'client') . ' ' . ($user->nom ?? ''),
                    'type' => 'kit',
                    'est_lu' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kit activé avec succès !',
                'menage' => trim(($user->prenom ?? '') . ' ' . ($user->nom ?? ''))
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur activation kit: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }
}
