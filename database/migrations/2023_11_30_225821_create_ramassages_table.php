<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRamassagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ramassages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); //Fournisseur
            $table->text('description');
            $table->text('statut');
            $table->text('city');
            $table->text('adress');
            $table->string('phone');
            $table->integer('number');
            $table->float('livreurId');
            $table->date('prevu_at');
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
        Schema::dropIfExists('ramassages');
    }
}
