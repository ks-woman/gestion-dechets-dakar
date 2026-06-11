<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitTri extends Model
{
    use HasFactory;

    protected $table = 'kits_tri';

    protected $fillable = [
        'user_id',
        'code_qr',
        'type_kit',
        'date_demande',
        'date_distribution',
        'statut'
    ];

    protected $casts = [
        'date_demande' => 'date',
        'date_distribution' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
