<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Pour hebdomadaire : quel jour de la semaine
            $table->string('jour_hebdomadaire')->nullable()->after('jours_collecte');

            // Pour bihebdomadaire : jour et semaine paire/impaire
            $table->string('jour_bihebdomadaire')->nullable()->after('jour_hebdomadaire');
            $table->enum('semaine_type', ['paire', 'impaire'])->nullable()->after('jour_bihebdomadaire');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jour_hebdomadaire', 'jour_bihebdomadaire', 'semaine_type']);
        });
    }
};
