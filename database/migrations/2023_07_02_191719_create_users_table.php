<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phoneno');
            $table->string('address1');
            $table->string('address2');
            $table->string('district');
            $table->string('pin');
            $table->string('state');
            $table->string('country');
            $table->string('gender');
            $table->integer('user_role_id');
            $table->string('department');
            $table->string('office');
            $table->string('designation');
            $table->string('post');
            $table->string('activity_status');          // A - Active, D - Deactive
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
