<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedInteger('rns_unit_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->string('company_id')->nullable();
            $table->string('prop_name')->nullable();
            $table->string('prop_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->unsignedInteger('beds')->nullable();
            $table->unsignedInteger('baths')->nullable();
            $table->unsignedInteger('sleeps')->nullable();
            $table->boolean('inactive')->nullable();
            $table->unsignedInteger('turn_day')->nullable();
            $table->text('description')->nullable();
            $table->string('geocode')->nullable();
            $table->text('reviews')->nullable();
            $table->unsignedInteger('unit_types_list_id')->nullable();
            $table->unsignedInteger('subdivisions_id')->nullable();
            $table->unsignedInteger('reservation_group_id')->nullable();
            $table->unsignedInteger('finance_group_id')->nullable();
            $table->unsignedInteger('persons_per_rental')->nullable();
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
        Schema::dropIfExists('details');
    }
}
