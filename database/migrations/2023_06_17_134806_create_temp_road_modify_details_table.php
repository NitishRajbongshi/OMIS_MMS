<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempRoadModifyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_road_modify_details', function (Blueprint $table) {
            $table->id();
            $table->string('road_details_id')->nullable();
            $table->string('RNo')->nullable();
            $table->string('RName')->nullable();
            $table->string('RLTotal')->nullable();
            $table->string('chF')->nullable();
            $table->string('chT')->nullable();
            $table->string('RSLocationFrom')->nullable();
            $table->string('RSLocationTo')->nullable();
            $table->string('Rcat')->nullable();
            $table->string('DistNo')->nullable();
            $table->string('Division')->nullable();
            $table->string('RdClass')->nullable();
            $table->Date('Date')->nullable();
            $table->string('reason1')->nullable();
            $table->string('reason2')->nullable();
            $table->string('reason3')->nullable();
            $table->string('reason4')->nullable();
            $table->string('reason5')->nullable();
            $table->string('reason6')->nullable();
            $table->string('reason7')->nullable();
            $table->string('reason8')->nullable();
            $table->string('reason9')->nullable();
            $table->string('reason10')->nullable();
            $table->string('reason11')->nullable();
            $table->string('reason12')->nullable();
            $table->string('status1')->nullable();  // Status 1 to 12 -> A-Approve, R-Reject, P-Pending
            $table->string('status2')->nullable();
            $table->string('status3')->nullable();
            $table->string('status4')->nullable();
            $table->string('status5')->nullable();
            $table->string('status6')->nullable();
            $table->string('status7')->nullable();
            $table->string('status8')->nullable();
            $table->string('status9')->nullable();
            $table->string('status10')->nullable();
            $table->string('status11')->nullable();
            $table->string('status12')->nullable();
            $table->string('request_sent_by')->nullable();
            $table->Date('sent_time')->nullable();
            $table->Date('approve_time')->nullable();
            $table->string('approve_status')->nullable(); // P-Pending, S-Submitted
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
        Schema::dropIfExists('temp_road_modify_details');
    }
}
