<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_criterias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('unit_id')->index();
            $table->unsignedInteger('rns_unit_id')->index();
            $table->unsignedInteger('rns_id');
            $table->string('name')->index();
            $table->unsignedInteger('sort_order');
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
        Schema::dropIfExists('search_criterias');
    }
}
