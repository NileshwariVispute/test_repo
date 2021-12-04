<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_account', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mobile_number', 30);
            $table->string('password');
            $table->rememberToken();
            $table->string('password_reset_token')->nullable();
            $table->dateTime('password_reset_at')->nullable();
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->foreignId('class_id')->nullable();
            $table->tinyInteger('user_type')->default(1)->comment('1:Non-Premium User, 2: Premium User');
            $table->tinyInteger('status')->default(1)->comment('1:Active User, 0:Inactive User');
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
        Schema::dropIfExists('user_account');
    }
}
