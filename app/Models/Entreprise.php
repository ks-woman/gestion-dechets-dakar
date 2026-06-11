<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numero_registre_commerce',
        'type_activite',
        'volume_moyen_dechet'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
