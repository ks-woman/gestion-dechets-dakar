<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieDechet extends Model
{
    use HasFactory;

    protected $table = 'categories_dechet';

    protected $fillable = [
        'nom',
        'icone',
        'couleur',
        'est_actif'
    ];

    public function stock()
    {
        return $this->hasOne(StockDechet::class);
    }
}
