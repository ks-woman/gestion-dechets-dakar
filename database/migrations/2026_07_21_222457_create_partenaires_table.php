<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partenaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type_partenaire'); // centre_recyclage, unite_compostage, etc.
            $table->string('filiere'); // recyclage, compostage, reutilisation, incineration
            $table->enum('statut_partenariat', ['actif', 'inactif'])->default('actif');
            $table->json('besoins')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partenaires');
    }
};
