<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificats_valorisation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partenaire_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->string('numero_certificat', 50)->unique();
            $table->date('date_emission');
            $table->decimal('quantite_valorisee', 10, 2);
            $table->string('type_valorisation', 50);
            $table->text('description')->nullable();
            $table->string('fichier_pdf')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificats_valorisation');
    }
};
