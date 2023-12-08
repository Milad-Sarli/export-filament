<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('meeting_no')->unsigned();
            $table->string('subject',150);
            $table->bigInteger('committee_id')->unsigned();
            $table->string('meeting_place',100);
            $table->date('meeting_date');
            $table->string('begin_time',10);
            $table->string('end_time',10);
            $table->timestamp('meeting_started_at')->nullable();
            $table->timestamp('meeting_ended_at')->nullable();
            $table->enum('meeting_type',['عادی','فوق العاده']);
            $table->bigInteger('applicant_authority_id')->unsigned();
            $table->bigInteger('meeting_boss_user_id')->unsigned();
            $table->timestamps();

            $table->foreign('committee_id')
                ->references('id')
                ->on('committees')
                ->onUpdate('cascade')
                ->onDelete('no action');

            $table->foreign('applicant_authority_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('no action');

            $table->foreign('meeting_boss_user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
