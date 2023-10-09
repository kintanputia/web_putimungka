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
                                ->onDelete('restrict');
            $table->unsignedBigInteger('id_distribusi');
            $table->foreign('id_distribusi')->references('id')->on('distribusi_barangs')
                                ->onUpdate('cascade')
                                ->onDelete('cascade');
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
