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
            <span>Booking Tour > {{$data->booking_number}}</span>
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
                    <a location="{{ url('bookings/tour/'.$data->booking_number.'/refund')}}" class="btn btn-waves bg-red" id="refund" >Refund</a>
                    @endif
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="row">
                <div class="col-md-4">
                    <div class="col-md-6">Booking Number</div>
                    <div class="col-md-6">: {{$data->booking_number}}</div>
                </div>
                <div class="col-md-4 col-md-offset-2">
                    <div class="col-md-6">Booked By</div>
                    <div class="col-md-6">: {{$data->transactions->customer->firstname}} {{$data->transactions->customer->lastname}} ({{$data->transactions->customer->email}})</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="col-md-6">Transaction Date</div>
                    <div class="col-md-6">: {{date('d-F-Y', strtotime($data->created_at))}}</div>
                </div>
                <div class="col-md-4 col-md-offset-2">
                    <div class="col-md-6">Booking Status</div>
                    <div class="col-md-6">: 
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
                            <span>Product Category</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$data->tours->product_category}} - {{$data->tours->product_type}}</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Product Name</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$data->tour_name}}</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Schedule</span>
                        </div>
                        <div class="col-md-6">
                            :
                            @if($data->start_date == $data->end_date)
                                @if($data->end_hours == "23:59:00")
                                    {{date('d M Y', strtotime($data->start_date))}}
                                @else
                                    {{date('d M Y', strtotime($data->start_date))}}, {{date('h:i', strtotime($data->start_hours))}} - {{date('h:i', strtotime($data->end_hours))}}
                                @endif
                            @else
                            {{date('d M Y', strtotime($data->start_date))}} - {{date('d M Y', strtotime($data->end_date))}}
                            @endif
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Total Person</span>
                        </div>
                        <div class="col-md-6">
                            : <span><b>{{$data->number_of_person}} person(s)</b></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <span>Item Price</span>
                        </div>
                        <div class="col-md-6">
                            : <span>{{Helpers::idr($data->price_per_person)}}</span>
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
                            : <span>{{Helpers::idr(($data->total_price)-$data->commission)}}</span>
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
    