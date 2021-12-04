<div class="table-responsive">
    <table class="table" id="show-users-table">
        <thead>
            <tr>
                <th scope="col">Sr. No.</th>
                <th scope="col">User Name</th>
                <th scope="col">Email Address</th>
                <th scope="col">Mobile Number</th>
                <th scope="col">Class</th>
                <th scope="col">Amount</th>
                <th scope="col">Payment Status</th>
                <th scope="col">Payment ID</th>
                <th scope="col">Created At</th>
                <th scope="col">Updated At</th>
            </tr>
        </thead>
        <tbody>
            @empty(!$payment)
                @php $i = 1; @endphp
                @foreach ($payment as $key => $value)
                    <tr>
                        <th scope="row">{{ $i++ }}</th>
                        <td>{{ $value['name'] }}</td>
                        <td>{{ $value['email'] }}</td>
                        <td>{{ $value['mobile_number'] }}</td>
                        <td>{{ $classes[$value['class_id']] }}</td>
                        <td><i class="fas fa-rupee-sign"></i>{{ $value['amount'] }}</td>
                        @if($value['status'] == 0 || $value['status'] == 1)
                            <td><span class="label label-inline label-light-pending font-weight-bold user-show-sub-class text-center">Pending</span></td>
                        @elseif($value['status'] == 2)
                            <td><span class="label label-inline label-light-success font-weight-bold user-show-sub-class text-center">Success</span></td>
                        @elseif($value['status'] == 3)
                            <td><span class="label label-inline label-light-fail font-weight-bold user-show-sub-class text-center">Failed</span></td>
                        @endif
                        <td>{{ $value['razorpay_payment_id'] }}</td>
                        <td>{{ date('F j, Y h:i A', strtotime($value['created_at'])) }}</td>
                        <td>{{ date('F j, Y h:i A', strtotime($value['updated_at'])) }}</td>
                    </tr>
                @endforeach
            @endempty
        </tbody>
    </table>
</div>
<div class="row mt30">
    <div class="col-md-3 text-left">
        {{ $payment->links() }}
    </div>
</div>
