<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneCollecte extends Model
{
    use HasFactory;

    protected $table = 'zones_collecte';

    protected $fillable = [
        'nom',
        'description',
        'quartiers'
    ];

    protected $casts = [
        'quartiers' => 'array',
    ];

    // Relation many-to-many avec les collecteurs
    public function collecteurs()
    {
        return $this->belongsToMany(
            Collecteur::class,
            'collecteur_zone',
            'zone_id',
            'collecteur_id'
        );
    }

    // Récupérer les utilisateurs (ménages/entreprises) dont le quartier est dans cette zone
    public function clients()
    {
        // On récupère les quartiers de la zone
        $quartiers = $this->quartiers ?? [];
        return User::whereIn('role', ['menage', 'entreprise'])
            ->whereIn('quartier', $quartiers);
    }

    // Nombre de clients dans la zone
    public function getNombreClientsAttribute()
    {
        return $this->clients()->count();
    }

    // Nombre de collectes dans la zone (pour les statistiques)
    public function getNombreCollectesAttribute()
    {
        $clientIds = $this->clients()->pluck('id');
        return Collecte::whereIn('user_id', $clientIds)->count();
    }
}
