<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subjects;
use App\ClassSubjectsMapped;

class SubjectController extends Controller
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
     * Description: view all subject
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @return view
     */
    public function viewAllSubject()
    {
        try {
            $subjects = Subjects::where('status', 1)
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            return view('subject.view-subject', ['subjects' => $subjects]);
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }

    /**
     * Description: create new subjects
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @request Illuminate\Http\Request
     * @return redirect
     */
    public function postCreateSubject(Request $request)
    {
        try {
            $input_data = $request->only(['subject_name']);
            //Insert details in DB
            $subjects = new Subjects;
            $subjects->subject_name = $input_data['subject_name'];
            //upload logo file and save filename into DB
            if ($request->hasFile('subject_logo')) {
                $imageName = $input_data['subject_name'] . '_' . time().'.'.$request->subject_logo->extension();
                $file_upplod = $request->subject_logo->move(public_path(SUBJECT_LOGO_UPLOAD), $imageName);
                $subjects->logo_image = $imageName;
            }
            $subjects->save();
            return redirect('subjects')->with([
                'status' => 'success',
                'msg' => 'Subject Created!'
            ]);
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
