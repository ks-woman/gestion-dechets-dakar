<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDechet extends Model
{
    use HasFactory;

    protected $table = 'stocks_dechets';

    protected $fillable = [
        'categorie_id',
        'quantite',
        'prix_unitaire'
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieDechet::class);
    }

    // Incrémenter le stock pour une catégorie donnée
    public static function incrementer($categorieId, $quantite)
    {
        $stock = self::where('categorie_id', $categorieId)->first();
        if ($stock) {
            $stock->quantite += $quantite;
            $stock->save();
        } else {
            self::create([
                'categorie_id' => $categorieId,
                'quantite' => $quantite,
                'prix_unitaire' => 0,
            ]);
        }
    }

    // Décrémenter le stock
    public static function decrementer($categorieId, $quantite)
    {
        $stock = self::where('categorie_id', $categorieId)->first();
        if ($stock && $stock->quantite >= $quantite) {
            $stock->quantite -= $quantite;
            $stock->save();
            return true;
        }
        return false;
    }
}
