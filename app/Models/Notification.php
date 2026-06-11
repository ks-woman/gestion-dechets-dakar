<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'message',
        'type',
        'est_lu'
    ];

    protected $casts = [
        'est_lu' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
