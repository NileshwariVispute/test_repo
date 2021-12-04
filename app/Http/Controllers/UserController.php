<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes;
use App\Subjects;
use App\ClassSubjectsMapped;
use App\Video;
use App\UserAccount;
use App\UserLoginHistory;
use Validator;
use Auth;
use View;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Description: view all active app users
     * Date: Date: 16-July-2020
     * @author Ganesh Suryawanshi
     *
     * @return view
     */
    public function viewAllUsers()
    {
        try {
            $users = UserAccount::distinct()
                ->select('id', 'name', 'email', 'mobile_number', 'class_id', 'created_at as account_open_date')
                ->where('status', 1)
                ->with(['usersLoginHistory' => function ($query) {
                    $query->select('user_id','created_at as last_login');
                }])
                ->with(['classFeePaidUser' => function ($query) {
                    $query->select('user_id','class_id')->where('status', 2);
                }])
                ->orderBy('created_at', 'desc')
                ->paginate(25);
            $users_data = $users->toArray()['data'];
            $classes = Classes::select('id', 'class_name')
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            $classes = array_column($classes, 'class_name', 'id');
            return view('users.view-users', compact('users', 'classes', 'users_data'));
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }
}
