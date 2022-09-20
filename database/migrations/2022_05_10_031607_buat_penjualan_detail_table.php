<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatPenjualanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema:: create ('penjualan_detail',function(Blueprint $table)
        {

            $table->increments('id_penjualan_detail');
            $table->string('id_penjualan');
            $table->string('id_barang');
            $table->integer('harga_jual');
            $table->integer('jumlah');
            $table->tinyinteger('diskon')->default(0);
            $table->integer('subtotal');
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
        schema:: dropifexists('penjualan_detail');
    }
}
