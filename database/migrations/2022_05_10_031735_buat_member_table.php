<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema:: create ('member',function(Blueprint $table)
        {
            $table->increments('id_member');
            $table->string('kode_member')->unique();
            $table->string('nama');
            $table->text('alamat');
            $table->string('telepon');
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
        schema:: dropifexists('member');
    }
}
