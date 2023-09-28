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
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')
                                ->onUpdate('cascade')
                                ->onDelete('cascade');
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->date('tgl_pesan');
            $table->date('tgl_selesai')->nullable();
            $table->string('status_transaksi');
            $table->boolean('kirim_ekspedisi')->default(false);
            $table->integer('total_belanja');
            $table->integer('biaya_ongkir')->default(0);
            $table->string('layanan_ekspedisi')->nullable();
            $table->string('alamat')->nullable();
            $table->string('provinsi_tujuan')->nullable();
            $table->string('kota_tujuan')->nullable();
            $table->string('kecamatan_tujuan')->nullable();
            $table->integer('kode_pos')->nullable();
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
