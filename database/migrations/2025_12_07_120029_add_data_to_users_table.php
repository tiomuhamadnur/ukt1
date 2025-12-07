<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('unit_kerja_id')->unsigned()->nullable();
            $table->bigInteger('seksi_id')->unsigned()->nullable();

            $table->foreign('unit_kerja_id')->on('unit_kerja')->references('id');
            $table->foreign('seksi_id')->on('seksi')->references('id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_kerja_id']);
            $table->dropForeign(['seksi_id']);

            $table->dropColumn('unit_kerja_id');
            $table->dropColumn('seksi_id');
        });
    }
};
