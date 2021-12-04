<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_details', function (Blueprint $table) {
            $table->id();
            $table->string('class_name', 150);
            $table->string('class_fee', 50)->default(0)->nullable();
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
        Schema::dropIfExists('class_details');
    }
}
