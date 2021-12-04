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
                        <li>View Video</li>
                    </ul>
                </h3>
            </div>
            <div class="mb30">
                <div class="mb15">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="inputbox mb20 create-segmentbox-searchbox">
                                <label class="">Search Video</label>
                                <span class="input-search-icon"><input type="text" placeholder="Search" name="search_videos" id="search_videos" class="inputboxselect"></span>
                            </div>
                        </div>
                        <div class="col-md-7 text-right">
                            <a class="btn btn-primary font-weight-bold" id="add-video-btn" href={{ route('add-video') }}>
                                <i class="fa fa-plus mr10" aria-hidden="true"></i>
                                Add Video
                            </a>
                        </div>
                    </div>
                    <div class="view-video-table">
                        @include('videos.view-video-content')
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
