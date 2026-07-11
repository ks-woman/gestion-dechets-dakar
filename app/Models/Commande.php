<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'partenaire_id',
        'categorie_id',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'statut',
        'date_livraison',
        'collecteur_id',
        'date_affectation'
    ];

    protected $casts = [
        'date_livraison' => 'date',
        'date_affectation' => 'datetime',
    ];

    public function partenaire()
    {
        return $this->belongsTo(User::class, 'partenaire_id');
    }

    public function collecte()
    {
        return $this->belongsTo(Collecte::class);
    }

    public function certificat()
    {
        return $this->hasOne(CertificatValorisation::class);
    }

    public function collecteur()
    {
        return $this->belongsTo(Collecteur::class);
    }

    public function categorie()
    {
        return $this->belongsTo(CategorieDechet::class);
    }
}
