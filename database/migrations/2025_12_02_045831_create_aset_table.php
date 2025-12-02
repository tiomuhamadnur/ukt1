<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->unique()->nullable();
            $table->string('code')->nullable();
            $table->bigInteger('tipe_aset_id')->unsigned()->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('alamat')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longtitude')->nullable();
            $table->integer('kapasitas')->nullable();
            $table->integer('luas_tanah')->nullable();
            $table->integer('luas_bangunan')->nullable();
            $table->integer('nilai_kontrak')->nullable();
            $table->date('mulai_kontrak')->nullable();
            $table->date('akhir_kontrak')->nullable();
            $table->timestamps();

            $table->foreign('tipe_aset_id')->on('tipe_aset')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset');
    }
};
