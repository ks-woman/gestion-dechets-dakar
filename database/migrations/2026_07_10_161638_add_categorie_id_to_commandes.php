<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('commandes', 'categorie_id')) {
            Schema::table('commandes', function (Blueprint $table) {
                $table->foreignId('categorie_id')->constrained('categories_dechet')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('commandes', 'categorie_id')) {
            Schema::table('commandes', function (Blueprint $table) {
                $table->dropForeign(['categorie_id']);
                $table->dropColumn('categorie_id');
            });
        }
    }
};
