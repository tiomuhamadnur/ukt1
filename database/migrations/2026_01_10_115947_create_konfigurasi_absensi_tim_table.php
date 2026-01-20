<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konfigurasi_absensi_tim', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('konfigurasi_absensi_id')->unsigned()->nullable();
            $table->bigInteger('tim_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('konfigurasi_absensi_id')->on('konfigurasi_absensi')->references('id');
            $table->foreign('tim_id')->on('tim')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_absensi_tim');
    }
};
