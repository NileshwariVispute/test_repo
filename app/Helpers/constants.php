<?php

//Status Constants
define("SUCCESS", 'success');
define("FAIL", 'error');
define("ERROR", 'error');
define("VALIDATION_FAIL", 1);
define("EXCEPTION_ERROR", 2);
define("TOKEN_MISMATCH", 3);
define("EMAIL_VERIFICATION_PENDING", 4);

//upload files path
define("SUBJECT_LOGO_UPLOAD", '/subjects_logo');
define("VIDEO_THUMBNAILS_PATH", '/video_thumbnails');
define("VIDEO_QUIZ_PATH", '/quiz_files');
define("VIDEO_UPLOAD_PATH", '/videos');

//Razorpay credentials Live
// define("RAZORPAY_PAY_KEY", 'rzp_live_d2l5BWjVjUHY14');
// define("RAZORPAY_PAY_SECRET_KEY", 'uJBDWXVcppeYucLo9tHCRdOl');

//Razorpay credentials UAT
define("RAZORPAY_PAY_KEY", 'rzp_test_WT8Gn7My3mDekR');
define("RAZORPAY_PAY_SECRET_KEY", 'OgAEUjQ0evj0q5s8lv0GyDCx');
