<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recompenses', function (Blueprint $table) {
            $table->id();
            $table->string('nom_recompense', 100);
            $table->integer('points_requis');
            $table->enum('type_recompense', ['bon_achat', 'article_physique', 'reduction', 'cadeau']);
            $table->text('description')->nullable();
            $table->decimal('valeur', 10, 2)->nullable();
            $table->integer('quantite_disponible')->default(0);
            $table->date('date_expiration')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recompenses');
    }
};
