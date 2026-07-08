<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            // Remplacer collecte_id par le type de déchet
            if (Schema::hasColumn('commandes', 'collecte_id')) {
                $table->dropForeign(['collecte_id']);
                $table->dropColumn('collecte_id');
            }
            $table->enum('type_dechet', ['recyclable', 'organique', 'residuel'])->after('partenaire_id');
        });
    }

    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('type_dechet');
            $table->foreignId('collecte_id')->constrained();
        });
    }
};
