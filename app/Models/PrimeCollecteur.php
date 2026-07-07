<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimeCollecteur extends Model
{
    use HasFactory;

    protected $table = 'primes_collecteurs';

    protected $fillable = [
        'collecteur_id',
        'mois',
        'annee',
        'montant_total',
        'details',
        'statut'
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function collecteur()
    {
        return $this->belongsTo(Collecteur::class);
    }
}
