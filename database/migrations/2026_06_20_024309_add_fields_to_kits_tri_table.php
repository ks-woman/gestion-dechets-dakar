<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kits_tri', function (Blueprint $table) {
            $table->foreignId('collecteur_id')->nullable()->constrained()->nullOnDelete();
            $table->string('url_qr')->nullable()->after('code_qr');
            $table->timestamp('date_activation')->nullable()->after('date_distribution');
            // Supprimer la colonne redondante statut_kit si elle existe
            if (Schema::hasColumn('kits_tri', 'statut_kit')) {
                $table->dropColumn('statut_kit');
            }
        });
    }

    public function down()
    {
        Schema::table('kits_tri', function (Blueprint $table) {
            $table->dropForeign(['collecteur_id']);
            $table->dropColumn(['collecteur_id', 'url_qr', 'date_activation']);
        });
    }
};
