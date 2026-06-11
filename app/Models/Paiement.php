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
        'date_paiement',
        'mode_paiement',
        'reference_transaction',
        'statut'
    ];

    protected $casts = [
        'date_paiement' => 'date',
    ];

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function validerPaiement()
    {
        $this->statut = 'valide';
        $this->save();

        $abonnement = $this->abonnement;
        $abonnement->statut = 'actif';
        $abonnement->date_debut_abonnement = now();
        $abonnement->save();

        $abonnement->user->statut_compte = 'abonne_actif';
        $abonnement->user->save();
    }
}
