<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatpenggilinganBaksoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema:: create ('penggilingan',function(Blueprint $table)
        {

            $table->increments('id_penggilingan');
            $table->integer('total_item');
            $table->integer('total_harga');
            $table->integer('total_akhir');
            $table->integer('bayar')->default(0);
            $table->integer('diterima')->default(0);
            $table->string('id_user')->default(0);
            $table->integer('ambil')->default(0);
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
        schema:: dropifexists('penggilingan');
    }
}
