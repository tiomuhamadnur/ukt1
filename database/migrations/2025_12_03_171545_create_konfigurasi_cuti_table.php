<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konfigurasi_cuti', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->year('periode')->nullable();
            $table->bigInteger('jenis_cuti_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->integer('jumlah_awal')->nullable();
            $table->integer('jumlah_akhir')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('jenis_cuti_id')->on('jenis_cuti')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_cuti');
    }
};
