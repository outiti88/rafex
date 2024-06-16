<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBonretourIdToRetoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retours', function (Blueprint $table) {
            $table->unsignedBigInteger('bon_retour');
            $table->foreign('bon_retour')->references('id')->on('bon_retours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retours', function (Blueprint $table) {
            //
        });
    }
}
