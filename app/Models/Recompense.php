<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recompense extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_recompense',
        'points_requis',
        'type_recompense',
        'description',
        'valeur',
        'quantite_disponible',
        'date_expiration'
    ];

    protected $casts = [
        'date_expiration' => 'date',
    ];

    public function estDisponible()
    {
        return $this->quantite_disponible > 0;
    }
}
