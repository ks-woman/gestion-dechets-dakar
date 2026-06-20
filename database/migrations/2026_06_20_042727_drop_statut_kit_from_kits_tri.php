<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('kits_tri', function (Blueprint $table) {
            $table->dropColumn('statut_kit');
        });
    }

    public function down()
    {
        Schema::table('kits_tri', function (Blueprint $table) {
            $table->enum('statut_kit', ['en_attente', 'actif', 'inactif'])->default('en_attente');
        });
    }
};
