<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelanggansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->bigIncrements('id_pelanggan');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')
                                    ->onUpdate('cascade')
                                    ->onDelete('cascade');
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->string('alamat');
            $table->unsignedBigInteger('id_provinsi');
            $table->foreign('id_provinsi')->references('id')->on('indonesia_provinces')
                                    ->onUpdate('cascade')
                                    ->onDelete('cascade');
            $table->unsignedBigInteger('id_kota');
            $table->foreign('id_kota')->references('id')->on('indonesia_cities')
                                    ->onUpdate('cascade')
                                    ->onDelete('cascade');
            $table->unsignedBigInteger('id_kecamatan');
            $table->foreign('id_kecamatan')->references('id')->on('indonesia_districts')
                                    ->onUpdate('cascade')
                                    ->onDelete('cascade');
            $table->integer('kode_pos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelanggans');
    }
}
