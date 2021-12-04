<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes;
use App\Subjects;
use App\ClassSubjectsMapped;
use Auth;

class ClassController extends Controller
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
     * Description: view all class in view
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function viewAllClass()
    {
        try {
            $classes = Classes::where('status', 1)
                ->with(['classSubject' => function ($query) {
                    $query->where('status', 1);
                }])
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            $subjects = Subjects::select('id', 'subject_name')
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
            $subjects = array_column($subjects, 'subject_name', 'id');
            return view('class.view-class', ['classes' => $classes, 'subjects' => $subjects]);
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }

    /**
     * Description: create new class in DB
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @request Illuminate\Http\Request
     * @return redirect
     */
    public function postCreateClass(Request $request)
    {
        try {
            $input_data = $request->only(['class_name', 'class_subjects', 'class_fee']);
            //Insert details in DB
            $classes = new Classes;
            $classes->class_name = $input_data['class_name'];
            $classes->class_fee = $input_data['class_fee'];
            $classes->created_by = Auth::user()->id;
            $classes->save();
            $class_id = $classes->id;
            if ($class_id) {
                if ($input_data['class_subjects'][0] == 'all') {
                    unset($input_data['class_subjects'][0]);
                }
                foreach ($input_data['class_subjects'] as $key => $value) {
                    $class_subjects_mapped = ClassSubjectsMapped::firstOrCreate(
                        ['class_id' => $class_id, 'subject_id' => $value],
                        ['created_by' => Auth::user()->id]
                    );
                }
            }
            return redirect('classes')->with([
                'status' => 'success',
                'msg' => 'Class Created!'
            ]);
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }

    /**
     * Description: update class using class id
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @request Illuminate\Http\Request
     * @return redirect
     */
    public function postUpdateClass(Request $request)
    {
        try {
            $input_data = $request->only(['class_id', 'update_class_name', 'update_class_fee', 'update_class_subjects']);
            //update details in class_details table
            $classes = Classes::find($input_data['class_id']);
            $classes->class_name = $input_data['update_class_name'];
            $classes->class_fee = $input_data['update_class_fee'];
            $classes->save();

            //update class subjects details
            ClassSubjectsMapped::where('class_id', $input_data['class_id'])
                ->update(['status' => 0, 'updated_by' => Auth::user()->id]);
            if ($input_data['update_class_subjects'][0] == 'all') {
                unset($input_data['update_class_subjects'][0]);
            }
            foreach ($input_data['update_class_subjects'] as $key => $value) {
                $class_subjects_mapped = ClassSubjectsMapped::updateOrCreate(
                    ['class_id' => $input_data['class_id'], 'subject_id' => $value],
                    ['status' => 1, 'created_by' => Auth::user()->id, 'updated_by' => Auth::user()->id]
                );
            }
            return redirect('classes')->with([
                'status' => 'success',
                'msg' => 'Class Details Updated!'
            ]);
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }

    /**
     * Description: delete class using class id
     * Date: Date: 13-July-2020
     * @author Ganesh Suryawanshi
     *
     * @request Illuminate\Http\Request
     * @return redirect
     */
    public function postDeleteClass(Request $request)
    {
        try {
            //soft delete class from class_details table
            $classes = Classes::find($request->delete_class_id);
            $classes->status = 0;
            $classes->save();

            //soft delete all class subject from class_subjects_mapped table
            ClassSubjectsMapped::where('class_id', $request->delete_class_id)
                ->update(['status' => 0, 'updated_by' => Auth::user()->id]);
            return redirect('classes')->with([
                'status' => 'success',
                'msg' => 'Class Deleted!'
            ]);
        } catch (Exception $exception) {
            // return buildResponse(FAIL, EXCEPTION_ERROR, $exception->getMessage());
            return view('404');
        }
    }
}
