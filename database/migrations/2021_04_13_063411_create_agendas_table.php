<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('meeting_id')->unsigned();
            $table->boolean('is_two_urgencies')->default(0);
            $table->bigint('agenda_id')->unsigned()->nullable();
            $table->string('summary',150);
            $table->string('description',1000)->default('');
            $table->string('rules',1000)->default('');
            $table->boolean('is_started')->default(0);
            $table->boolean('is_ended')->default(0);
            $table->boolean('is_allowed_to_vote')->default(0);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('order')->default(1);
            $table->integer('agrees')->default(0);
            $table->integer('disagrees')->default(0);
            $table->integer('abstentions')->default(0);
            $table->integer('absents')->default(0);
            $table->integer('count_users')->default(0);
            $table->enum('result',['accepted','rejected'])->nullable();
            $table->timestamps();

            $table->foreign('meeting_id')
                ->references('id')
                ->on('meetings')
                ->onUpdate('cascade')
                ->onDelete('no action');

            $table->foreign('agenda_id')
                ->references('id')
                ->on('agendas')
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
        Schema::dropIfExists('agendas');
    }
}
