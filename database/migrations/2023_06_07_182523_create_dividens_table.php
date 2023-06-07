<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDividensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dividens', function (Blueprint $table) {
            $table->id();
            $table->string("id_investasi");
            $table->string("total_dividen");
            $table->string("nominal_pencairan");
            $table->string("opsi_pembayaran");
            $table->string("nama_rek");
            $table->string("status");
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
        Schema::dropIfExists('dividens');
    }
}
