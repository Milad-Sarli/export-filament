<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommitteeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committee_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('committee_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->enum('role_in_committee',['boss','secretary','member'])->nullable();
            $table->timestamps();

            $table->foreign('committee_id')
                ->references('id')
                ->on('committees')
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
        Schema::dropIfExists('committee_user');
    }
}
