<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            // Ajouter date_reception
            $table->timestamp('date_reception')->nullable()->after('date_livraison');

            // ✅ Ajouter collecteur_id si elle n'existe pas
            // Vérifier d'abord si la colonne existe
            if (!Schema::hasColumn('commandes', 'collecteur_id')) {
                $table->foreignId('collecteur_id')->nullable()->constrained('collecteurs')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('date_reception');
            // Si vous voulez aussi supprimer collecteur_id en cas de rollback
            // $table->dropForeign(['collecteur_id']);
            // $table->dropColumn('collecteur_id');
        });
    }
};
