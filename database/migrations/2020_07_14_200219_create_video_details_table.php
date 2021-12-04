<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_subjects_mapped_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_root_name');
            $table->string('thumbnail_name')->nullable();
            $table->string('quiz_file_name')->nullable();
            $table->tinyInteger('video_type')->default(1)->comment('1:Non-Premium Video, 2: Premium Video');
            $table->tinyInteger('status')->default(1)->comment('1:Active, 0:Inactive');
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
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
        Schema::dropIfExists('video_details');
    }
}
