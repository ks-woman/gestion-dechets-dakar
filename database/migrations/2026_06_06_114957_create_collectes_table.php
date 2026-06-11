<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collectes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('collecteur_id')->nullable()->constrained('collecteurs')->onDelete('set null');
            $table->date('date_demande');
            $table->date('date_collecte')->nullable();
            $table->enum('statut', ['en_attente', 'planifiee', 'realisee', 'annulee'])->default('en_attente');
            $table->float('poids_recyclable')->default(0);
            $table->float('poids_organique')->default(0);
            $table->float('poids_residuel')->default(0);
            $table->integer('points_obtenus')->default(0);
            $table->text('adresse')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collectes');
    }
};
