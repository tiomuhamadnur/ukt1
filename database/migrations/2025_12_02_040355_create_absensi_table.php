<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('code')->unique()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('jenis_absensi_id')->unsigned()->nullable();
            $table->date('tanggal')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->integer('telat_masuk')->nullable();
            $table->integer('telat_pulang')->nullable();
            $table->string('status_masuk')->nullable();
            $table->string('status_pulang')->nullable();
            $table->text('photo_masuk')->nullable();
            $table->text('photo_pulang')->nullable();
            $table->text('catatan_masuk')->nullable();
            $table->text('catatan_pulang')->nullable();
            $table->string('latitude_masuk')->nullable();
            $table->string('longitude_masuk')->nullable();
            $table->string('latitude_pulang')->nullable();
            $table->string('longitude_pulang')->nullable();
            $table->bigInteger('diketahui_oleh_id')->unsigned()->nullable();
            $table->bigInteger('disetujui_oleh_id')->unsigned()->nullable();
            $table->bigInteger('status_absensi_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('jenis_absensi_id')->on('jenis_absensi')->references('id');
            $table->foreign('diketahui_oleh_id')->on('users')->references('id');
            $table->foreign('disetujui_oleh_id')->on('users')->references('id');
            $table->foreign('status_absensi_id')->on('status_absensi')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
