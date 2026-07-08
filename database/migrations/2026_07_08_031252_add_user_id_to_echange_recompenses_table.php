<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('echange_recompenses', function (Blueprint $table) {
            // Vérifier si la colonne existe avant de l'ajouter
            if (!Schema::hasColumn('echange_recompenses', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('echange_recompenses', 'recompense_id')) {
                $table->foreignId('recompense_id')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('echange_recompenses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['recompense_id']);
            $table->dropColumn('recompense_id');
        });
    }
};
