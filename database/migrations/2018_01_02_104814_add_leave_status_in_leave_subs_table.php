<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeaveStatusInLeaveSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_subs', function (Blueprint $table) {
            $table->integer('leave_approve_status')->after('half_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_subs', function (Blueprint $table) {
            $table->dropColumn('leave_approve_status');
        });
    }
}
