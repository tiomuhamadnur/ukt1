<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('jenis_cuti_id')->unsigned()->nullable();
            $table->dateTime('tanggal_awal')->nullable();
            $table->dateTime('tanggal_akhir')->nullable();
            $table->integer('jumlah')->nullable();
            $table->text('catatan')->nullable();
            $table->text('lampiran')->nullable();
            $table->bigInteger('diketahui_oleh_id')->unsigned()->nullable();
            $table->dateTime('diketahui_at')->nullable();
            $table->bigInteger('disetujui_oleh_id')->unsigned()->nullable();
            $table->dateTime('disetujui_at')->nullable();
            $table->bigInteger('status_cuti_id')->unsigned()->nullable();
            $table->string('nomor_surat')->unique()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('jenis_cuti_id')->on('jenis_cuti')->references('id');
            $table->foreign('diketahui_oleh_id')->on('users')->references('id');
            $table->foreign('disetujui_oleh_id')->on('users')->references('id');
            $table->foreign('status_cuti_id')->on('status_cuti')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};
