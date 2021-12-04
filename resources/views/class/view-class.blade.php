@extends('layouts.app')

@section('content')
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        @include('layouts.header')
        <!-- Begin Page Content -->
        <div class="container-fluid">
            @if (session('status'))
                <div class="alert @if(session('status') == 'success') alert-success @elseif (session('status') == 'warning') alert-warning @else alert-danger @endif" role="alert">
                    {{ session('msg') }}
                </div>
            @endif
            <!-- Page Heading -->
            <div class="breadcream">
                <h3 class="breadcream-text">Class
                    <ul class="breadcream-ul">
                        <li>All Class List</li>
                    </ul>
                </h3>
            </div>
            <!-- DataTales -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-lg-3 mt10">
                            <h6 class="m-0 font-weight-bold text-primary">Active Classes</h6>
                        </div>
                        <div class="col-lg-9 text-right">
                            <button class="btn btn-primary font-weight-bold" id="add-subject-btn" data-toggle="modal" data-target="#add-class-modal">
                                <i class="fa fa-plus mr10" aria-hidden="true"></i>
                                Add Class
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Class Name</th>
                                    <th>Class Subjects</th>
                                    <th>Status</th>
                                    <th>Class Fee</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @empty(!$classes)
                                    @php $i = 1; @endphp
                                    @foreach ($classes as $key => $value)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $value['class_name'] }}</td>
                                            @php $sub = ''; $subject_id = array_column($value['class_subject'], 'subject_id'); @endphp
                                            @foreach ($subject_id as $si_k => $si_v)
                                                @php $sub .= ', ' . $subjects[$si_v]; @endphp
                                            @endforeach
                                            <td>{{ ltrim($sub, ' ,') }}</td>
                                            <td>{{ ($value['status'] == 1) ? 'Active' : 'Deleted' }}</td>
                                            <td><i class="fas fa-rupee-sign"></i>{{ $value['class_fee'] }}</td>
                                            <td>{{ date('F j, Y h:i A', strtotime($value['created_at'])) }}</td>
                                            <td>{{ date('F j, Y h:i A', strtotime($value['updated_at'])) }}</td>
                                            <td>
                                                <button type="button" data-toggle="modal" data-target="#edit-class-modal" class="btn btn-info btn-circle btn-sm edit_class_btn" title="Edit details" data-class_id="{{ $value['id'] }}" data-class_name="{{ $value['class_name'] }}" data-subjects="{{ json_encode($subject_id, JSON_FORCE_OBJECT) }}" data-class_fee="{{ $value['class_fee'] }}"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-danger btn-circle btn-sm delete_class_btn" title="Delete" data-toggle="modal" data-class_id="{{ $value['id'] }}" data-target="#delete-class-modal"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endempty
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    <!-- Create Subject Modal-->
    <div class="modal fade" id="add-class-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Create New Class</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form class="form" name="add-class-form" id="add-class-form" method="POST" action="{{ route('add-class') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="class_name">Class Name*</label>
                                <input type="text" name="class_name" id="class_name" class="form-control form-control-solid" placeholder="Enter class name" required />
                            </div>
                            <div class="form-group">
                                <label for="class_fee">Class Fee*</label>
                                <input type="number" name="class_fee" id="class_fee" min="0" step="1" class="form-control form-control-solid" placeholder="Enter class fee" required />
                            </div>
                            <div class="form-group">
                                <label for="class_subjects">Select Subject*</label>
                                <select role="my-select" name="class_subjects[]" id="class_subjects" class="W55 selectpicker" data-live-search="true" multiple data-selected-text-format="count>3" required>
                                    <option value="all">Select All</option>
                                    @empty(!$subjects)
                                        @foreach ($subjects as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    @endempty
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold add-submit-btn">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Subject Modal-->
    <div class="modal fade" id="edit-class-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Class Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form class="form" name="edit-class-form" id="edit-class-form" method="POST" action="{{ route('update-class') }}">
                    @csrf
                    <input type="hidden" id="class_id" name="class_id" value="">
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Class Name*</label>
                                <input type="text" name="update_class_name" id="update_class_name" class="form-control form-control-solid" placeholder="Enter class name" required />
                            </div>
                            <div class="form-group">
                                <label for="class_fee">Class Fee*</label>
                                <input type="number" name="update_class_fee" id="update_class_fee" min="0" step="1" class="form-control form-control-solid" placeholder="Enter class fee" required />
                            </div>
                            <div class="form-group">
                                <label for="class_subjects">Select Subject*</label>
                                <select role="my-select" name="update_class_subjects[]" id="update_class_subjects" class="W55 selectpicker" data-live-search="true" multiple data-selected-text-format="count>3" required>
                                    <option value="all">Select All</option>
                                    @empty(!$subjects)
                                        @foreach ($subjects as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    @endempty
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold edit-submit-btn">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Class Modal-->
    <div class="modal fade" id="delete-class-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Do you want to delete class?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Confirm" below if you are ready to mark this class as Inactive.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary delete-submit-btn" href="{{ route('delete-class') }}" onclick="event.preventDefault(); document.getElementById('delete-class-form').submit();">
                        Confirm
                    </a>
                    <form id="delete-class-form" action="{{ route('delete-class') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" id="delete_class_id" name="delete_class_id" value="">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>
<!-- End of Content Wrapper -->
@endsection
