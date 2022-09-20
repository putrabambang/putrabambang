<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatTabelItemBakso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
         schema:: create ('item',function(Blueprint $table)
        {

            $table->increments('id_item');
            $table->string('nama_item');
            $table->integer('harga');
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
        schema:: dropifexists('item');
    }
}
