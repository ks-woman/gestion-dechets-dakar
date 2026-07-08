<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partenaire_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('collecte_id')->constrained()->onDelete('cascade');
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_unitaire', 10, 2)->default(0);
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->enum('statut', ['en_attente', 'validee', 'livree', 'annulee'])->default('en_attente');
            $table->date('date_livraison')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commandes');
    }
};
