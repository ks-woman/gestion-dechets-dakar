<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            // Modifier l'enum pour ajouter 'recue'
            $table->enum('statut', ['en_attente', 'validee', 'affectee', 'livree', 'recue', 'annulee'])
                ->default('en_attente')
                ->change();
        });
    }

    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'validee', 'affectee', 'livree', 'annulee'])
                ->default('en_attente')
                ->change();
        });
    }
};
