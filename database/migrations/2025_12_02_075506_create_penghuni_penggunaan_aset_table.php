<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penghuni_penggunaan_aset', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('penggunaan_aset_id')->unsigned()->nullable();
            $table->bigInteger('pengguna_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('penggunaan_aset_id')->on('penggunaan_aset')->references('id');
            $table->foreign('pengguna_id')->on('pengguna')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penghuni_penggunaan_aset');
    }
};
