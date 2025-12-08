<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('provinces')) {
            Schema::create('provinces', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('external_id')->nullable()->index();
                $table->string('name')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cities')) {
            Schema::create('cities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('external_id')->nullable()->index();
                $table->unsignedBigInteger('province_id')->nullable()->index();
                $table->string('name')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('subdistricts')) {
            Schema::create('subdistricts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('external_id')->nullable()->index();
                $table->unsignedBigInteger('city_id')->nullable()->index();
                $table->string('name')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('villages')) {
            Schema::create('villages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('external_id')->nullable()->index();
                $table->unsignedBigInteger('subdistrict_id')->nullable()->index();
                $table->string('name')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('company_desc')) {
            Schema::create('company_desc', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('slug')->nullable()->index();
                $table->text('content')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('about_us')) {
            Schema::create('about_us', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('slug')->nullable()->index();
                $table->text('content')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Hanya drop tabel yang pasti dibuat di migration ini
        foreach (['about_us', 'company_desc', 'villages', 'subdistricts', 'cities', 'provinces'] as $table) {
            if (Schema::hasTable($table)) {
                Schema::drop($table);
            }
        }
    }
};
