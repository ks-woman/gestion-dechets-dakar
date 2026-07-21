<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_partenaire',
        'filiere',
        'statut_partenariat',
        'besoins'
    ];

    protected $casts = [
        'besoins' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
