<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EchangeRecompense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recompense_id',
        'points_utilises',
        'date_echange',
        'statut'
    ];

    protected $casts = [
        'date_echange' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recompense()
    {
        return $this->belongsTo(Recompense::class);
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }
}
