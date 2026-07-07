<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anomalie extends Model
{
    use HasFactory;

    protected $fillable = [
        'collecteur_id',
        'user_id',
        'type',
        'description',
        'photo',
        'statut',
        'date_signalement'
    ];

    protected $casts = [
        'date_signalement' => 'datetime',
    ];

    public function collecteur()
    {
        return $this->belongsTo(Collecteur::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
