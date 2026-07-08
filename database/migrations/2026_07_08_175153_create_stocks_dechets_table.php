<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocks_dechets', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['recyclable', 'organique', 'residuel'])->unique();
            $table->decimal('quantite', 10, 2)->default(0);
            $table->decimal('prix_unitaire', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks_dechets');
    }
};
