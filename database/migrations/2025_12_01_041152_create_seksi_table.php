<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seksi', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('code')->unique()->nullable();
            $table->bigInteger('unit_kerja_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('unit_kerja_id')->on('unit_kerja')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seksi');
    }
};
