<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->integer('nik')->nullable();
            $table->bigInteger('gender_id')->unsigned()->nullable();
            $table->bigInteger('pulau_id')->unsigned()->nullable();
            $table->bigInteger('kelurahan_id')->unsigned()->nullable();
            $table->text('alamat')->nullable();
            $table->text('photo')->nullable();
            $table->text('photo_ktp')->nullable();
            $table->string('name_emergency')->nullable();
            $table->string('no_hp_emergency')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('gender_id')->on('gender')->references('id');
            $table->foreign('pulau_id')->on('pulau')->references('id');
            $table->foreign('kelurahan_id')->on('kelurahan')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
