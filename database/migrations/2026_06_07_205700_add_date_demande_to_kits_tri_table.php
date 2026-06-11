<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('kits_tri', 'date_demande')) {
            Schema::table('kits_tri', function (Blueprint $table) {
                $table->date('date_demande')->nullable()->after('type_kit');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kits_tri', 'date_demande')) {
            Schema::table('kits_tri', function (Blueprint $table) {
                $table->dropColumn('date_demande');
            });
        }
    }
};
