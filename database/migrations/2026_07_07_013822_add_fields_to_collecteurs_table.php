<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('collecteurs', function (Blueprint $table) {
            $table->integer('objectif_mensuel')->default(50)->after('note_moyenne');
            $table->date('date_embauche')->nullable()->after('objectif_mensuel');
        });
    }

    public function down()
    {
        Schema::table('collecteurs', function (Blueprint $table) {
            $table->dropColumn(['objectif_mensuel', 'date_embauche']);
        });
    }
};
