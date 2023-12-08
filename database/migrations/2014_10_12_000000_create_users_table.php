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
            $table->string('username', 100);
            $table->string('password', 255);
            $table->string('email', 100)->unique();
            $table->bigInteger('file_id')->unsigned()->nullable();
            $table->timestamp('registration_date')->nullable();
            $table->boolean('is_active');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('title', 100);
            $table->string('national_code',10)->default('');
            $table->string('mobile', 11)->unique();
            $table->string('phone', 11)->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->rememberToken();
            $table->timestamp('token_valid_time')->nullable();
            $table->boolean('is_online')->default(0);
            $table->timestamp('last_activity_time')->nullable();
            $table->timestamps();

            $table->foreign('file_id')
                ->references('id')
                ->on('files')
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
        Schema::dropIfExists('users');
    }
}
