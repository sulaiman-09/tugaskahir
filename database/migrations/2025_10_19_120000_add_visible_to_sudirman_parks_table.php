<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sudirman_parks')) {
            Schema::table('sudirman_parks', function (Blueprint $table) {
                if (!Schema::hasColumn('sudirman_parks', 'visible')) {
                    $table->boolean('visible')->default(true)->after('note');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sudirman_parks')) {
            Schema::table('sudirman_parks', function (Blueprint $table) {
                if (Schema::hasColumn('sudirman_parks', 'visible')) {
                    $table->dropColumn('visible');
                }
            });
        }
    }
};
