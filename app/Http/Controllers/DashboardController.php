<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes;
use App\Subjects;
use App\ClassSubjectsMapped;
use App\Video;
use App\UserAccount;
use App\UserLoginHistory;
use App\VideoViewHistory;
use Validator;
use Auth;
use DB;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            //get total users Count
            $total_users = UserAccount::distinct()->count('email');

            //get daily login count
            $login_count = UserLoginHistory::distinct()->whereDate('created_at', date('Y-m-d'))->count('user_id');

            //get total video count
            $video_count = Video::where('status', 1)->distinct()->count('id');

            //get all video name
            $videos = Video::select('id', 'title')->where('status', 1)->distinct()->get()->toArray();
            $videos = array_column($videos, 'title', 'id');

            //get video view count
            $video_view_count = VideoViewHistory::select(DB::Raw('video_id, COUNT(DISTINCT user_id) as video_count'))
                ->groupBy('video_id')
                ->orderBy('video_count', 'desc')
                ->get()
                ->toArray();
            $total_vv_count = array_sum(array_column($video_view_count, 'video_count'));

            //get users count class wise
            $users = UserAccount::select(DB::Raw('class_id, COUNT(DISTINCT id) as user_count'))
                ->groupBy('class_id')
                ->with('userClassName')
                ->orderBy('user_count', 'desc')
                ->get()
                ->toArray();
            return view('home', compact('total_users', 'login_count', 'video_count', 'video_view_count', 'total_vv_count', 'videos', 'users'));
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }
}
