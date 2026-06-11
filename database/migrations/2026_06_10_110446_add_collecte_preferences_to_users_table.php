<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('frequence_collecte', [
                'quotidienne',
                '2x_semaine',
                'hebdomadaire',
                'bihebdomadaire',
                'sur_demande'
            ])->default('sur_demande')->after('statut_compte');

            $table->json('jours_collecte')->nullable()->after('frequence_collecte');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['frequence_collecte', 'jours_collecte']);
        });
    }
};
