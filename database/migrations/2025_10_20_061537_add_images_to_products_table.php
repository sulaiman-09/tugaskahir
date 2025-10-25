<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Cek apakah kolom 'speed' sudah ada
            if (!Schema::hasColumn('products', 'speed')) {
                $table->string('speed')->nullable()->after('name');
            }

            // Contoh menambahkan kolom lain: 'web_image'
            if (!Schema::hasColumn('products', 'web_image')) {
                $table->string('web_image')->nullable()->after('speed');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Hanya drop jika kolom ada
            if (Schema::hasColumn('products', 'web_image')) {
                $table->dropColumn('web_image');
            }
            // Jangan drop kolom speed karena sudah ada sebelumnya
        });
    }
};
