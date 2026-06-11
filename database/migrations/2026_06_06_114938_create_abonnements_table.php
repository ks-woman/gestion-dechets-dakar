<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date_debut_essai');
            $table->date('date_fin_essai');
            $table->date('date_debut_abonnement')->nullable();
            $table->decimal('montant_mensuel', 10, 2)->default(5000);
            $table->enum('statut', ['essai', 'actif', 'expire', 'resilie'])->default('essai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
