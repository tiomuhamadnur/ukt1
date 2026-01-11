<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->bigInteger('tim_id')->unsigned()->nullable()->after('seksi_id');

            $table->foreign('tim_id')->on('tim')->references('id');
        });
    }

    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropForeign(['tim_id']);

            $table->dropColumn('tim_id');
        });
    }
};
