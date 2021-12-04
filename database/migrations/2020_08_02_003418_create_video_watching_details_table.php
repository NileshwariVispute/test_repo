<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoWatchingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_watching_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('video_id');
            $table->tinyInteger('continue_watching')->default(0)->comment('0:No, 1:Yes');
            $table->time('continue_at', 0);
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
        Schema::dropIfExists('video_watching_details');
    }
}
