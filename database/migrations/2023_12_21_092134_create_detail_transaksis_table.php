<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->unsignedBigInteger('id_transaksi');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksis')
                                    ->onUpdate('cascade')
                                    ->onDelete('cascade');
            $table->unsignedBigInteger('id_produk');
            $table->foreign('id_produk')->references('id_produk')->on('warna_bahan_produks')
                                    ->onUpdate('restrict')
                                    ->onDelete('restrict');
            $table->unsignedBigInteger('id_warna');
            $table->foreign('id_warna')->references('id_warna')->on('warna_bahan_produks')
                                    ->onUpdate('restrict')
                                    ->onDelete('restrict');
            $table->unsignedBigInteger('id_bahan');
            $table->foreign('id_bahan')->references('id_bahan')->on('warna_bahan_produks')
                                    ->onUpdate('restrict')
                                    ->onDelete('restrict');
            $table->integer('jumlah');
            $table->boolean('tambahan_motif');
            $table->integer('harga_produk');
            $table->primary(['id_transaksi', 'id_warna', 'id_bahan', 'id_produk'], 'detail_transaksis_primary');
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
        Schema::dropIfExists('detail_transaksis');
    }
}
