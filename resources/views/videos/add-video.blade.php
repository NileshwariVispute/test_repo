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
                <h3 class="breadcream-text">Videos
                    <ul class="breadcream-ul">
                        <li>Add New Video</li>
                    </ul>
                </h3>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Content Column -->
                <div class="col-lg-12 mb-4">
                    <!-- Project Card Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">New Video</h6>
                        </div>
                        <div class="card-body">
                            <form class="form" name="add-video-form" id="add-video-form" enctype="multipart/form-data" method="POST" action="{{ route('add-video') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label text-lg-right">Title*</label>
                                    <div class="col-lg-5">
                                        <input type="text" name="video_title" id="video_title" class="form-control form-control-solid" placeholder="Enter video title" required />
                                        <span class="form-text text-muted video_title">Please enter video title</span>
                                        <span class="invalid-feedback" id="video_title_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label text-lg-right">Description*</label>
                                    <div class="col-lg-5">
                                        <textarea name="description" id="description" class="form-control form-control-solid" placeholder="Enter video description" required></textarea>
                                        <span class="form-text text-muted description">Please enter video description</span>
                                        <span class="invalid-feedback" id="description_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label text-lg-right">Video Type*</label>
                                    <div class="col-lg-5">
                                        <select name="video_type" id="video_type" class="W55 selectpicker">
                                            <option value="" selected disabled hidden>Choose here</option>
                                            <option value="1" >Free</option>
                                            <option value="2">Premium</option>
                                        </select>
                                        <span class="form-text text-muted video_type">Please select video type</span>
                                        <span class="invalid-feedback" id="video_type_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label text-lg-right">Select Class*</label>
                                    <div class="col-lg-5">
                                        <select name="class_id" id="class_id" class="W55 selectpicker" data-live-search="true" data-selected-text-format="count>3" required>
                                            <option value="" selected disabled hidden>Choose here</option>
                                            @empty(!$classes)
                                                @foreach ($classes as $cl_k => $cl_v)
                                                    <option value="{{ $cl_v['id'] }}">{{ $cl_v['class_name'] }}</option>
                                                @endforeach
                                            @endempty
                                        </select>
                                        <span class="form-text text-muted class_id">Please select class</span>
                                        <span class="invalid-feedback" id="class_id_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label text-lg-right">Select Subject*</label>
                                    <div class="col-lg-5">
                                        <select disabled name="subject_id" id="subject_id" class="W55 selectpicker" data-live-search="true" data-selected-text-format="count>3" required>
                                            <option value="" selected disabled hidden>Choose here</option>
                                        </select>
                                        <span class="form-text text-muted subject_id">Please select subject</span>
                                        <span class="invalid-feedback" id="subject_id_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label text-lg-right">Upload Thumbnail*</label>
                                    <div class="col-lg-5">
                                        <div class="width60">
                                            <div class="upload-btn-wrapper">
                                                <button class="uploadfilebtn">Attach Files</button>
                                                <input id="thumbnail" name="thumbnail" type="file" required="required">
                                                <span class="form-text text-muted thumbnail">Max file size is 10MB and max number of files is 1.</span>
                                                <span class="invalid-feedback" id="thumbnail_error"></span>
                                            </div>
                                        </div>
                                        <div class="width60">
                                            <div class="preview-container thumbnail">
                                                <div class="collection" id="previews">
                                                    <div class="collection-item clearhack valign-wrapper item-template" id="zdrop-template">
                                                        <div class="left pv zdrop-info" data-dz-thumbnail>
                                                            <div>
                                                                <span class="show-file-name-campaign thumbnail" data-dz-name></span>
                                                                <span class="show-file-size-campaign thumbnail" data-dz-size></span>
                                                            </div>
                                                            <div class="progress">
                                                                <div class="determinate" style="width: 0;" data-dz-uploadprogress></div>
                                                            </div>
                                                            <div class="secondary-content actions">
                                                                <a data-dz-remove class="btn-floating ph red white-text waves-effect waves-light oad-file remove-thumbnail-file"><i class="fas fa-times" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label text-lg-right">Upload Quiz File</label>
                                    <div class="col-lg-5">
                                        <div class="width60">
                                            <div class="upload-btn-wrapper">
                                                <button class="uploadfilebtn">Attach Files</button>
                                                <input id="quiz_file" name="quiz_file" type="file">
                                                <span class="form-text text-muted quiz_file">Max file size is 10MB and max number of files is 1.</span>
                                                <span class="invalid-feedback" id="quiz_file_error"></span>
                                            </div>
                                        </div>
                                        <div class="width60">
                                            <div class="preview-container quiz_file">
                                                <div class="collection" id="previews">
                                                    <div class="collection-item clearhack valign-wrapper item-template" id="zdrop-template">
                                                        <div class="left pv zdrop-info" data-dz-thumbnail>
                                                            <div>
                                                                <span class="show-file-name-campaign quiz_file" data-dz-name></span>
                                                                <span class="show-file-size-campaign quiz_file" data-dz-size></span>
                                                            </div>
                                                            <div class="progress">
                                                                <div class="determinate" style="width: 0;" data-dz-uploadprogress></div>
                                                            </div>
                                                            <div class="secondary-content actions">
                                                                <a data-dz-remove class="btn-floating ph red white-text waves-effect waves-light oad-file remove-quiz-file"><i class="fas fa-times" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label text-lg-right">Select Video File*</label>
                                    <div class="col-lg-5">
                                        <div class="width60">
                                            <div class="upload-btn-wrapper">
                                                <button class="uploadfilebtn">Attach Files</button>
                                                <input id="video_file" name="video_file" type="file" required="required">
                                                <span class="form-text text-muted video_file">Max file size is 2GB and max number of files is 1.</span>
                                                <span class="invalid-feedback" id="video_file_error"></span>
                                            </div>
                                        </div>
                                        <div class="width60">
                                            <div class="preview-container video_file">
                                                <div class="collection" id="previews">
                                                    <div class="collection-item clearhack valign-wrapper item-template" id="zdrop-template">
                                                        <div class="left pv zdrop-info" data-dz-thumbnail>
                                                            <div>
                                                                <span class="show-file-name-campaign video_file" data-dz-name></span>
                                                                <span class="show-file-size-campaign video_file" data-dz-size></span>
                                                            </div>
                                                            <div class="progress">
                                                                <div class="determinate" style="width: 0;" data-dz-uploadprogress></div>
                                                            </div>
                                                            <div class="secondary-content actions">
                                                                <a data-dz-remove class="btn-floating ph red white-text waves-effect waves-light oad-file remove-video-file"><i class="fas fa-times" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt30 text-center">
                                    <button type="submit" class="btn btn-primary font-weight-bold text-lg-center">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->
    @include('layouts.footer')
</div>
<!-- End of Content Wrapper -->
@endsection
