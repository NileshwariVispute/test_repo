<?php

//validation rules for REST API
define("CREATE_USER_VALIDATION", array(
    'name' => 'required',
    'email' => 'required|email|unique:App\UserAccount,email',
    'password' => 'required',
    'phone_number' => 'required|integer|digits_between:10,20|unique:App\UserAccount,mobile_number',
    'class_id' => 'required|integer|exists:class_details,id'
));

define("USER_LOGIN_VALIDATION", array(
    'email' => 'required',
    'password' => 'required'
));

define("FORGOT_PASSWORD_VALIDATION", array(
    'email' => 'required|email',
));

define("GET_RESET_PASSWORD_VALIDATION", array(
    'email' => 'required|email',
    'token' => 'required',
));

define("POST_RESET_PASSWORD_VALIDATION", array(
    'email' => 'required|email',
    'token' => 'required',
    'password' => 'required|same:password_confirmation'
));

define("GET_VIDEO_API_VALIDATION", array(
    'class_id' => 'required|integer|exists:class_details,id',
    'subject_id' => 'required|integer|exists:subjects_master,id'
));

define("GET_SUBJECTS_API_VALIDATION", array(
    'class_id' => 'required|integer|exists:class_details,id'
));

define("VIDEO_VIEW_API_VALIDATION", array(
    'video_id' => 'required|integer|exists:video_details,id'
));

define("UPDATE_PROFILE_API_VALIDATION", array(
    'name' => 'required',
    'email' => 'required|email',
    'phone_number' => 'required|integer|digits_between:10,20',
    'class_id' => 'required|integer|exists:class_details,id'
));

define("NEW_PAYMENT_API_VALIDATION", array(
    'class_id' => 'required|integer|exists:class_details,id',
    'amount' => 'required|numeric|min:1'
));

define("UPDATE_API_VALIDATION", array(
    'payment_status' => 'required|in:1,0',
    'razorpay_order_id' => 'required',
    'razorpay_payment_id' => 'required_if:payment_status,1',
    'razorpay_signature' => 'required_if:payment_status,1',
    'error_code' => 'required_if:payment_status,0',
    'error_description' => 'required_if:payment_status,0'
));

define("PAUSE_VIDEO_API_VALIDATION", array(
    'video_id' => 'required|integer|exists:video_details,id',
    'time' => 'required'
));

define("EMAIL_VERIFY_API_VALIDATION", array(
    'email' => 'required|email|exists:user_account,email|exists:email_verification_otp,send_to',
    'otp' => 'required|integer'
));

// admin side validation rules
define("UPLOAD_VIDEO_VALIDATION", array(
    'video_title' => 'required',
    'description' => 'required',
    'video_type' => 'required',
    'class_id' => 'required|integer',
    'subject_id' => 'required|integer',
    'video_file'  => 'mimes:mp4,mov,ogg,qt'
));
