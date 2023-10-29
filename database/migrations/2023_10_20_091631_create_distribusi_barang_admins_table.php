<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistribusiBarangAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribusi_barang_admins', function (Blueprint $table) {
            $table->bigIncrements('id_distribusi');
            $table->unsignedBigInteger('id_transaksi');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi_admins')
                                    ->onUpdate('cascade')
                                    ->onDelete('cascade');
            $table->integer('biaya_ongkir')->default(0);
            $table->string('layanan_ekspedisi')->nullable();
            $table->string('alamat');
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
        Schema::dropIfExists('distribusi_barang_admins');
    }
}
