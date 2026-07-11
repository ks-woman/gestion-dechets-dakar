<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Modifier la colonne statut pour ajouter 'affectee'
        DB::statement("ALTER TABLE commandes MODIFY statut ENUM('en_attente', 'validee', 'affectee', 'livree', 'annulee') NOT NULL DEFAULT 'en_attente'");
    }

    public function down()
    {
        // Revenir à l'ancien ENUM (sans 'affectee')
        DB::statement("ALTER TABLE commandes MODIFY statut ENUM('en_attente', 'validee', 'livree', 'annulee') NOT NULL DEFAULT 'en_attente'");
    }
};
