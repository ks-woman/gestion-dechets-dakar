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
        Schema::table('commandes', function (Blueprint $table) {
            $table->foreignId('collecteur_id')->nullable()->constrained('collecteurs')->nullOnDelete();
            $table->timestamp('date_affectation')->nullable()->after('collecteur_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropForeign(['collecteur_id']);
            $table->dropColumn(['collecteur_id', 'date_affectation']);
        });
    }
};
