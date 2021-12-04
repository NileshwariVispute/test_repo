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
                <h3 class="breadcream-text">Subject
                    <ul class="breadcream-ul">
                        <li>All Subject List</li>
                    </ul>
                </h3>
            </div>
            <!-- DataTales -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-lg-3 mt10">
                            <h6 class="m-0 font-weight-bold text-primary">Active Subjects</h6>
                        </div>
                        <div class="col-lg-9 text-right">
                            <button class="btn btn-primary font-weight-bold" id="add-subject-btn" data-toggle="modal" data-target="#add-subject">
                                <i class="fa fa-plus mr10" aria-hidden="true"></i>
                                Add Subject
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Subject Name</th>
                                    <th>Logo</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @empty(!$subjects)
                                    @php $i = 1; @endphp
                                    @foreach ($subjects as $key => $value)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $value['subject_name'] }}</td>
                                            <td>{{ $value['logo_image'] }}</td>
                                            <td>{{ ($value['status'] == 1) ? 'Active' : 'Deleted' }}</td>
                                            <td>{{ date('F j, Y H:i A', strtotime($value['created_at'])) }}</td>
                                            <td>
                                                <button type="button" data-toggle="modal" data-target="#edit-subject" class="btn btn-info btn-circle btn-sm edit_subject_btn" title="Edit details" data-subject_name="{{ $value['subject_name'] }}" data-logo_image="{{ $value['logo_image'] }}" data-subject_id="{{ $value['id'] }}"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-danger btn-circle btn-sm delete_subject_btn" title="Delete" data-toggle="modal" data-target="#delete-subject-modal" data-subject_id="{{ $value['id'] }}"><i class="fas fa-trash"></i></button>
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
    <div class="modal fade" id="add-subject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Create New Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form class="form" name="add-subject-form" id="add-subject-form" enctype="multipart/form-data" method="POST" action="{{ route('add-subject') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Subject Name*</label>
                                <input type="text" name="subject_name" id="subject_name" class="form-control form-control-solid" placeholder="Enter subject name" required />
                            </div>
                            <div class="form-group" id="add-subject-logo-div">
                                <label>Upload Logo*</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="subject_logo" name="subject_logo" required/>
                                    <label class="custom-file-label" for="subject_logo">Choose file</label>
                                </div>
                                <div class="width60">
                                    <div class="preview-container add">
                                        <div class="collection" id="previews">
                                            <div class="collection-item clearhack valign-wrapper item-template" id="zdrop-template">
                                                <div class="left pv zdrop-info" data-dz-thumbnail>
                                                    <div>
                                                        <span class="show-file-name-campaign add" data-dz-name></span>
                                                        <span class="show-file-size-campaign add" data-dz-size></span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="determinate" style="width: 0;" data-dz-uploadprogress></div>
                                                    </div>
                                                    <div class="secondary-content actions">
                                                        <a data-dz-remove class="btn-floating ph red white-text waves-effect waves-light oad-file remove-upload-file"><i class="fas fa-times" aria-hidden="true"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    <div class="modal fade" id="edit-subject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Subject Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form class="form" name="edit-subject-form" id="edit-subject-form" enctype="multipart/form-data" method="POST" action="{{ route('update-subject') }}">
                    @csrf
                    <input type="hidden" id="subject_id" name="subject_id" value="">
                    <input type="hidden" id="old_subject_logo" name="old_subject_logo" value="">
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Subject Name*</label>
                                <input type="text" name="update_subject_name" id="update_subject_name" class="form-control form-control-solid" placeholder="Enter subject name" required />
                            </div>
                            <div class="form-group">
                                <label>Update Logo*</label>
                                <div class="radio-inline">
                                    <label class="radio">
                                        <input required type="radio" value="yes" name="update_logo"/>
                                        <span></span>
                                        Yes
                                    </label>
                                    <label class="radio">
                                        <input required type="radio" value="no" name="update_logo"/>
                                        <span></span>
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" id="edit-subject-logo-div" style="display: none;">
                                <label>Upload Logo*</label>
                                <div class="custom-file">
                                    <input disabled type="file" class="custom-file-input" id="update_subject_logo" name="update_subject_logo" required/>
                                    <label class="custom-file-label" for="update_subject_logo">Choose file</label>
                                </div>
                                <div class="">
                                    <div class="width60">
                                        <div class="preview-container edit">
                                            <div class="collection" id="previews">
                                                <div class="collection-item clearhack valign-wrapper item-template" id="zdrop-template">
                                                    <div class="left pv zdrop-info" data-dz-thumbnail>
                                                        <div>
                                                            <span class="show-file-name-campaign edit" data-dz-name></span>
                                                            <span class="show-file-size-campaign edit" data-dz-size></span>
                                                        </div>
                                                        <div class="progress">
                                                            <div class="determinate" style="width: 0;" data-dz-uploadprogress></div>
                                                        </div>
                                                        <div class="secondary-content actions">
                                                            <a data-dz-remove class="btn-floating ph red white-text waves-effect waves-light oad-file remove-upload-file"><i class="fas fa-times" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

    <!-- Delete Subject Modal-->
    <div class="modal fade" id="delete-subject-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete this subject?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Confirm" below if you are ready to mark this subject as Inactive.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary delete-submit-btn" href="{{ route('delete-subject') }}" onclick="event.preventDefault(); document.getElementById('delete-subject-form').submit();">
                        Confirm
                    </a>
                    <form id="delete-subject-form" action="{{ route('delete-subject') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" id="delete_subject_id" name="delete_subject_id" value="">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>
<!-- End of Content Wrapper -->
@endsection
