<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntanglesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entangle_locations', function (Blueprint $table) {
            $table->integer('concept_id')->unsigned();
            $table->foreign('concept_id')->references('id')->on('concepts');
            $table->float('longitude')->nullable();
            $table->float('latitude')->nullable();
        });

        Schema::create('entangle_events', function (Blueprint $table) {
            $table->integer('concept_id')->unsigned();
            $table->foreign('concept_id')->references('id')->on('concepts');
            $table->integer('location_id')->unsigned()->nullable();
            $table->foreign('location_id')->references('id')->on('concepts');
            $table->date('date_from');
            $table->integer('duration')->nullable()->default(1);
            $table->string('duration_unit')->nullable()->default('d');
            $table->date('date_to')->nullable();
            $table->string('anniversary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entangle_events');
        Schema::dropIfExists('entangle_locations');
    }
}
