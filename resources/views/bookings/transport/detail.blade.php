@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
@stop
@section('main-content')
<div class="card">
    <div class="body">
        <div class="col-md-10 font-20">
            <span>Booking Transport > {{$data->booking_number}}</span>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="header">
            <h4>Booking Detail</h4>
            <ul class="header-dropdown m-r--5">
                <li>
                    @if($data->status == 3)
                    <a location="{{ url('bookings/transport/'.$data->booking_number.'/refund')}}" class="btn btn-waves bg-red" id="refund" >Refund</a>
                    @endif
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-4">Booking Number</div>
                    <div class="col-md-8">: {{$data->booking_number}}</div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4">Booked By</div>
                    <div class="col-md-8">: {{$data->transactions->customer->firstname}} {{$data->transactions->customer->lastname}} ({{$data->transactions->customer->email}})</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-4">Transaction Date</div>
                    <div class="col-md-8">: {{date('d-F-Y', strtotime($data->created_at))}}</div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4">Booking Status</div>
                    <div class="col-md-8">: 
                        <span class="badge" style="background-color:{{$data->transactions->transaction_status->color}}">{{$data->transactions->transaction_status->name}}</span>
                    </div>
                </div>
            </div>
            <br>
            <div class="panel panel-default" style="margin-left: 30px;margin-right: 30px;">
                <div class="panel-heading">
                    <h6>Booked Item</h6>
                </div>
                @foreach($data->detail as $detail)
                <div class="panel-body">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Flight Name</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$detail->provider_name}}</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Origin</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$detail->origins->airport_name}} ({{$detail->origins->airport_code}})</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Destination</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$detail->destinations->airport_name}} ({{$detail->destinations->airport_code}})</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Departure Time</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$detail->departure_time}}</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Arrival Time</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$detail->arrival_time}}</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Total Person</span>
                        </div>
                        <div class="col-md-6">
                            : <span>Adult: <b>{{$data->adult}} person(s)</b></span><br>
                            : <span>Child: <b>{{$data->child}} person(s)</b></span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="panel panel-default" style="margin-left: 30px;margin-right: 30px;">
                <div class="panel-heading">
                    <h6>Pricing</h6>
                </div>
                <div class="panel-body">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Item Price</span>
                        </div>
                        <div class="col-md-6">
                            : <span>{{Helpers::idr($data->price_per_quantity)}}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Total Price</span>
                        </div>
                        <div class="col-md-6">
                            : <span>{{Helpers::idr($data->total_price)}}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Total Discount</span>
                        </div>
                        <div class="col-md-6">
                            : <span>{{Helpers::idr($data->total_discount)}}</span>
                        </div>
                    </div>
                </div>
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-6">
                            <span>Total Price :</span>
                        </div>
                        <div class="col-sm-6" style="margin-left:-1%;">
                            <span>: {{Helpers::idr(($data->total_price - $data->total_discount))}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="header">
            <h4>Customer Contact Detail</h4>
        </div>
        <div class="body m-l-20">
            <div class="row" style="margin-top: 20px">
                <div class="col-md-2">
                    Full Name
                </div>
                <div class="col-md-6">
                    : 
                    {{-- <span>{{$data->transactions->contact->salutation}}. {{$data->transactions->contact->firstname}} {{$data->transactions->contact->lastname}} --}}
                    @if($data->user_contact_id == 0)
                        {{$data->transactions->customer->salutation}}. {{$data->transactions->customer->firstname}} {{$data->transactions->customer->lastname}}
                    @else
                        {{$data->transactions->contact->salutation}}. {{$data->transactions->contact->firstname}} {{$data->transactions->contact->lastname}}
                    @endif
                    </span>
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="col-md-2">
                    Phone Number
                </div>
                <div class="col-md-6">
                    : 
                    <span>
                    @if($data->user_contact_id == 0)
                        {{$data->transactions->customer->phone}}
                    @else
                        {{$data->transactions->contact->phone}}
                    @endif
                    </span>
                </div>
            </div>
            <div class="row" style="margin-top: 20px; margin-bottom: 20px">
                <div class="col-md-2">
                    Email Address
                </div>
                <div class="col-md-6">
                    : 
                    <span>
                    @if($data->user_contact_id == 0)
                        {{$data->transactions->customer->email}}
                    @else
                        {{$data->transactions->contact->email}}
                    @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('head-js')
@parent
<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $("#refund").click(function(){
            var href = $(this).attr('location');
            swal({
                title: "Are you sure?",
                text: "You will not be able to change this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function(isConfirm){
                window.location = href;
            });
        });
    });
</script>
@stop
    