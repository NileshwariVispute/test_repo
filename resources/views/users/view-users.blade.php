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
                <h3 class="breadcream-text">
                    User
                    <ul class="breadcream-ul">
                        <li>Users List</li>
                    </ul>
                </h3>
            </div>
            <div class="table-responsive">
                <table class="table" id="show-users-table">
                    <thead>
                        <tr>
                            <th scope="col">Sr. No.</th>
                            <th scope="col">User Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Mobile Number</th>
                            <th scope="col">Subscribed For</th>
                            <th scope="col">User Class</th>
                            <th scope="col">Account Open Date</th>
                            <th scope="col">Last Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        @empty(!$users_data)
                            @php $i = 1; @endphp
                            @foreach ($users_data as $key => $value)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{ $value['email'] }}</td>
                                    <td>{{ $value['mobile_number'] }}</td>
                                    <td>
                                        @if(! empty($value['class_fee_paid_user']))
                                            @foreach ($value['class_fee_paid_user'] as $cf_k => $cf_v)
                                                <span class="mb5 label label-inline label-light-success font-weight-bold user-show-sub-class text-center">{{ $classes[$cf_v['class_id']] }}</span>
                                            @endforeach
                                        @else
                                            <span class="label label-inline label-light-primary font-weight-bold">NA</span>
                                        @endif
                                    </td>
                                    <td>{{ $classes[$value['class_id']] }}</td>
                                    <td>{{ date('F j, Y h:i A', strtotime($value['account_open_date'])) }}</td>
                                    <td>{{ date('F j, Y h:i A', strtotime($value['users_login_history']['last_login'])) }}</td>
                                </tr>
                            @endforeach
                        @endempty
                    </tbody>
                </table>
            </div>
            <div class="row mt30">
                <div class="col-md-3 text-left">
                    {{ $users->links() }}
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
    </div>
    @include('layouts.footer')
    <script src="{{ asset('asset/js/datatables/dataTables.buttons.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/datatables/jszip.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/datatables/buttons.html5.min.js') }}" defer></script>
    <!-- End of Content Wrapper -->
</div>
@endsection
