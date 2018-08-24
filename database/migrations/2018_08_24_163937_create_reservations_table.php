<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('rns_id')->index();
            $table->date('arrive_date')->index();
            $table->date('depart_date')->index();
            $table->string('transaction_type');
            $table->string('transaction_type_description');
            $table->unsignedInteger('transaction_id');
            $table->string('email');
            $table->string('reservation_number');
            $table->string('reservation_type');
            $table->string('reservation_type_description');
            $table->unsignedInteger('agent_id');
            $table->date('dt_arrive_date');
            $table->boolean('deleted');
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
        Schema::dropIfExists('reservations');
    }
}
