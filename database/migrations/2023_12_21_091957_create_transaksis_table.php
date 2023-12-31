<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi');
            $table->unsignedBigInteger('id_pelanggan');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')
                                ->onUpdate('cascade')
                                ->onDelete('restrict');
            $table->boolean('kirim_ekspedisi')->default(false);
            $table->date('tgl_pesan');
            $table->date('tgl_selesai')->nullable();
            $table->string('status_transaksi');
            $table->integer('total_belanja');
            $table->string('bukti_bayar')->nullable();
            $table->text('note_transaksi')->nullable();
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
        Schema::dropIfExists('transaksis');
    }
}
