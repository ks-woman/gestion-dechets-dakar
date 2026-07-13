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
        'statut',
        'date_paiement'
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
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
}
