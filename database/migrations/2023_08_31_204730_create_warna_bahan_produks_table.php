<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarnaBahanProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warna_bahan_produks', function (Blueprint $table) {
            $table->unsignedBigInteger('id_produk');
            $table->foreign('id_produk')->references('id_produk')->on('produks')
                                    ->onUpdate('cascade')
                                    ->onDelete('cascade');
            $table->unsignedBigInteger('id_warna');
            $table->foreign('id_warna')->references('id')->on('warnas')
                                    ->onUpdate('cascade')
                                    ->onDelete('restrict');
            $table->unsignedBigInteger('id_bahan');
            $table->foreign('id_bahan')->references('id')->on('bahans')
                                    ->onUpdate('cascade')
                                    ->onDelete('restrict');
            $table->primary(['id_produk', 'id_warna', 'id_bahan']);
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
        Schema::dropIfExists('warna_bahan_produks');
    }
}
