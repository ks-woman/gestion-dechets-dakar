<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'partenaire_id',
        'collecte_id',
        'categorie_id',
        'collecteur_id',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'statut',
        'date_livraison',
        'date_reception',
        'date_affectation',
    ];

    protected $casts = [
        'date_livraison' => 'date',
        'date_reception' => 'date',
    ];

    // Relations
    public function partenaire()
    {
        return $this->belongsTo(User::class, 'partenaire_id');
    }

    public function collecte()
    {
        return $this->belongsTo(Collecte::class);
    }

    public function categorie()
    {
        return $this->belongsTo(CategorieDechet::class);
    }

    public function certificat()
    {
        return $this->hasOne(CertificatValorisation::class);
    }

    public function collecteur()
    {
        return $this->belongsTo(Collecteur::class);
    }
}
