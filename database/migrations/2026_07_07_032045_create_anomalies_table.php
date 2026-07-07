<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('anomalies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collecteur_id')->constrained('collecteurs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['acces_impossible', 'absence_tri', 'dechet_dangereux', 'client_absent', 'autre']);
            $table->text('description');
            $table->string('photo')->nullable();
            $table->enum('statut', ['en_attente', 'traite', 'ignore'])->default('en_attente');
            $table->timestamp('date_signalement');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anomalies');
    }
};
