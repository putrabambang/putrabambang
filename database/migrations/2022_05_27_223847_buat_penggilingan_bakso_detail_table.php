<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatpenggilinganBaksoDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema:: create ('penggilingan_detail',function(Blueprint $table)
        {

            $table->increments('id_penggilingan_detail');
            $table->string('id_penggilingan');
            $table->string('id_item');
            $table->integer('harga');
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->integer('total_akhir');
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
        schema:: dropifexists('penggilingan_detail');
    }
}
