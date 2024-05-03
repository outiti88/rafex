<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfertRetoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfert_retours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); //superviseur
            $table->string('reference');
            $table->string('statut');
            $table->string('from_city');
            $table->string('to_city');
            $table->text('comment');
            $table->integer('sent');
            $table->integer('received');
            $table->date('validate_at');
            $table->unsignedBigInteger('validate_by');
            $table->foreign('validate_by')->references('id')->on('users');
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
        Schema::dropIfExists('transfert_retours');
    }
}
