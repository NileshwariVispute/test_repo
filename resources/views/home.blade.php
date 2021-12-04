@extends('layouts.app')

@section('content')
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        @include('layouts.header')
        <!-- Begin Page Content -->
        <div class="container-fluid">
            @if (session('status') && session('msg'))
                <div class="alert @if(session('status') == 'success') alert-success @elseif (session('status') == 'warning') alert-warning @else alert-danger @endif" role="alert">
                    {{ session('msg') }}
                </div>
            @elseif (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <!-- Page Heading -->
            <div class="breadcream">
                <h3 class="breadcream-text">Dashboard
                    <ul class="breadcream-ul">
                        <li>Home</li>
                    </ul>
                </h3>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_users }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Today's User login Count</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $login_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Videos</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $video_count }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-video fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Views</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_vv_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-eye fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Content Column -->
                <div class="col-lg-6 mb-4">
                    <!-- Project Card Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Most Watch Video</h6>
                        </div>
                        <div class="card-body">
                            @empty(!$video_view_count)
                                @php $i = 1; @endphp
                                @foreach ($video_view_count as $key => $value)
                                    @php
                                        if($i > 5) {
                                            break;
                                        }
                                        $percentage = ($value['video_count'] / $total_vv_count) * 100;
                                    @endphp
                                    <h4 class="small font-weight-bold">{{ $videos[$value['video_id']] }} ({{ $value['video_count'] }} Views)<span class="float-right">{{ $percentage }}%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                @endforeach
                            @endempty
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <!-- Illustrations -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Class Wise Users</h6>
                        </div>
                        <div class="card-body">
                            @empty(!$users)
                                @php $i = 1; @endphp
                                @foreach ($users as $key => $value)
                                    @php
                                        if($i > 5) {
                                            break;
                                        }
                                        $percentage = ($value['user_count'] / $total_users) * 100;
                                    @endphp
                                    <h4 class="small font-weight-bold">{{ $value['user_class_name']['class_name'] }} ({{ $value['user_count'] }} Users)<span class="float-right">{{ $percentage }}%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                @endforeach
                            @endempty
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
