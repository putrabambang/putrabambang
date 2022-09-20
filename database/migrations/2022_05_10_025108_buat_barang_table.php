<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema:: create ('barang',function(Blueprint $table)
        {

            $table->increments('id_barang');
            $table->string('id_kategori');
            $table->string('nama_barang');
            $table->integer('harga_jual');
            $table->integer('stok');
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
        schema:: dropifexists('barang');
    }
}
