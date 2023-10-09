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
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->boolean('kirim_ekspedisi')->default(false);
            $table->integer('biaya_ongkir')->default(0);
            $table->string('layanan_ekspedisi')->nullable();
            $table->string('alamat')->nullable();
            $table->string('provinsi_tujuan')->nullable();
            $table->string('kota_tujuan')->nullable();
            $table->string('kecamatan_tujuan')->nullable();
            $table->integer('kode_pos')->nullable();
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
