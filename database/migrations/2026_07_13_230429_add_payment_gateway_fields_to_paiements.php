<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('reference');
            $table->string('gateway', 50)->default('wave')->after('mode_paiement');
            $table->text('gateway_response')->nullable()->after('statut');
            $table->timestamp('date_validation')->nullable()->after('date_paiement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            //
        });
    }
};
