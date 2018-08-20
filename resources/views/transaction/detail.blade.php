@extends ('layouts.app')
@section('head-css')
@parent

<link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />
@stop
@section('main-content')
<div class="block-header">
    <h2>
        Transaction
        <small>Transaction / {{$data->transaction_number}}</small>
    </h2>
    <div class="row clearfix">
    	<div class="col-md-12 m-t-20">
    		<a href="/transaction/{{$data->transaction_number}}/print/PDF" class="btn btn-info waves-effect pull-left">
                <i class="material-icons">local_printshop</i>
                <span>Invoice</span>
            </a>
    	</div>
    	<div class="col-md-12 m-t-20">
    		<div class="card">
                <div class="body">
                    <div class="row">
                    	<div class="col-md-6">
                    		<h4>Transaction State</h4>
                    		<table class="table table-hover">
                                <tbody>
                                    @foreach($data->transaction_log_status as $log)
                                    	<tr>
                                    		<td><span class="badge" style="background-color: {{$log->status->color}}">{{$log->status->name}}</span></td>
                                    		<td>{{$log->created_at}}</td>
                                    	</tr>
                                    @endforeach
                                </tbody>
                            </table>
                    	</div>
                    	<div class="col-md-6">
                    		{{ Form::model($data, ['route' => ['transaction.update', $data->id], 'method'=>'PUT','class'=>'m-t-20']) }}
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
                    </div>
                </div>
            </div>
    	</div>
    	<div class="col-md-4">
    		<div class="card customer-detail">
                <div class="header">
                    <h2>
                        Customer
                    </h2>
                </div>
                <div class="body">
                    <ul class="p-info">
                    	<li>
                    		<div class="title">Name</div>
                    		<div class="desk">{{$data->customer->salutation}}. {{ucfirst($data->customer->firstname)}} {{$data->customer->lastname}}</div>
                    	</li>
                    	<li>
                    		<div class="title">Email</div>
                    		<div class="desk">{{$data->customer->email}}</div>
                    	</li>
                    	<li>
                    		<div class="title">Phone</div>
                    		<div class="desk">{{$data->customer->phone}}</div>
                    	</li>
                    </ul>
                </div>
            </div>
    	</div>
    	<div class="col-md-8">
    		<div class="card">
                <div class="body">
                	<h4>
                        Details <span class="badge">{{$data->transaction_number}}</span>
                    </h4>
                    <table class="table table-hover">
                        <tbody>
                        	<tr>
                        		<td>Transaction Number</td>
                        		<td><span class="badge">{{$data->transaction_number}}</span></td>
                        	</tr>
                        	<tr>
                        		<td>Use Coupon</td>
                        		<td>
                        			@if(!empty($data->coupon_code))
                        			<span class="badge">{{$data->coupon_code}}</span>
                        			@else
                        			<i class="material-icons" style="color:red">clear</i>
                        			@endif
                        		</td>
                        	</tr>
                        	<tr>
                        		<td>Total Discount</td>
                        		<td>{{number_format($data->total_discount)}}</td>
                        	</tr>
                        	<tr>
                        		<td>Total Price</td>
                        		<td>{{number_format($data->total_price)}}</td>
                        	</tr>
                        	<tr>
                        		<td>Payment Method</td>
                        		<td>{{$data->payment_method}}</td>
                        	</tr>
                        </tbody>
                    </table>
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

                    <table class="table table-hover">
                        <tbody>
                        	<tr>
                        		<th>Day</th>
                        		<th>Booking Number</th>
                        		<th>Tour Name</th>
                        		<th>Cancellaction Type</th>
                        		<th>Cancellation Fee</th>
                        		<th>Start Date</th>
                        		<th>End Date</th>
                        		<th>Price Per Person</th>
                        		<th>Total Discount</th>
                        		<th>Total Commission</th>
                        		<th>Total Price</th>
                        		<th>Net Price</th>
                        	</tr>
                            @foreach($data->booking_tours as $tour)
                            	<tr>
                            		<td>{{$tour->day_at}}</td>
                            		<td><span class="badge">{{$tour->booking_number}}</span></td>
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
                            			{{$tour->schedule->destination_schedule_day}}
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
                        		<th>Start Date</th>
                        		<th>End Date</th>
                        		<th>Room Name</th>
                        		<th>Price/Night</th>
                        		<th>Discount</th>
                        		<th>Total Price</th>
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
                            			{{$hotel->start_date}} {{$hotel->check_in}}
                            		</td>
                            		<td>
                            			{{$hotel->end_date}} {{$hotel->check_out}}
                            		</td>
                            		<td>
                            			{{$hotel->number_of_rooms}}x {{$hotel->room_name}} ({{$hotel->adult+$hotel->child}})
                            		</td>
                            		<td>{{number_format($hotel->price_per_night)}}</td>
                            		<td>{{number_format($hotel->total_discount)}}</td>
                            		<td>{{number_format($hotel->total_price)}}</td>
                            	</tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
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