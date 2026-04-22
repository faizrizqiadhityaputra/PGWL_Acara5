<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('polygon', function (Blueprint $table) {
            // TAMBAHKAN DI SINI 👇
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('polygon', function (Blueprint $table) {
            // TAMBAHKAN DI SINI 👇
            $table->dropColumn(['name', 'description', 'image']);
        });
    }
};
