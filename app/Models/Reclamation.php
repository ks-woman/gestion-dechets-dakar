<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sujet',
        'description',
        'statut',
        'reponse',
        'date_resolution'
    ];

    protected $casts = [
        'date_resolution' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeOuvertes($query)
    {
        return $query->where('statut', 'ouverte');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeResolues($query)
    {
        return $query->where('statut', 'resolue');
    }

    public function scopeFermees($query)
    {
        return $query->where('statut', 'fermee');
    }
}
