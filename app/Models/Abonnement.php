<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_debut_essai',
        'date_fin_essai',
        'date_debut_abonnement',
        'montant_mensuel',
        'statut'
    ];

    protected $casts = [
        'date_debut_essai' => 'datetime',
        'date_fin_essai' => 'datetime',
        'date_debut_abonnement' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function estActif()
    {
        return $this->statut === 'actif';
    }

    public function estEnEssai()
    {
        return $this->statut === 'essai';
    }

    public function estExpire()
    {
        return $this->statut === 'expire';
    }

    public function verifierFinEssai()
    {
        if ($this->statut === 'essai' && now()->greaterThan($this->date_fin_essai)) {
            $this->statut = 'expire';
            $this->save();

            $this->user->statut_compte = 'en_attente_paiement';
            $this->user->save();

            return true;
        }
        return false;
    }
}
