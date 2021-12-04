<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'class_id', 'name', 'email', 'mobile_number', 'amount', 'receipt_id', 'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'error_code', 'error_description', 'status'
    ];

    /**
     * get class details for paid payment
     */
    public function getActiveSubscriptionClass()
    {
        return $this->belongsTo('App\Classes', 'class_id', 'id');
    }
}
