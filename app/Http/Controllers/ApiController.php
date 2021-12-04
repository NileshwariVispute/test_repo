<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Classes;
use App\Subjects;
use App\ClassSubjectsMapped;
use App\UserAccount;
use App\Video;
use App\UserLoginHistory;
use App\VideoViewHistory;
use App\Payment;
use App\VideoWatch;
use App\EmailVefification;
use Validator;
use Mail;
use Redirect;
use Razorpay\Api\Api;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Description: retrun false when token not match
     * Date: Date: 14-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function tokenMisMatch()
    {
        return buildResponse(FAIL, TOKEN_MISMATCH, ['Token Invalid']);
    }

    /**
     * Description: get all active class list
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function getAllClass(Request $request)
    {
        try {
            Log::info('Enter in getAllClass Function.');
            $classes = Classes::select('id', 'class_name')
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            return buildResponse(SUCCESS, '', '', $classes);
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: get subject list for requested class
     * Date: Date: 17-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function getClassWiseSubjects(Request $request)
    {
        try {
            $input_data = $request->only(GET_SUBJECTS_API_REQUST);
            Log::info('Enter in getClassWiseSubjects Function ('. $input_data['class_id'] .'): ' . json_encode($input_data));
            $validator = Validator::make($input_data, GET_SUBJECTS_API_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in getClassWiseSubjects Function ('. $input_data['class_id'] .'): '.json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                $subjects = ClassSubjectsMapped::select('id', 'class_id', 'subject_id')
                    ->where('status', 1)
                    ->where('class_id', $request->class_id)
                    ->with(['classWiseSubjects' => function ($query) {
                        $query->select('id', 'subject_name')->where('status', 1);
                    }])
                    ->get()
                    ->toArray();
                if (! empty($subjects)) {
                    foreach ($subjects as $sb_k => $sb_value) {
                        $response_array[] = array(
                            'id' => $sb_value['subject_id'],
                            'subject_name' => $sb_value['class_wise_subjects']['subject_name']
                        );
                    }
                    $response_data = buildResponse(SUCCESS, '', '', $response_array);
                } else {
                    $response_data = buildResponse(SUCCESS, '', 'No Subjects Found', array());
                }
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: get form data from request, validate it and crete new user profile
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function createNewUser(Request $request)
    {
        try {
            $input_data = $request->only(CREATE_USER_REQUEST);
            Log::info('Enter in createNewUser Function ('. $input_data['email'] .'): ' . json_encode($input_data));
            $validator = Validator::make($input_data, CREATE_USER_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in createNewUser Function ('. $input_data['email'] .'): '.json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                $token = Str::random(60);
                $user = UserAccount::firstOrCreate(
                    ['email' => $input_data['email']],
                    [
                        'name' => $input_data['name'],
                        'mobile_number' => $input_data['phone_number'],
                        'password' => Hash::make($input_data['password']),
                        'class_id' => $input_data['class_id'],
                        'api_token' => hash('sha256', $token)
                    ]
                );

                $email_otp = mt_rand(100000, 999999);
                // store otp into database
                $emailVefification = new EmailVefification();
                $emailVefification->user_id = $user->id;
                $emailVefification->send_to = $user->email;
                $emailVefification->otp = $email_otp;
                $emailVefification->save();

                $email_array = array(
                    'name' => $user->name,
                    'email_verification_otp' => $email_otp
                );
                Mail::send('emails.verify-email', $email_array, function ($m) use ($user) {
                    $m->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
                    $m->to($user->email, $user->name)->subject('Email Verification');
                });
                $this->insertLoginLog($user);
                $user_data = array(
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'class_id' => $user->class_id,
                    'user_type' => $user->user_type,
                    'api_token' => $token
                );
                $response_data = buildResponse(SUCCESS, '', '', $user_data);
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: match email verification OTP that send to email with user enterd
     * Date: Date: 03-Aug-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function verifyEmailOtp(Request $request)
    {
        try {
            $input_data = $request->only(EMAIL_VERIFY_API_REQUEST);
            $validator = Validator::make($input_data, EMAIL_VERIFY_API_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in verifyEmailOtp Function ('. $input_data['email'] .'): '.json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                Log::info('Enter in verifyEmailOtp Function ('. $input_data['email'] .'): '.json_encode($input_data));
                $emailVefification = EmailVefification::where('send_to', $input_data['email'])->orderBy('id', 'DESC')->first();
                // calculate otp expired time
                $endTime = strtotime("+60 minutes", strtotime($emailVefification->created_at));
                if ($endTime >= strtotime('now')) {
                    if ($input_data['otp'] === $emailVefification->otp) {
                        $emailVefification->delete();
                        $user = UserAccount::where('email', $input_data['email'])->first();
                        $user->email_verified_at = date('Y-m-d H:i:s');
                        $user->save();
                        $response_data = buildResponse(SUCCESS, '', 'Email Verified Successfully');
                    } else {
                        $response_data = buildResponse(FAIL, VALIDATION_FAIL, 'Invalid OTP!');
                    }
                } else {
                    $emailVefification->delete();
                    //create new OTP and sent to user email ID
                    $user = UserAccount::where('email', $input_data['email'])->first();
                    $email_otp = mt_rand(100000, 999999);
                    // store otp into database
                    $emailVefification = new EmailVefification();
                    $emailVefification->user_id = $user->id;
                    $emailVefification->send_to = $user->email;
                    $emailVefification->otp = $email_otp;
                    $emailVefification->save();

                    $email_array = array(
                        'name' => $user->name,
                        'email_verification_otp' => $email_otp
                    );
                    Mail::send('emails.verify-email', $email_array, function ($m) use ($user) {
                        $m->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
                        $m->to($user->email, $user->name)->subject('Email Verification');
                    });
                    $response_data = buildResponse(FAIL, VALIDATION_FAIL, 'Entred OTP is expired! New Email Verification OTP has been sent to your email ID.');
                }
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: login user into api and generate new api token for user
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function loginUser(Request $request)
    {
        try {
            $input_data = $request->only(USER_LOGIN_REQUEST);
            Log::info('Enter in loginUser Function ('. $input_data['email'] .'): '.json_encode($input_data));
            $validator = Validator::make($input_data, USER_LOGIN_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in loginUser Function ('. $input_data['email'] .'): '.json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                $user = UserAccount::where('email', $input_data['email'])->first();
                if ($user !== null) {
                    if (Hash::check($input_data['password'], $user->password)) {
                        $this->insertLoginLog($user);
                        $token = Str::random(60);
                        $user->api_token = hash('sha256', $token);
                        $user->save();
                        $user_data = array(
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'class_id' => $user->class_id,
                            'user_type' => $user->user_type,
                            'api_token' => $token
                        );
                        if (empty($user->email_verified_at)) {
                            $email_otp = mt_rand(100000, 999999);
                            // store otp into database
                            $emailVefification = new EmailVefification();
                            $emailVefification->user_id = $user->id;
                            $emailVefification->send_to = $user->email;
                            $emailVefification->otp = $email_otp;
                            $emailVefification->save();

                            $email_array = array(
                                'name' => $user->name,
                                'email_verification_otp' => $email_otp
                            );
                            Mail::send('emails.verify-email', $email_array, function ($m) use ($user) {
                                $m->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
                                $m->to($user->email, $user->name)->subject('Email Verification');
                            });
                            $response_data = buildResponse(SUCCESS, EMAIL_VERIFICATION_PENDING, ['Email Verification Pending! New Email Verification OTP has been sent to your email ID.'], $user_data);
                        } else {
                            $response_data = buildResponse(SUCCESS, '', '', $user_data);
                        }
                    } else {
                        $response_data = buildResponse(ERROR, VALIDATION_FAIL, ['These credentials do not match our records.']);
                    }
                } else {
                    $response_data = buildResponse(ERROR, VALIDATION_FAIL, ['User doesn\'t exist']);
                }
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: insert login log user wise after successfully login
     * Date: Date: 19-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param $user stdClass
     * @return null
     */
    private function insertLoginLog($user)
    {
        try {
            $user_history = new UserLoginHistory;
            $user_history->user_id = $user->id;
            $user_history->ip_address = \request()->ip();
            $user_history->save();
            return true;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: forgot password, validate user and send password reset email
     * Date: Date: 17-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function forgotPassword(Request $request)
    {
        try {
            $input_data = $request->only(FORGOT_PASSWORD_REQUEST);
            Log::info('Enter in forgotPassword Function ('. $input_data['email'] .'): '.json_encode($input_data));
            $validator = Validator::make($input_data, FORGOT_PASSWORD_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in forgotPassword Function ('. $input_data['email'] .'): '.json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                $user = UserAccount::where('email', $input_data['email'])->first();
                if ($user !== null) {
                    $token = Str::random(70);
                    $user->password_reset_token = Hash::make($token);
                    $user->password_reset_at = date('Y-m-d H:i:s');
                    $user->save();
                    $url = url(route('password-reset', [
                        'token' => $token,
                        'email' => $input_data['email'],
                    ], false));
                    $email_array = array(
                        'name' => $user->name,
                        'url' => $url
                    );
                    Mail::send('emails.forgot-password', $email_array, function ($m) use ($user) {
                        $m->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
                        $m->to($user->email, $user->name)->subject('Reset Password Notification');
                    });

                    // check for failures
                    if (Mail::failures()) {
                        $response_data = buildResponse(ERROR, VALIDATION_FAIL, ['Failed to send Reset Password Mail']);
                    } else {
                        $response_data = buildResponse(SUCCESS);
                    }
                } else {
                    $response_data = buildResponse(ERROR, VALIDATION_FAIL, ['User doesn\'t exist']);
                }
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: get Reset user password view
     * Date: Date: 17-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return view
     */
    public function getUserPasswordReset(Request $request)
    {
        try {
            $input_data = $request->only(GET_RESET_PASSWORD_REQUEST);
            Log::info('Enter in forgotPassword Function ('. $input_data['email'] .'): '.json_encode($input_data));
            $validator = Validator::make($input_data, GET_RESET_PASSWORD_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in getUserPasswordReset Function ('. $input_data['email'] .'): '.json_encode($validator->getMessageBag()->toArray()));
                return view('auth.passwords.user-reset-password-msg', [
                    'msg' => 'Mising Requird parameters',
                    'sub_msg' => 'The link is not valid, please request the new link for reset password.'
                ]);
            } else {
                return view('auth.passwords.user-reset-password', $input_data);
            }
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: Reset user password
     * Date: Date: 17-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return redirect / view
     */
    public function postUserPasswordReset(Request $request)
    {
        try {
            $input_data = $request->only(POST_RESET_PASSWORD_REQUEST);
            Log::info('Enter in postUserPasswordReset Function ('. $input_data['email'] .'): '.json_encode($input_data));
            $validator = Validator::make($input_data, POST_RESET_PASSWORD_VALIDATION);
            if ($validator->fails()) {
                foreach ($validator->getMessageBag()->toArray() as $key => $e_v) {
                    $error[$key] = $e_v[0];
                }
                Log::error('Validation Failed in postUserPasswordReset Function ('. $input_data['email'] .'): '.json_encode($error));
                return Redirect::back()->withInput()->withErrors($error);
            } else {
                $user = UserAccount::where('email', $input_data['email'])->first();
                if ($user !== null) {
                    $endTime = strtotime("+60 minutes", strtotime($user->password_reset_at));
                    if ($endTime >= strtotime('now')) {
                        if (Hash::check($input_data['token'], $user->password_reset_token)) {
                            $user->password = Hash::make($input_data['password']);
                            $user->password_reset_token = '';
                            $user->password_reset_at = null;
                            $user->save();
                            Log::info('Password reset successfully ('. $user->email .')');
                            return view('auth.passwords.user-reset-password-msg', [
                                'msg' => 'Your password has been reset!',
                                'sub_msg' => 'Password reset successfully, Please close this window and login with your new password.'
                            ]);
                        } else {
                            $error_msg['email'] = 'This password reset token is invalid.';
                            Log::error('Validation Failed in postUserPasswordReset Function ('. $user->email .'): '.json_encode($error_msg));
                            return Redirect::back()->withInput()->withErrors($error_msg);
                        }
                    } else {
                        Log::error('Validation Failed in postUserPasswordReset Function ('. $user->email .'): Password Reset Link Expired');
                        return view('auth.passwords.user-reset-password-msg', [
                            'msg' => 'Password Reset Link Expired',
                            'sub_msg' => 'The link is not valid, please request the new link for reset password.'
                        ]);
                    }
                } else {
                    $error_msg['email'] = 'User doesn\'t exist';
                    Log::error('Validation Failed in postUserPasswordReset Function ('. $input_data['email'] .'): ' . json_encode($error_msg));
                    return Redirect::back()->withInput()->withErrors($error_msg);
                }
            }
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: verify token on every time user open app also update login log for user
     * Date: Date: 26-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function verifyToken(Request $request)
    {
        try {
            $user = $request->user();
            Log::info('Enter in verifyToken Function ('. $user->email .')');
            $this->insertLoginLog($user);
            return buildResponse(SUCCESS, '', ['User Verified successfully!']);
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: Logour User and make api_token null
     * Date: Date: 14-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function logoutUser(Request $request)
    {
        try {
            $user = $request->user();
            Log::info('Enter in logoutUser Function ('. $user->email .')');
            $user->api_token = null;
            $user->save();
            return buildResponse(SUCCESS, '', ['User Logout successfully!']);
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: get videos class and subject wise
     * Date: Date: 16-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function getVideos(Request $request)
    {
        try {
            $input_data = $request->only(GET_VIDEO_REQUEST);
            Log::info('Enter in getVideos Function ('. $request->user()->email .'): '.json_encode($input_data));
            $validator = Validator::make($input_data, GET_VIDEO_API_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in getVideos Function: ' . json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                // get available video for request class and subject
                $class_subjects_mapped_id = ClassSubjectsMapped::select('id', 'class_id')
                    ->where('class_id', $input_data['class_id'])
                    ->where('subject_id', $input_data['subject_id'])
                    ->with(['videos' => function ($query) {
                        $query->select('id', 'class_subjects_mapped_id', 'title', 'description', 'video_type', 'thumbnail_name', 'video_root_name', 'quiz_file_name')
                            ->where('status', 1)
                            ->with(['VideoWatchDetails' => function ($query) {
                                $query->select('video_id', 'continue_at')->where('continue_watching', 1);
                            }]);
                    }])
                    ->where('status', 1)
                    ->get()
                    ->toArray();
                // if viddeo found for given class and subject build resposne array
                if (! empty($class_subjects_mapped_id[0]['videos'])) {
                    foreach ($class_subjects_mapped_id[0]['videos'] as $v_k => $v_value) {
                        $temp_array = array(
                            'id' => $v_value['id'],
                            'title' => $v_value['title'],
                            'description' => $v_value['description'],
                            'video_type' => $v_value['video_type'],
                            'thumbnail_path' => $request->root() . VIDEO_THUMBNAILS_PATH . '/' . $v_value['thumbnail_name'],
                            'video_path' => $request->root() . VIDEO_UPLOAD_PATH . '/' . $v_value['video_root_name'],
                            'quiz_file_path' => '',
                            'continue_at' => ''
                        );
                        if (!empty($v_value['quiz_file_name'])) {
                            $temp_array['quiz_file_path'] = $request->root() . VIDEO_QUIZ_PATH . '/' . $v_value['quiz_file_name'];
                        }
                        if (! empty($v_value['video_watch_details'])) {
                            $temp_array['continue_at'] = $v_value['video_watch_details']['continue_at'];
                        }
                        $response_array[] = $temp_array;
                    }
                    $response_data = buildResponse(SUCCESS, '', '', $response_array);
                } else {
                    $response_data = buildResponse(SUCCESS, '', 'No Video Found!');
                }
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: update view count when user click on view video button
     * Date: Date: 20-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function updateVideoView(Request $request)
    {
        try {
            $input_data = $request->only(VIDEO_VIEW_API_REQUEST);
            Log::info('Enter in updateVideoView Function ('. $request->user()->email .'): ' . json_encode($input_data));
            $validator = Validator::make($input_data, VIDEO_VIEW_API_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in updateVideoView Function ('. $request->user()->email .'): ' . json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                $video_details = Video::find($input_data['video_id']);
                if (! empty($video_details)) {
                    $video_history = new VideoViewHistory;
                    $video_history->user_id = $request->user()->id;
                    $video_history->video_id = $input_data['video_id'];
                    $video_history->save();
                    $response_data = buildResponse(SUCCESS);
                } else {
                    $response_data = buildResponse(ERROR, '', 'No Video Found!');
                }
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: when user leave watching video save the details into continue watching
     * Date: Date: 01-August-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function savePauseVideo(Request $request)
    {
        try {
            $input_data = $request->only(PAUSE_VIDEO_API_REQUEST);
            Log::info('Enter in savePauseVideo Function ('. $request->user()->email .'): ' . json_encode($input_data));
            $validator = Validator::make($input_data, PAUSE_VIDEO_API_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in savePauseVideo Function ('. $request->user()->email .'): ' . json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                $videowatch = VideoWatch::updateOrCreate(
                    ['user_id' => $request->user()->id, 'video_id' => $input_data['video_id']],
                    ['continue_watching' => 1, 'continue_at' => $input_data['time']]
                );
                $response_data = buildResponse(SUCCESS);
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: get user profile details
     * Date: Date: 20-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function getUserProfileDetails(Request $request)
    {
        try {
            Log::info('Enter in getUserProfileDetails Function ('. $request->user()->email .')');
            $user_data = array(
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'class_id' => $request->user()->class_id,
                'user_type' => $request->user()->user_type,
                'mobile_number' => $request->user()->mobile_number,
                'mobile_number' => $request->user()->mobile_number
            );
            return buildResponse(SUCCESS, '', '', $user_data);
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: update user details into DB
     * Date: Date: 20-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function updateUserProfileDetails(Request $request)
    {
        try {
            $input_data = $request->only(UPDATE_PROFILE_API_REQUEST);
            Log::info('Enter in updateUserProfileDetails Function ('. $request->user()->email .'): ' . json_encode($input_data));
            $rules = UPDATE_PROFILE_API_VALIDATION;
            //while validating email and phone_number ignore the current user
            $rules['email'] .= '|unique:App\UserAccount,email,' . $request->user()->id;
            $rules['phone_number'] .= '|unique:App\UserAccount,mobile_number,' . $request->user()->id;
            $validator = Validator::make($input_data, $rules);
            if ($validator->fails()) {
                Log::error('Validation Failed in updateUserProfileDetails Function ('. $request->user()->email .'): ' . json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                UserAccount::where('id', $request->user()->id)
                    ->update([
                        'email' => $input_data['email'],
                        'name' => $input_data['name'],
                        'mobile_number' => $input_data['phone_number'],
                        'class_id' => $input_data['class_id'],
                    ]);
                $response_data = buildResponse(SUCCESS, '', ['User Details Update successfully!']);
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: get all class and details about user paid fee for that class or not
     * Date: Date: 26-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function getUserClass(Request $request)
    {
        try {
            Log::info('Enter in getUserClass Function ('. $request->user()->email .')');
            $user_id = $request->user()->id;
            $classes = Classes::select('id', 'class_name', 'class_fee')
                ->with(['classFeePaidUser' => function ($query) use ($user_id) {
                    $query->select('class_id', 'status', 'created_at')->where('status', 2)->where('user_id', $user_id);
                }])
                ->where('status', 1)
                ->get()
                ->toArray();
            foreach ($classes as $key => $value) {
                $temp_array = array(
                    'id' => $value['id'],
                    'class_name' => $value['class_name'],
                    'class_fee' => $value['class_fee']
                );
                if (!empty($value['class_fee_paid_user']) && $value['class_fee_paid_user']['status'] == 2
                && date('Y-m-d', strtotime("+1 years", strtotime($value['class_fee_paid_user']['created_at']))) >= date('Y-m-d')) {
                    $temp_array['is_subscribed'] = 1;
                } else {
                    $temp_array['is_subscribed'] = 0;
                }
                $response_array[] = $temp_array;
            }
            return buildResponse(SUCCESS, '', '', $response_array);
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: Create new payment entry
     * Date: Date: 25-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function initializeNewPayment(Request $request)
    {
        try {
            $input_data = $request->only(NEW_PAYMENT_API_REQUEST);
            Log::info('Enter in initializeNewPayment Function ('. $request->user()->email .'): ' . json_encode($input_data));
            $validator = Validator::make($input_data, NEW_PAYMENT_API_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in initializeNewPayment Function ('. $request->user()->email .'): ' . json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                $razorpay = new Api(RAZORPAY_PAY_KEY, RAZORPAY_PAY_SECRET_KEY);
                $receipt_id = Str::random(30);
                $razorpayOrder = $razorpay->order->create(
                    array(
                        'receipt' => $receipt_id,
                        'amount' => $input_data['amount'] * 100,
                        'payment_capture' => 1,
                        'currency' => 'INR'
                    )
                );
                $payment = Payment::create(
                    [
                        'user_id' => $request->user()->id,
                        'class_id' => $input_data['class_id'],
                        'name' => $request->user()->name,
                        'email' => $request->user()->email,
                        'mobile_number' => $request->user()->mobile_number,
                        'amount' => $input_data['amount'],
                        'receipt_id' => $receipt_id,
                        'razorpay_order_id' => $razorpayOrder['id']
                    ]
                );
                $response_array = array(
                    'razorpay_order_id' => $payment->razorpay_order_id,
                    'amount' => $payment->amount,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'mobile_number' => $request->user()->mobile_number,
                );
                $response_data = buildResponse(SUCCESS, '', '', $response_array);
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: update payment info into DB(success/failed)
     * Date: Date: 26-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function updatePayment(Request $request)
    {
        try {
            $input_data = $request->only(UPDATE_API_REQUEST);
            Log::info('Enter in updatePayment Function ('. $request->user()->email .'): ' . json_encode($input_data));
            $validator = Validator::make($input_data, UPDATE_API_VALIDATION);
            if ($validator->fails()) {
                Log::error('Validation Failed in updatePayment Function ('. $request->user()->email .'): ' . json_encode($validator->getMessageBag()->toArray()));
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                if ($input_data['payment_status'] == 1) {
                    $success = true;
                    // Verify Payment Signature
                    try {
                        $razorpay = new Api(RAZORPAY_PAY_KEY, RAZORPAY_PAY_SECRET_KEY);
                        $attributes = array(
                            'razorpay_order_id' => $input_data['razorpay_order_id'],
                            'razorpay_payment_id' => $input_data['razorpay_payment_id'],
                            'razorpay_signature' => $input_data['razorpay_signature']
                        );
                        $razorpay->utility->verifyPaymentSignature($attributes);
                    } catch (SignatureVerificationError $e) {
                        $success = false;
                        $error = 'Razorpay Error : ' . $e->getMessage();
                    }
                    if ($success === true) {
                        Payment::where('razorpay_order_id', $input_data['razorpay_order_id'])
                            ->update([
                                'razorpay_payment_id' => $input_data['razorpay_payment_id'],
                                'razorpay_signature' => $input_data['razorpay_signature'],
                                'status' => 2
                            ]);
                    } else {
                        Payment::where('razorpay_order_id', $input_data['razorpay_order_id'])
                            ->update([
                                'error_code' => 'Verify Signature Failed',
                                'error_description' => $error,
                                'status' => 3
                            ]);
                    }
                } else {
                    Payment::where('razorpay_order_id', $input_data['razorpay_order_id'])
                        ->update([
                            'error_code' => $input_data['error_code'],
                            'error_description' => $input_data['error_description'],
                            'status' => 3
                        ]);
                }
                $response_data = buildResponse(SUCCESS, '', ['Payment Details Update successfully!']);
            }
            return $response_data;
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }

    /**
     * Description: get User active Subscription class wise
     * Date: Date: 31-July-2020
     * @author Ganesh Suryawanshi
     *
     * @param Illuminate\Http\Request
     * @return json
     */
    public function getUserActiveSubscription(Request $request)
    {
        try {
            Log::info('Enter in getUserActiveSubscription Function ('. $request->user()->email .')');
            $user_id = $request->user()->id;
            $subscription_list = Payment::select('id', 'user_id', 'class_id', 'amount', 'created_at')
                ->with(['getActiveSubscriptionClass' => function ($query) {
                    $query->select('id', 'class_name')->where('status', 1);
                }])
                ->where('status', 2)
                ->where('user_id', $user_id)
                ->whereRaw('DATE_ADD(created_at, INTERVAL 1 YEAR) >= ?', date('Y-m-d'))
                ->get()
                ->toArray();
            $response_array = array();
            if (! empty($subscription_list)) {
                foreach ($subscription_list as $key => $value) {
                    $response_array[] = array(
                        'class_id' => $value['class_id'],
                        'class_name' => $value['get_active_subscription_class']['class_name'],
                        'paid_amount' => $value['amount'],
                        'start_date' => date("Y-m-d", strtotime($value['created_at'])),
                        'end_date' => date("Y-m-d", strtotime('+1 year', strtotime($value['created_at'])))
                    );
                }
            }
            return buildResponse(SUCCESS, '', '', $response_array);
        } catch (Exception $exception) {
            return buildResponse(ERROR, EXCEPTION_ERROR, $exception->getMessage());
        }
    }
}
