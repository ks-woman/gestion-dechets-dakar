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
        'date_resolution' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
