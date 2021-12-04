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
                    Payment
                    <ul class="breadcream-ul">
                        <li>Payment Details</li>
                    </ul>
                </h3>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <label class="">Filter Data</label>
                </div>
                <div class="col-md-3">
                    <div class="inputbox mb20 create-segmentbox-searchbox">
                        <select name="payment_status_option" id="payment_status_option" class="W100-custom selectpicker">
                            <option selected="true" value="all">All</option>
                            <option value="1">Pending</option>
                            <option value="2">Success</option>
                            <option value="3">Failed</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="view-payments-table">
                @include('payment.payment-content')
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
