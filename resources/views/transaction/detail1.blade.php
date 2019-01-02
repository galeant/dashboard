@extends ('layouts.app')
@section('head-css')
@parent

<link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />
@stop
@section('main-content')
<div class="block-header">
    <div class="bread-cumb">
        <h2>
            Transaction
            <small>Transaction / {{$data->transaction_number}}</small>
        </h2>
        <div class="tr-dt-footer tr-st-hd">
            <h5>Transaction Status : </h5>
            <span class="badge" style="background-color: {{$data->transaction_status->color}}">{{$data->transaction_status->name}}</span>
        </div>
    </div>
    <div class="row clearfix">
    	<div class="col-md-12 m-t-20">
    		<div class="row">
                <div class="col-md-8 col-md-offset-4 col-sm-10 col-sm-offset-2 list-btn">
                    <div class="btn-inv-right"><a href="/transaction/{{$data->transaction_number}}/print/PDF" target="_blank" >View Invoice</a></div>
                    <div class="btn-inv-right"><a href="">Send Invoice to Email</a></div>
                    <div class="btn-inv-right"><a href="
                        @if(!empty($data->planning) > 0)
                            /transaction/1/print/{{$data->planning->id}}/itinerary/PDF"
                        @else "#" @endif target="_blank" >View Receipt</a></div>
                    <div class="btn-inv-right"><a href="">Send Receipt to Email</a></div>
                </div>
            </div>
    	</div>
    	<div class="col-md-12 m-t-20">
    		<div class="card m-b-15">
                <div class="body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="m-b-15">Transaction State</h4>
                        </div>
                            <div class="col-md-6">
                                <table class="table table-hover">
                                    <tbody>
                                        @foreach($data->transaction_log_status as $log)
                                            <tr>
                                                <td><span class="badge" style="background-color: {{$log->status->color}}">{{$log->status->name}}</span></td>    
                                                <td>{{$log->created_at}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><h5>Booking From : @if($data->from != null) {{$data->from->application}} @else - @endif</h5></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @if($data->status_id != 6)
                            <div class="col-md-6">
                                {{ Form::model($data, ['route' => ['transaction.update', $data->id], 'method'=>'PUT']) }}
                                    <div class="input-group">
                                        <div class="form-line">
                                            {!! Form::select('status',Helpers::statusTransaction(),null,['class' => 'form-control show-tick']) !!}
                                        </div>
                                        <span class="input-group-addon">
                                           <button type="submit" class="btn btn-info waves-effect">Change</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                            @endif
                            <div class="col-md-6">
                                {{ Form::model($data, ['route' => ['transaction.update', $data->id], 'method'=>'PUT']) }}
                                    <div class="input-group">
                                    <label>Payment from midtrans</label>
                                        <div class="form-line">
                                            <input type="text" value="{{str_replace('Rp. ','',Helpers::idr($data->midtrans_payment))}}" class="form-control"/>
                                        </div>
                                        <span class="input-group-addon">
                                            <button id="midtrans" type="submit" class="btn btn-success waves-effect">Save</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <h4>Transaction Detail</h4>
                            <hr>
                            <div class="col-md-4">
                                <h5 class="font-thin">Transaction Number</h5>
                                <p>{{$data->transaction_number}}</p>
                            </div>
                            <div class="col-md-4">
                                <h5 class="font-thin">Transaction Date</h5>
                                <p>
                                @if($data->paid_at != null)
                                    {{date('d M Y H:i',strtotime($data->paid_at))}}
                                @else
                                    -
                                @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <h5 class="font-thin">Last Updated</h5>
                                <p>{{date('d M Y H:i:s',strtotime($data->updated_at))}}</p>
                            </div>
                            <div class="col-md-12">
                                <h5 class="font-thin">Booked By</h5>
                                @if($data->customer)
                                <p>{{$data->customer->firstname.' '.$data->customer->lastname}} ({{$data->customer->email}})</p>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <h4 class="font-fat m-t-20 m-b-15">Contact Person</h4>
                            </div>
                            <div class="col-md-4">
                                <h5 class="font-thin">Fullname:</h5>
                                @if($data->user_contact_id == 0)
                                    {{$data->customer->firstname}} {{$data->customer->lastname}}
                                @else
                                    {{$data->contact->firstname}} {{$data->contact->lastname}}
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h5 class="font-thin">Email Address:</h5>
                                @if($data->user_contact_id == 0)
                                    {{$data->customer->email}}
                                @else
                                    {{$data->contact->email}}
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h5 class="font-thin">Phone Number:</h5>
                                @if($data->user_contact_id == 0)
                                    {{$data->customer->phone}}
                                @else
                                    {{$data->contact->phone}}
                                @endif
                            </div>
                            <div class="col-md-12">
                                <h4 class="font-fat m-t-20 m-b-15">Payment Detail</h4>
                            </div>
                            <div class="col-md-4">
                                <h5 class="font-thin">
                                    Payment Method:
                                </h5>
                                <p>{{str_replace("_"," ",ucfirst($data->payment_method))}}</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="font-thin">
                                    Total Payment
                                </h5>
                                <p>Rp. {{number_format($data->total_price-$data->total_discount)}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @if(count($data->booking_tours) > 0)
            <div class="card">
                <div class="body">
                    <h4>
                        Booking Tours
                    </h4>
                    <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Booking Number</th>
                                <th>Company</th>
                                <th>Product Name</th>
                                <th>Schedule</th>
                                <th>Number Of Person</th>
                                <th>Price Per Person</th>
                                <th>Total Price</th>
                                <th>Booking Status</th>
                            </tr>
                            @foreach($data->booking_tours as $tour)
                                <tr>
                                    <td><a href="{{url('/booking/tour/'.$tour->booking_number)}}"><span class="badge">{{$tour->booking_number}}</span></a></td>
                                    <td><a href="/partner/{{$tour->tours->company->id}}/edit">{{$tour->tours->company->company_name}}</a></td>
                                    <td>{{$tour->tour_name}}</td>
                                    <td>
                                        @if($tour->start_date == $tour->end_date)
											@if($tour->end_hours == "23:59:00")
												{{date('d M Y', strtotime($tour->start_date))}}
											@else
												{{date('d M Y', strtotime($tour->start_date))}}, {{date('h:i', strtotime($tour->start_hours))}} - {{date('h:i', strtotime($tour->end_hours))}}
											@endif
										@else
										{{date('d M Y', strtotime($tour->start_date))}} - {{date('d M Y', strtotime($tour->end_date))}}
										@endif
                                    </td>
                                    <td>{{$tour->number_of_person}} person(s)</td>
                                    <td>
                                        {{Helpers::idr($tour->price_per_person)}}
                                    </td>
                                    <td>
                                        {{Helpers::idr($tour->total_price)}}
                                    </td>
                                    <td>
                                        @if(!empty($tour->booking_status))
                                        <span class="badge" style="background-color:{{$tour->booking_status->color}}">{{$tour->booking_status->name}}</span>
                                        @else
                                        <span class="badge" style="background-color:#066dd6">
                                                Awaiting Payment
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            @endif
            @if(count($data->booking_activities) > 0)
            <div class="card">
                <div class="body">
                    <h4>
                        Booking Activities
                    </h4>
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Day</th>
                                <th>Booking Number</th>
                                <th>Activity Name</th>
                                <th>Cancellaction Type</th>
                                <th>Cancellation Fee</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Schedule</th>
                                <th>Price Per Person</th>
                                <th>Total Discount</th>
                                <th>Total Commission</th>
                                <th>Total Price</th>
                                <th>Net Price</th>
                            </tr>
                            @foreach($data->booking_activities as $tour)
                                <tr>
                                    <td>{{$tour->day_at}}</td>
                                    <td></td>
                                    <td>{{$tour->tour_name}}</td>
                                    <td>
                                        @if($tour->cancellaction_type == 0)
                                            No cancellation
                                        @elseif($tour->cancellaction_type == 1)
                                            Free Cancellation
                                        @elseif($tour->cancellaction_type == 2)
                                            Cancellation policy applies
                                        @endif
                                    </td>
                                    <td>
                                        {{$tour->cancellation_fee}}
                                    </td>
                                    <td>
                                        {{date('Y-m-d',strtotime($tour->start_date))}} {{$tour->start_hours}}
                                    </td>
                                    <td>
                                        {{date('Y-m-d',strtotime($tour->end_date))}} {{$tour->end_hours}}
                                    </td>
                                    <td>
                                        {{date('l, d M Y',strtotime($tour->start_date))}}
                                    </td>
                                    <td>
                                        {{number_format($tour->price_per_person)}}
                                    </td>
                                    <td>
                                        {{number_format($tour->total_discount)}}
                                    </td>
                                    <td>
                                        {{number_format($tour->commission)}}
                                    </td>
                                    <td>
                                        {{number_format($tour->total_price)}}
                                    </td>
                                    <td>
                                        {{number_format($tour->net_price)}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @if(count($data->booking_hotels) > 0)
            <div class="card">
                <div class="body">
                    <h4>
                        Booking Hotels
                    </h4>
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Booking Number</th>
                                <th>Booking From</th>
                                <th>Hotel Name</th>
                                <th>Room Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Number of Room</th>
                                <th>Number of Night</th>
                                <th>Price/Night</th>
                                <th>Total Price</th>
                                <th>Booking Status</th>
                            </tr>
                            @foreach($data->booking_hotels as $hotel)
                                <tr>
                                    <td>
                                        <span class="badge">{{$hotel->booking_number}}</span>
                                        @if(!empty($hotel->order_detail_id))
                                        <span class="badge">{{$hotel->order_detail_id}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ucfirst($hotel->booking_from)}}
                                    </td>
                                    <td>
                                        {{$hotel->hotel_name}}
                                    </td>
                                    <td>
                                        {{$hotel->number_of_rooms}}x {{$hotel->room_name}} ({{$hotel->adult+$hotel->child}})
                                    </td>
                                    <td>
                                        {{date('d M Y', strtotime($hotel->start_date))}}, {{date('h:i', strtotime($hotel->check_in))}}
                                    </td>
                                    <td>
                                        {{date('d M Y', strtotime($hotel->end_date))}}, {{date('h:i', strtotime($hotel->check_out))}}
                                    </td>
                                    <td>
                                        {{$hotel->number_of_rooms}}
                                    </td>
                                    <td>
                                        {{$hotel->night}}
                                    </td>
                                    <td>{{Helpers::idr($hotel->price_per_night)}}</td>
                                    <td>{{Helpers::idr($hotel->total_price)}}</td>
                                    <td>
                                        @if(count($hotel->booking_status))
                                        <span class="badge" style="background-color:{{$hotel->booking_status->color}}">{{$hotel->booking_status->name}}</span>
                                        @else
                                        <span class="badge" style="background-color:#066dd6">Awaiting Payment</span>
                                        @endif
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            
            @if(count($data->booking_rent_cars) > 0)
            <div class="card">
                <div class="body">
                    <h4>
                        Booking Rent Cars
                    </h4>
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Booking Number</th>
                                <th>Company</th>
                                <th>Vehicle Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Number of Day</th>
                                <th>Price per Day</th>
                                <th>Total Price</th>
                                <th>Booking Status</th>
                            </tr>
                            @foreach($data->booking_rent_cars as $rent_car)
                                <tr>
                                    <td>{{$rent_car->booking_number}}</td>
                                    <td>{{$rent_car->agency_name}}</td>
                                    <td>{{$rent_car->vehicle_name}}</td>
                                    <td>{{date('d M Y', strtotime($rent_car->start_date))}}</td>
                                    <td>{{date('d M Y', strtotime($rent_car->end_date))}}</td>
                                    <td>{{$rent_car->number_of_day}} day(s)</td>
                                    <td>
                                        {{Helpers::idr($rent_car->price_per_day)}}
                                    </td>
                                    <td>
                                        {{Helpers::idr($rent_car->total_price)}}
                                    </td>
                                    <td>
                                        @if(count($rent_car->booking_status))
                                        <span class="badge" style="background-color:{{$rent_car->booking_status->color}}">{{$rent_car->booking_status->name}}</span>
                                        @else
                                        <span class="badge" style="background-color:#066dd6">Awaiting Payment</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(count($data->booking_transports) > 0)
            <div class="card">
                <div class="body">
                    <h4>
                        Booking Transport
                    </h4>
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Booking Number</th>
                                <th>Flight Name</th>
                                <th>Departure Time</th>
                                <th>Arrival Time</th>
                                <th>Passanger</th>
                                <th>Price per Person</th>
                                <th>Total Price</th>
                                <th>Booking Status</th>
                            </tr>
                            @foreach($data->booking_transports as $transport)
                                <tr>
                                    <td rowspan="{{count($transport->detail)}}" class="align-middle">{{$transport->booking_number}}</td>
                                    @foreach($transport->detail as $detail)
                                    <td>
                                        {{$detail->provider_trip_name}} ({{$detail->origins->airport_code}} - {{$detail->destinations->airport_code}}) <br><br>
                                    </td>
                                    <td>
                                        {{date('d M Y, h:m', strtotime($detail->departure_time))}} <br>
                                    </td>
                                    <td>
                                        {{date('d M Y, h:m', strtotime($detail->arrival_time))}} <br>
                                    </td>
                                    <td>
                                        <span>Adult: <b>{{$transport->adult}} person(s)</b></span><br>
                                        <span>Child: <b>{{$transport->child}} person(s)</b></span>
                                    </td>
                                    <td>
                                        {{Helpers::idr($transport->price_per_quantity)}}
                                    </td>
                                    <td>
                                        {{Helpers::idr($transport->total_price)}}
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color:{{$data->transaction_status->color}}">{{$data->transaction_status->name}}</span>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h6>Total Transaction</h6>
                </div>
                <div class="panel-body">
                    <div class="row form-group m-t-10">
                        <div class="col-sm-6">
                            <span>Total Price :</span>
                        </div>
                        <div class="col-sm-6">
                            <span>{{Helpers::idr($data->total_price)}}</span>
                        </div>
                    </div>
                    <div class="row form-group m-t-10">
                        <div class="col-sm-6">
                            <span>Discount ({{$data->coupon_code}})</span>
                        </div>
                        <div class="col-sm-6">
                            <span>{{Helpers::idr($data->total_discount)}}</span>
                        </div>
                    </div>
                </div>
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-6">
                            <span>Total Amount to Pay :</span>
                        </div>
                        <div class="col-sm-6" style="margin-left:-1%;">
                            <span>{{Helpers::idr(($data->total_price))}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('head-js')
@parent

<script src="{{asset('plugins/sweetalert/sweetalertnew.min.js')}}"></script>
<script type="text/javascript">
$( window ).on( "load", function() {
	@if( Session::has('error') )
	 swal("Error","{{ Session::get('error') }}" , "error");
	@endif	
});
</script>
@stop