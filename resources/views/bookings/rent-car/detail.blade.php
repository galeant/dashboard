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
            <span>Booking Rent Car > {{$data->booking_number}}</span>
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
                    <a location="{{ url('bookings/rent-car/'.$data->booking_number.'/refund')}}" class="btn btn-waves bg-red" id="refund" >Refund</a>
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
                <div class="col-md-5 col-md-offset-1">
                    <div class="col-md-4">Booked By</div>
                    <div class="col-md-8">: {{$data->transactions->customer->firstname}} {{$data->transactions->customer->lastname}} ({{$data->transactions->customer->email}})</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-4">Transaction Date</div>
                    <div class="col-md-8">: {{date('d-F-Y', strtotime($data->created_at))}}</div>
                </div>
                <div class="col-md-5 col-md-offset-1">
                    <div class="col-md-4">Booking Status</div>
                    <div class="col-md-8">: 
                        @if($data->status == 1)
                        <span class="badge" style="background-color:#666699">Awaiting Payment</span>    
                        @elseif($data->status == 2)
                        <span class="badge" style="background-color:#006600">Payment Accepted</span>    
                        @elseif($data->status == 3)
                        <span class="badge" style="background-color:#cc0000">Cancelled</span>    
                        @elseif($data->status == 4)
                        <span class="badge" style="background-color:#3399ff">On Prosses Settlement,</span>    
                        @elseif($data->status == 5)
                        <span class="badge" style="background-color:#3333ff">Settled</span>    
                        @else
                        <span class="badge" style="background-color:#b30086">Refund</span>    
                        @endif
                    </div>
                </div>
            </div>
            <br>
            <div class="panel panel-default" style="margin-left: 30px;margin-right: 30px;">
                <div class="panel-heading">
                    <h6>Booked Item</h6>
                </div>
                <div class="panel-body">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Company Name</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$data->vehicle_company_name}}</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Vehicle Brand</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$data->vehicle_brand}}</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Vehicle Name</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$data->vehicle_name}}</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Vehicle Type</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$data->vehicle_type}}</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Number of day</span>
                        </div>
                        <div class="col-md-6">
                            : {{$data->number_of_day}} day(s)
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Start Rent</span>
                        </div>
                        <div class="col-md-6">
                            : {{date('d M Y', strtotime($data->start_date))}}
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>End Rent</span>
                        </div>
                        <div class="col-md-6">
                            : {{date('d M Y', strtotime($data->end_date))}}
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
                </div>
                <hr>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <span>Pigijo Commission</span>
                        </div>
                        <div class="col-md-6">
                            : <span>{{Helpers::idr($data->commission)}}</span>
                        </div>
                    </div>
                </div>
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6">
                            <span>Your Net Sales</span>
                        </div>
                        <div class="col-md-6">
                            : <span>{{Helpers::idr(($data->net_price))}}</span>
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
                    : <span>{{$data->transactions->customer->salutation}}. {{$data->transactions->customer->firstname}} {{$data->transactions->customer->lastname}}</span>
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="col-md-2">
                    Phone Number
                </div>
                <div class="col-md-6">
                        : <span>{{$data->transactions->customer->phone}}</span>
                </div>
            </div>
            <div class="row" style="margin-top: 20px; margin-bottom: 20px">
                <div class="col-md-2">
                    Email Address
                </div>
                <div class="col-md-6">
                        : <span>{{$data->transactions->customer->email}}</span>
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
    