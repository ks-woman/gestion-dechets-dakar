<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            // Ajouter la colonne date_reception si elle n'existe pas
            if (!Schema::hasColumn('commandes', 'date_reception')) {
                $table->timestamp('date_reception')->nullable()->after('date_livraison');
            }
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (Schema::hasColumn('commandes', 'date_reception')) {
                $table->dropColumn('date_reception');
            }
        });
    }
};
