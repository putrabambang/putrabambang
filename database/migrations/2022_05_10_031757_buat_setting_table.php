<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema:: create ('setting',function(Blueprint $table)
        {
            $table->increments('id_setting');
            $table->string('nama_perusahaan')->unique();
            $table->text('alamat');
            $table->string('telepon');
            $table->tinyinteger('tipe_nota');
            $table->smallInteger('diskon');
            $table->string('path_logo');
            $table->string('path_kartu_member');
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
        schema:: dropifexists('setting');
    }
}
