<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories_dechet', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->unique();
            $table->string('icone', 10)->nullable();
            $table->string('couleur', 20)->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories_dechet');
    }
};
