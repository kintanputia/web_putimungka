<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_admins', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')
                                ->onUpdate('cascade')
                                ->onDelete('restrict');
            $table->string('nama_pelanggan');
            $table->string('no_hp');
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
        Schema::dropIfExists('transaksi_admins');
    }
}
