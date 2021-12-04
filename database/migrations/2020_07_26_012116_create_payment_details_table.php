<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('class_id');
            $table->string('name', 200);
            $table->string('email', 150);
            $table->string('mobile_number', 30);
            $table->string('amount', 50);
            $table->string('receipt_id', 70)->nullable();
            $table->string('razorpay_order_id');
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->string('error_code', 50)->nullable();
            $table->string('error_description')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0:Created, 1:Pending, 2:Success, 3:Failed');
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
        Schema::dropIfExists('payment_details');
    }
}
