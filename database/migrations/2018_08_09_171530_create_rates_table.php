<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('unit_id')->index();
            $table->unsignedInteger('rns_unit_id')->index();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('daily')->nullable();
            $table->integer('weekly')->nullable();
            $table->integer('monthly')->nullable();
            $table->integer('minimun_nights')->nullable();
            $table->boolean('blackout')->default(0);
            $table->boolean('ignore_start_day')->default(0);
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
        Schema::dropIfExists('rates');
    }
}
