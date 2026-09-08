<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log_details', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('activity_type');            // what kind of operation is performed ( create, update or delete)
            $table->string('task_type');                // which attribute is inserted,updated,deleted (eg: department name is changed)
            $table->string('task_description');         // (eg: department name <Account Department> is deleted
            $table->string('ipAddress');
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
        Schema::dropIfExists('activity_log_details');
    }
}
