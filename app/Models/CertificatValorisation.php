<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificatValorisation extends Model
{
    use HasFactory;

    protected $table = 'certificats_valorisation';

    protected $fillable = [
        'partenaire_id',
        'commande_id',
        'numero_certificat',
        'date_emission',
        'quantite_valorisee',
        'type_valorisation',
        'description',
        'fichier_pdf'
    ];

    protected $casts = [
        'date_emission' => 'date',
    ];

    public function partenaire()
    {
        return $this->belongsTo(User::class, 'partenaire_id');
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
