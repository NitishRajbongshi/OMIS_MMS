<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_details', function (Blueprint $table) {
            $table->id();
            $table->string('office_name');
            $table->string('department_id');
            $table->string('parent_office'); // wheather Y/N
            $table->string('parent_office_id')->nullable();
            $table->string('office_level');  // L0 if parent and L1,l2 and so on
            $table->string('state');
            $table->string('district');
            $table->string('subdistrict')->nullable();
            $table->string('block')->nullable();
            $table->string('village')->nullable();
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
        Schema::dropIfExists('office_details');
    }
}



