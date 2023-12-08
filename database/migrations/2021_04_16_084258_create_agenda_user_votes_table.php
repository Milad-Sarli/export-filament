<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaUserVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_user_votes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agenda_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->enum('vote',['agree','disagree','abstention','absent']);
            $table->timestamps();

            $table->foreign('agenda_id')
                ->references('id')
                ->on('agendas')
                ->onUpdate('cascade')
                ->onDelete('no action');

            $table->foreign('user_id')
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
        Schema::dropIfExists('agenda_user_votes');
    }
}
