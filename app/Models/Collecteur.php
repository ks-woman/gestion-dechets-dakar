<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collecteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'vehicule_type',
        'zone_couverture',
        'disponibilite',
        'note_moyenne'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function collectes()
    {
        return $this->hasMany(Collecte::class);
    }

    public function primes()
    {
        return $this->hasMany(PrimeCollecteur::class);
    }

    // Ajoutez cette méthode dans la classe Collecteur

    public function zones()
    {
        return $this->belongsToMany(ZoneCollecte::class, 'collecteur_zone', 'collecteur_id',  'zone_id');
    }

    // Récupérer la première zone (pour simplification)
    public function getZoneAttribute()
    {
        return $this->zones()->first();
    }
}
