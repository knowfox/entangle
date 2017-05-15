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
        Schema::create('entangle_entangles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('entangle_timelines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->string('title');
            $table->text('timelines');
            $table->timestamps();
        });

        Schema::create('entangle_entangled_timelines', function (Blueprint $table) {
            $table->integer('entangle_id')->unsigned();
            $table->foreign('entangle_id')->references('id')->on('entangle_entangles');
            $table->integer('timeline_id')->unsigned();
            $table->foreign('timeline_id')->references('id')->on('entangle_timelines');
            $table->timestamps();
        });

        Schema::create('entangle_locations', function (Blueprint $table) {
            $table->integer('concept_id')->unsigned();
            $table->foreign('concept_id')->references('id')->on('concepts');
            $table->float('longitude');
            $table->float('latitude');
        });

        Schema::create('entangle_events', function (Blueprint $table) {
            $table->integer('concept_id')->unsigned();
            $table->foreign('concept_id')->references('id')->on('concepts');
            $table->integer('timeline_id')->unsigned();
            $table->foreign('timeline_id')->references('id')->on('entangle_timelines');
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('concept_id')->on('entangle_locations');
            $table->date('date_from');
            $table->integer('duration');
            $table->string('duration_unit');
            $table->date('date_to');
            $table->string('anniversary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entangle_entangled_timelines');
        Schema::dropIfExists('entangle_entangles');
        Schema::dropIfExists('entangle_events');
        Schema::dropIfExists('entangle_timelines');
        Schema::dropIfExists('entangle_locations');
    }
}
