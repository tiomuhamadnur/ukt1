<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggunaan_aset', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('code')->unique()->nullable();
            $table->bigInteger('reservasi_id')->unsigned()->nullable();
            $table->bigInteger('aset_id')->unsigned()->nullable();
            $table->bigInteger('pengguna_id')->unsigned()->nullable();
            $table->bigInteger('status_penggunaan_aset_id')->unsigned()->nullable();
            $table->dateTime('tanggal_checkin')->nullable();
            $table->dateTime('tanggal_checkout')->nullable();
            $table->integer('jumlah_penghuni')->nullable();
            $table->timestamps();

            $table->foreign('reservasi_id')->on('reservasi')->references('id');
            $table->foreign('aset_id')->on('aset')->references('id');
            $table->foreign('pengguna_id')->on('pengguna')->references('id');
            $table->foreign('status_penggunaan_aset_id')->on('status_penggunaan_aset')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggunaan_aset');
    }
};
