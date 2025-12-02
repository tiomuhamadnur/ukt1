<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas_aset', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('aset_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('photo')->nullable();
            $table->timestamps();

            $table->foreign('aset_id')->on('aset')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas_aset');
    }
};
