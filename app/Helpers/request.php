<?php

//Get only parameter from request that mention in request array

//api request
define("CREATE_USER_REQUEST", ['name', 'email', 'password', 'phone_number', 'class_id']);
define("USER_LOGIN_REQUEST", ['email', 'password']);
define("FORGOT_PASSWORD_REQUEST", ['email']);
define("GET_RESET_PASSWORD_REQUEST", ['token', 'email']);
define("POST_RESET_PASSWORD_REQUEST", ['token', 'email', 'password', 'password_confirmation']);
define("GET_SUBJECTS_API_REQUST", ['class_id']);
define("GET_VIDEO_REQUEST", ['class_id', 'subject_id']);
define("VIDEO_VIEW_API_REQUEST", ['video_id']);
define("UPDATE_PROFILE_API_REQUEST", ['id', 'name', 'email', 'phone_number', 'class_id']);
define("NEW_PAYMENT_API_REQUEST", ['class_id', 'amount']);
define("UPDATE_API_REQUEST", ['payment_status', 'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'error_code', 'error_description']);
define("PAUSE_VIDEO_API_REQUEST", ['video_id', 'time']);
define("EMAIL_VERIFY_API_REQUEST", ['email', 'otp']);

//admin side request
define("UPLOAD_VIDEO_REQUEST", ['video_title', 'description', 'video_type', 'class_id', 'subject_id']);
