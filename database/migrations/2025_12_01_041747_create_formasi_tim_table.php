<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formasi_tim', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('code')->unique()->nullable();
            $table->bigInteger('tim_id')->unsigned()->nullable();
            $table->bigInteger('pulau_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('koordinator_id')->unsigned()->nullable();
            $table->year('periode')->nullable();
            $table->timestamps();

            $table->foreign('tim_id')->on('tim')->references('id');
            $table->foreign('pulau_id')->on('pulau')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('koordinator_id')->on('users')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formasi_tim');
    }
};
