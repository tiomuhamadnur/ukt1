<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->nullable();
            $table->string('nik')->unique()->nullable();
            $table->string('nip')->unique()->nullable();
            $table->string('no_hp')->unique()->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->text('photo')->nullable();
            $table->text('ttd')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_plt')->nullable()->default(false);
            $table->bigInteger('user_type_id')->unsigned()->nullable();
            $table->bigInteger('gender_id')->unsigned()->nullable();
            $table->bigInteger('jabatan_id')->unsigned()->nullable();
            $table->bigInteger('pulau_id')->unsigned()->nullable();
            $table->bigInteger('kelurahan_id')->unsigned()->nullable();
            $table->softDeletes();

            $table->foreign('user_type_id')->on('user_type')->references('id');
            $table->foreign('gender_id')->on('gender')->references('id');
            $table->foreign('jabatan_id')->on('jabatan')->references('id');
            $table->foreign('pulau_id')->on('pulau')->references('id');
            $table->foreign('kelurahan_id')->on('kelurahan')->references('id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_type_id']);
            $table->dropForeign(['gender_id']);
            $table->dropForeign(['jabatan_id']);
            $table->dropForeign(['pulau_id']);
            $table->dropForeign(['kelurahan_id']);

            $table->dropColumn('uuid');
            $table->dropColumn('nik');
            $table->dropColumn('nip');
            $table->dropColumn('no_hp');
            $table->dropColumn('tempat_lahir');
            $table->dropColumn('tanggal_lahir');
            $table->dropColumn('alamat');
            $table->dropColumn('photo');
            $table->dropColumn('ttd');
            $table->dropColumn('bio');
            $table->dropColumn('is_plt');
            $table->dropColumn('user_type_id');
            $table->dropColumn('gender_id');
            $table->dropColumn('pulau_id');
            $table->dropColumn('kelurahan_id');
            $table->dropColumn('jabatan_id');
        });
    }
};
