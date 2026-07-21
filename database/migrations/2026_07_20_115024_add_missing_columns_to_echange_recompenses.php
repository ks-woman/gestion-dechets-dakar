<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('echange_recompenses', function (Blueprint $table) {
            if (!Schema::hasColumn('echange_recompenses', 'points_utilises')) {
                $table->integer('points_utilises')->after('recompense_id');
            }
            if (!Schema::hasColumn('echange_recompenses', 'date_echange')) {
                $table->timestamp('date_echange')->nullable()->after('points_utilises');
            }
            if (!Schema::hasColumn('echange_recompenses', 'statut')) {
                $table->string('statut', 20)->default('en_attente')->after('date_echange');
            }
        });
    }

    public function down(): void
    {
        Schema::table('echange_recompenses', function (Blueprint $table) {
            $table->dropColumn(['points_utilises', 'date_echange', 'statut']);
        });
    }
};
