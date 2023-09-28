<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_produk');
            $table->integer('harga');
            $table->text('ukuran');
            $table->integer('berat');
            $table->string('nama_motif');
            $table->string('jenis_jahitan');
            $table->string('model');
            $table->string('waktu_pengerjaan')->nullable();
            $table->string('foto');
            $table->text('deskripsi');
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
        Schema::dropIfExists('produks');
    }
}
