<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('date_debut_essai')->nullable()->after('statut_compte');
            $table->timestamp('date_fin_essai')->nullable()->after('date_debut_essai');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['date_debut_essai', 'date_fin_essai']);
        });
    }
};
