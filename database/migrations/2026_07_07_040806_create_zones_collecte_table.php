<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table des zones
        Schema::create('zones_collecte', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->text('description')->nullable();
            $table->json('quartiers'); // Ex: ["Pikine", "Guediawaye", "Yoff"]
            $table->timestamps();
        });

        // Table pivot entre collecteurs et zones
        Schema::create('collecteur_zone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collecteur_id')->constrained('collecteurs')->onDelete('cascade');
            $table->foreignId('zone_id')->constrained('zones_collecte')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collecteur_zone');
        Schema::dropIfExists('zones_collecte');
    }
};
