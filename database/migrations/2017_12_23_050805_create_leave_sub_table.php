<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_subs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leave_id')->unsigned();
            $table->foreign('leave_id')->references('id')
                ->on('leaves')->onDelete('cascade');
            $table->date('leave_date')->nullable();
            $table->string('half_day')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('leave_subs');
    }
}
