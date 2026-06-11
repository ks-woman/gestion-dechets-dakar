<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collecte extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'collecteur_id',
        'date_demande',
        'date_collecte',
        'poids_recyclable',
        'poids_organique',
        'poids_residuel',
        'statut',
        'points_obtenus',
        'adresse',
        'instructions'
    ];

    protected $casts = [
        'date_demande' => 'date',
        'date_collecte' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function collecteur()
    {
        return $this->belongsTo(Collecteur::class);
    }

    public function calculerPoints()
    {
        $points = ($this->poids_recyclable * 1) + ($this->poids_organique * 0.5);
        $this->points_obtenus = (int)$points;
        $this->save();

        $this->user->ajouterPoints($this->points_obtenus);
    }
}
