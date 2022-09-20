<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatReturDetailTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            schema:: create ('retur_detail',function(Blueprint $table)
            {
    
                $table->increments('id_returdetail');
                $table->string('id_retur');
                $table->string('id_barangretur');
                $table->string('id_barangganti');
                $table->integer('total_retur');
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
            schema:: dropifexists('retur_detail');
        }
    }
