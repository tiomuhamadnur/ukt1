<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pic_aset', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('aset_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->year('periode')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('aset_id')->on('aset')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pic_aset');
    }
};
