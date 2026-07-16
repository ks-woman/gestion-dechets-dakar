<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la colonne existe
        if (Schema::hasColumn('commandes', 'statut')) {
            // Utiliser une requête brute pour modifier l'enum
            DB::statement("ALTER TABLE commandes MODIFY COLUMN statut ENUM('en_attente', 'validee', 'affectee', 'livree', 'recue', 'annulee') DEFAULT 'en_attente'");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('commandes', 'statut')) {
            DB::statement("ALTER TABLE commandes MODIFY COLUMN statut ENUM('en_attente', 'validee', 'livree', 'annulee') DEFAULT 'en_attente'");
        }
    }
};
