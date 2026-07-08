<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ajouter la colonne categorie_id
        Schema::table('stocks_dechets', function (Blueprint $table) {
            if (!Schema::hasColumn('stocks_dechets', 'categorie_id')) {
                $table->foreignId('categorie_id')->after('id')->constrained('categories_dechet')->onDelete('cascade');
            }
            if (Schema::hasColumn('stocks_dechets', 'type')) {
                $table->dropColumn('type');
            }
        });
    }

    public function down()
    {
        Schema::table('stocks_dechets', function (Blueprint $table) {
            $table->dropForeign(['categorie_id']);
            $table->dropColumn('categorie_id');
            $table->enum('type', ['recyclable', 'organique', 'residuel'])->unique();
        });
    }
};
