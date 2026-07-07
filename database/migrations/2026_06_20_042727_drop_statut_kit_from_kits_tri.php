<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la colonne existe avant de la supprimer
        if (Schema::hasColumn('kits_tri', 'statut_kit')) {
            Schema::table('kits_tri', function (Blueprint $table) {
                $table->dropColumn('statut_kit');
            });
        }
    }

    public function down()
    {
        // En cas de rollback, recréer la colonne si elle n'existe pas
        if (!Schema::hasColumn('kits_tri', 'statut_kit')) {
            Schema::table('kits_tri', function (Blueprint $table) {
                $table->enum('statut_kit', ['en_attente', 'actif', 'inactif'])->default('en_attente');
            });
        }
    }
};
