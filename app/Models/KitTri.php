<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class KitTri extends Model
{
    use HasFactory;

    protected $table = 'kits_tri';

    protected $fillable = [
        'user_id',
        'collecteur_id',        // Nouveau : collecteur qui a livré le kit
        'code_qr',              // Code unique du kit (ex: KIT-ABC123)
        'url_qr',               // URL pour l'activation
        'type_kit',             // standard, renforce, compact
        'date_demande',
        'date_distribution',
        'date_activation',
        'date_activation',
        'statut',               // en_attente, actif, inactif
    ];

    protected $casts = [
        'date_demande' => 'date',
        'date_distribution' => 'date',
        'date_activation' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur (ménage ou entreprise)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le collecteur qui a livré le kit
     */
    public function collecteur()
    {
        return $this->belongsTo(Collecteur::class);
    }

    /**
     * Vérifier si le kit est en attente de livraison
     */
    public function estEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si le kit est actif
     */
    public function estActif()
    {
        return $this->statut === 'actif';
    }

    /**
     * Vérifier si le kit est inactif
     */
    public function estInactif()
    {
        return $this->statut === 'inactif';
    }

    /**
     * Activer le kit pour un ménage donné (avec ou sans collecteur)
     */
    public function activer($collecteurId = null)
    {
        if ($this->statut !== 'en_attente') {
            return false;
        }

        $this->statut = 'actif';
        $this->date_distribution = now();
        $this->date_activation = now();

        if ($collecteurId) {
            $this->collecteur_id = $collecteurId;
        }

        $this->save();

        // Mettre à jour le statut du ménage associé (15 jours d'essai)
        if ($this->user) {
            $this->user->statut_compte = 'essai_15j';
            $this->user->date_debut_essai = now();
            $this->user->date_fin_essai = now()->addDays(15);
            $this->user->save();

            // Notifier le ménage
            Notification::create([
                'user_id' => $this->user->id,
                'titre' => '✅ Votre kit est activé !',
                'message' => 'Votre kit de tri a été livré et activé. Profitez de 15 jours d\'essai gratuit.',
                'type' => 'kit',
                'est_lu' => false
            ]);
        }

        return true;
    }

    /**
     * Générer une image QR code en base64 (pour affichage)
     */
    public function genererQRCode($size = 200)
    {
        if (!$this->code_qr) {
            return null;
        }

        return QrCode::size($size)->generate($this->code_qr);
    }

    /**
     * Générer et sauvegarder l'image QR code sur le disque
     */
    public function sauvegarderQRCode($path = 'qrcodes')
    {
        if (!$this->code_qr) {
            return null;
        }

        $image = QrCode::format('png')->size(300)->generate($this->code_qr);
        $filePath = $path . '/' . $this->code_qr . '.png';
        Storage::disk('public')->put($filePath, $image);

        return $filePath;
    }

    /**
     * Accesseur pour obtenir l'URL complète du QR code (s'il est stocké)
     */
    public function getQrImageUrlAttribute()
    {
        if (Storage::disk('public')->exists('qrcodes/' . $this->code_qr . '.png')) {
            return asset('storage/qrcodes/' . $this->code_qr . '.png');
        }
        return null;
    }

    /**
     * Scope pour les kits en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les kits actifs
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les kits d'un ménage donné
     */
    public function scopePourMénage($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
