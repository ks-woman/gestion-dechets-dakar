<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('primes_collecteurs')) {
            Schema::create('primes_collecteurs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('collecteur_id')->constrained('collecteurs')->onDelete('cascade');
                $table->integer('mois');
                $table->integer('annee');
                $table->decimal('montant_total', 10, 2);
                $table->json('details')->nullable();
                $table->enum('statut', ['calcule', 'valide', 'paye'])->default('calcule');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('primes_collecteurs');
    }
};
