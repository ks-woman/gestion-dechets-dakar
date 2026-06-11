<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kits_tri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code_qr', 100)->unique();
            $table->enum('type_kit', ['standard', 'renforce', 'compact'])->default('standard');
            $table->date('date_demande')->nullable();
            $table->date('date_distribution')->nullable();
            $table->enum('statut', ['en_attente', 'distribue', 'actif', 'inactif'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kits_tri');
    }
};
