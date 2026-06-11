<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collecteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('matricule', 50)->unique();
            $table->enum('vehicule_type', ['charette', 'motocycliste', 'camion']);
            $table->string('zone_couverture', 100)->nullable();
            $table->boolean('disponibilite')->default(true);
            $table->float('note_moyenne')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collecteurs');
    }
};
