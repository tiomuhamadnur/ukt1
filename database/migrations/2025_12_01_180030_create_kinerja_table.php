<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kinerja', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('code')->unique()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->date('tanggal')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->text('lokasi')->nullable();
            $table->bigInteger('kegiatan_id')->unsigned()->nullable();
            $table->text('kegiatan_lainnya')->nullable();
            $table->text('deskripsi')->nullable();
            $table->bigInteger('unit_kerja_id')->unsigned()->nullable();
            $table->bigInteger('seksi_id')->unsigned()->nullable();
            $table->bigInteger('tim_id')->unsigned()->nullable();
            $table->bigInteger('formasi_tim_id')->unsigned()->nullable();
            $table->bigInteger('pulau_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('kegiatan_id')->on('kegiatan')->references('id');
            $table->foreign('unit_kerja_id')->on('unit_kerja')->references('id');
            $table->foreign('seksi_id')->on('seksi')->references('id');
            $table->foreign('tim_id')->on('tim')->references('id');
            $table->foreign('formasi_tim_id')->on('formasi_tim')->references('id');
            $table->foreign('pulau_id')->on('pulau')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kinerja');
    }
};
