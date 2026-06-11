<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeCollecte extends Model
{
    use HasFactory;

    protected $table = 'demandes_collecte';

    protected $fillable = [
        'user_id',
        'date_souhaitee',
        'instructions',
        'statut'
    ];

    protected $casts = [
        'date_souhaitee' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
