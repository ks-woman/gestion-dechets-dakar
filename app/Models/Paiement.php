<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'abonnement_id',
        'montant',
        'reference',
        'mode_paiement',
        'gateway',
        'transaction_id',
        'statut',
        'gateway_response',
        'date_paiement',
        'date_validation',
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'date_validation' => 'datetime',
    ];

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Abonnement::class, 'id', 'id', 'abonnement_id', 'user_id');
    }

    public function estValide()
    {
        return $this->statut === 'valide';
    }

    public function estEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    public function estEchoue()
    {
        return $this->statut === 'echoue';
    }

    // Correspondance avec le diagramme : modePaiement est l'énumération
    public function getModePaiementAttribute($value)
    {
        return $value; // paiementMobile, paiementBancaire, especes
    }
}
