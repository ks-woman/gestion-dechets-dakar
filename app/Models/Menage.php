<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre_personnes',
        'type_logement',
        'a_enfants'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function demanderCollecte()
    {
        // Logique pour demander une collecte
    }
}
