<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeranjangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keranjangs', function (Blueprint $table) {
            $table->bigIncrements('id_keranjang');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')
                                    ->onUpdate('cascade')
                                    ->onDelete('cascade');
            $table->unsignedBigInteger('id_produk');
            $table->foreign('id_produk')->references('id')->on('produks')
                                    ->onUpdate('cascade')
                                    ->onDelete('cascade');
            $table->unsignedBigInteger('warna');
            $table->foreign('warna')->references('id')->on('warnas')
                                    ->onUpdate('restrict')
                                    ->onDelete('restrict');
            $table->unsignedBigInteger('bahan');
            $table->foreign('bahan')->references('id')->on('bahans')
                                    ->onUpdate('restrict')
                                    ->onDelete('restrict');
            $table->integer('jumlah');
            $table->boolean('tambahan_motif');
            $table->integer('harga_produk');
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
        Schema::dropIfExists('keranjangs');
    }
}
