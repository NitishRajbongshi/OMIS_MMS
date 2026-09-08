<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoadDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('road_details', function (Blueprint $table) {
            $table->id();
            $table->string('RNo');
            $table->string('RName');
            $table->string('RLTotal');
            $table->string('chF');
            $table->string('chT');
            $table->string('RSLocationFrom');
            $table->string('RSLocationTo');
            $table->string('Rcat');
            $table->string('DistNo');
            $table->string('Division');
            $table->string('RdClass');
            $table->date('Date');
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
        Schema::dropIfExists('road_details');
    }
}
