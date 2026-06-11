<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('demandes_collecte', 'instructions')) {
            Schema::table('demandes_collecte', function (Blueprint $table) {
                $table->text('instructions')->nullable()->after('date_souhaitee');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('demandes_collecte', 'instructions')) {
            Schema::table('demandes_collecte', function (Blueprint $table) {
                $table->dropColumn('instructions');
            });
        }
    }
};
