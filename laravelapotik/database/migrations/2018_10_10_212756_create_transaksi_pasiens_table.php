<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiPasiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_pasiens', function (Blueprint $table) {
            $table->increments('id');
            $table->char('dokter_id');
            $table->char('pasien_id');
            $table->date('tgl_resep');
            $table->double('bayar');
            $table->double('total');
            $table->double('kembalian');
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
        Schema::dropIfExists('transaksi_pasiens');
    }
}
