<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes;
use App\Subjects;
use App\ClassSubjectsMapped;
use App\Video;
use Validator;
use Auth;
use View;
use File;

class VideoController extends Controller
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
     * Description: view all Videos
     * Date: Date: 15-July-2020
     * @author Ganesh Suryawanshi
     *
     * @return view
     */
    public function viewAllVideos()
    {
        try {
            $videos = Video::where('status', 1)
                ->orderBy('created_at', 'desc')
                ->with(['getClassSubject' => function ($query) {
                    $query->select('id', 'class_id', 'subject_id', 'status');
                }])
                ->paginate(12);
            $videos_data = $videos->toArray()['data'];
            $classes = Classes::select('id', 'class_name')
                ->where('status', 1)
                ->get()
                ->toArray();
            $subjects = Subjects::select('id', 'subject_name')
                ->where('status', 1)
                ->get()
                ->toArray();
            $classes = array_column($classes, 'class_name', 'id');
            $subjects = array_column($subjects, 'subject_name', 'id');
            return view('videos.view-video', compact('videos', 'classes', 'subjects', 'videos_data'));
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }

    /**
     * Description: search video option on view video page(search string in title)
     * Date: Date: 16-July-2020
     * @author Ganesh Suryawanshi
     *
     * @request Illuminate\Http\Request
     * @return json
     */
    public function getFiltersVideos(Request $request)
    {
        try {
            $videos = Video::where('status', 1)
                ->with(['getClassSubject' => function ($query) {
                    $query->select('id', 'class_id', 'subject_id', 'status');
                }])
                ->where('title', 'LIKE', $request->search_videos.'%')
                ->orderBy('created_at', 'desc')
                ->limit(12)
                ->paginate(12);
            $videos_data = $videos->toArray()['data'];
            $classes = Classes::select('id', 'class_name')
                ->where('status', 1)
                ->get()
                ->toArray();
            $subjects = Subjects::select('id', 'subject_name')
                ->where('status', 1)
                ->get()
                ->toArray();
            $classes = array_column($classes, 'class_name', 'id');
            $subjects = array_column($subjects, 'subject_name', 'id');
            $view = View::make('videos.view-video-content', compact('videos', 'classes', 'subjects', 'videos_data'));
            $html = $view->render();
            return buildResponse(SUCCESS, '', '', $html);
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }

    /**
     * Description: Get Add new video form page
     * Date: Date: 16-July-2020
     * @author Ganesh Suryawanshi
     *
     * @return view
     */
    public function getAddVideoView()
    {
        try {
            $classes = Classes::select('id', 'class_name')
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            return view('videos.add-video', ['classes' => $classes]);
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }

    /**
     * Description: Get Subject List for selected class
     * Date: Date: 16-July-2020
     * @author Ganesh Suryawanshi
     *
     * @request Illuminate\Http\Request
     * @return json
     */
    public function getClassOfSubjects(Request $request)
    {
        try {
            $subjects = ClassSubjectsMapped::select('id', 'class_id', 'subject_id')
                ->where('status', 1)
                ->where('class_id', $request->class_id)
                ->with(['classWiseSubjects' => function ($query) {
                    $query->select('id', 'subject_name')->where('status', 1);
                }])
                ->get()
                ->toArray();
            return buildResponse(SUCCESS, '', '', $subjects);
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }

    /**
     * Description: Upload new video and save video details in DB
     * Date: Date: 16-July-2020
     * @author Ganesh Suryawanshi
     *
     * @request Illuminate\Http\Request
     * @return json
     */
    public function postAddVideo(Request $request)
    {
        try {
            $input_data = $request->only(UPLOAD_VIDEO_REQUEST);
            $validator = Validator::make($input_data, UPLOAD_VIDEO_VALIDATION);
            if ($validator->fails()) {
                $response_data = buildResponse(FAIL, VALIDATION_FAIL, $validator->getMessageBag()->toArray());
            } else {
                $class_subjects_mapped_id = ClassSubjectsMapped::select('id')
                    ->where('class_id', $input_data['class_id'])
                    ->where('subject_id', $input_data['subject_id'])
                    ->where('status', 1)
                    ->first();
                //Insert details in DB
                $video = new Video;
                $video->title = $input_data['video_title'];
                $video->description = $input_data['description'];
                $video->video_type = $input_data['video_type'];
                $video->class_subjects_mapped_id = $class_subjects_mapped_id->id;
                $video->created_by = Auth::user()->id;

                //upload Video file and save filename into DB
                if ($request->hasFile('video_file')) {
                    $path = public_path(VIDEO_UPLOAD_PATH);
                    File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                    $file_name = time().'.'.$request->video_file->extension();
                    $file_upplod = $request->video_file->move($path, $file_name);
                    $video->video_root_name = $file_name;
                }

                //upload thumbnail file and save filename into DB
                if ($request->hasFile('thumbnail')) {
                    $path = public_path(VIDEO_THUMBNAILS_PATH);
                    File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                    $file_name = time().'.'.$request->thumbnail->extension();
                    $file_upplod = $request->thumbnail->move($path, $file_name);
                    $video->thumbnail_name = $file_name;
                }

                //upload quiz file and save filename into DB
                if ($request->hasFile('quiz_file')) {
                    $path = public_path(VIDEO_QUIZ_PATH);
                    File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                    $file_name = time().'.'.$request->quiz_file->extension();
                    $file_upplod = $request->quiz_file->move($path, $file_name);
                    $video->quiz_file_name = $file_name;
                }
                $video->save();
                $response_data = buildResponse(SUCCESS);
            }
            return $response_data;
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }

    /**
     * Description: update subject using subject id
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @request Illuminate\Http\Request
     * @return redirect
     */
    public function postUpdateSubject(Request $request)
    {
        try {
            $input_data = $request->only(['subject_id', 'update_subject_name', 'update_logo', 'old_subject_logo']);
            //update details in DB
            $subjects = Subjects::find($input_data['subject_id']);
            $subjects->subject_name = $input_data['update_subject_name'];

            //if update logo is yes then upload new file and file name into DB
            if ($input_data['update_logo'] == 'yes') {
                if ($request->hasFile('update_subject_logo')) {
                    //unlink old logo file
                    if (file_exists(public_path(SUBJECT_LOGO_UPLOAD . '/' . $input_data['old_subject_logo']))) {
                        unlink(public_path(SUBJECT_LOGO_UPLOAD . '/' . $input_data['old_subject_logo']));
                    }
                    $imageName = $input_data['update_subject_name'] . '_' . time().'.'.$request->update_subject_logo->extension();
                    $file_upplod = $request->update_subject_logo->move(public_path(SUBJECT_LOGO_UPLOAD), $imageName);
                    $subjects->logo_image = $imageName;
                }
            }
            $subjects->save();
            return redirect('subjects')->with([
                'status' => 'success',
                'msg' => 'Subject Updated!'
            ]);
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }

    /**
     * Description: delete subject using subject id
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @request Illuminate\Http\Request
     * @return redirect
     */
    public function postDeleteSubject(Request $request)
    {
        try {
            $active_class_subject = ClassSubjectsMapped::select('id')
                ->where('subject_id', $request->delete_subject_id)
                ->where('status', 1)
                ->get()
                ->toArray();
            if (empty($active_class_subject)) {
                // if subject is not used in any active class then soft delete subjects from subjects_master table
                $classes = Subjects::find($request->delete_subject_id);
                $classes->status = 0;
                $classes->save();
                return redirect('subjects')->with([
                    'status' => 'success',
                    'msg' => 'Subject Deleted!'
                ]);
            } else {
                return redirect('subjects')->with([
                    'status' => 'warning',
                    'msg' => 'Subject Used in active Class! Please unlink subject from class before delete'
                ]);
            }
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }
}
