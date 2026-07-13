<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // On garde les champs d'essai existants (déjà présents dans votre projet)
            // On ajoute un champ de statut global (si pas déjà présent)
            if (!Schema::hasColumn('users', 'abonnement_statut')) {
                $table->string('abonnement_statut', 20)->default('inactif')->after('statut_compte');
            }
            // On supprime les colonnes qu'on a déplacées dans la table abonnements (si elles existent)
            // Mais pour ne pas casser les données, on les garde et on les migrera vers la nouvelle table séparément
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('abonnement_statut');
        });
    }
};
