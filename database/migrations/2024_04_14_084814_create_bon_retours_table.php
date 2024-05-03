<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonRetoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bon_retours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); //superviseur
            $table->string('reference');
            $table->string('city');
            $table->integer('orders');
            $table->unsignedBigInteger('livreur');
            $table->foreign('livreur')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bon_retours');
    }
}
