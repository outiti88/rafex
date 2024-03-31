<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatedCommandeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('updated_commande', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('telephone');
            $table->text('adresse');
            $table->float('montant');
            $table->boolean('is_fragile');
            $table->boolean('isOpen');
            $table->foreignId('reclamation_id')->constrained();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('updated_commande');
    }
}
