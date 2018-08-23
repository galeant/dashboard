@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@stop
@section('main-content')
<div class="card">
        <div class="body">
            <div class="col-md-10 font-20">
                <span>All Schedule > {{$data->transaction_id}}</span>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="header">
                <h4>Booking Detail</h4>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="col-md-6">Booking Number</div>
                        <div class="col-md-6">: {{$data->booking_number}}</div>
                    </div>
                    <div class="col-md-4 col-md-offset-2">
                        <div class="col-md-6">Booked By</div>
                        <div class="col-md-6">: {{$data->booking_number}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="col-md-6">Transaction Date</div>
                        <div class="col-md-6">: {{date('d-F-Y', strtotime($data->created_at))}}</div>
                    </div>
                    <div class="col-md-4 col-md-offset-2">
                        <div class="col-md-6">Booked Status</div>
                        <div class="col-md-6">: {{$data->transactions->transaction_status->name}}</div>
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
            <div class="body">
                <div class="row" style="margin-top: 20px">
                    <div class="col-md-2">
                        Full Name
                    </div>
                    <div class="col-md-2">
                        : <span>{{$data->product_code}}</span>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px">
                    <div class="col-md-2">
                        Phone Number
                    </div>
                    <div class="col-md-2">
                            : <span>{{$data->product_code}}</span>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px; margin-bottom: 20px">
                    <div class="col-md-2">
                        Email Address
                    </div>
                    <div class="col-md-2">
                            : <span>{{$data->product_code}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('head-js')
@parent
@stop
    