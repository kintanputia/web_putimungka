<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistribusiBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribusi_barangs', function (Blueprint $table) {
            $table->bigIncrements('id_distribusi');
            $table->unsignedBigInteger('id_alamat');
            $table->foreign('id_alamat')->references('id_alamat')->on('alamat_pelanggans')
                                    ->onUpdate('restrict')
                                    ->onDelete('restrict');
            $table->unsignedBigInteger('id_transaksi');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksis')
                                    ->onUpdate('restrict')
                                    ->onDelete('cascade');
            $table->integer('biaya_ongkir')->default(0);
            $table->string('layanan_ekspedisi')->nullable();
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
        Schema::dropIfExists('distribusi_barangs');
    }
}
