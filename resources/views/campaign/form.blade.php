@extends ('layouts.app')
@section('head-css')
@parent
<!-- Sweet Alert Css -->
<link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />

<link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/boostrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" />
@stop
@section('main-content')
	<div class="block-header">
        <h2>
            Campaign
            <small>Campaign Create</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Create Campaign</h2>
		        </div>
		        <div class="body">
		        	@include('errors.error_notification')
			        <div class="row clearfix">
 						<div class="col-lg-8 m-l-30">
					        @if(isset($data))
						        {{ Form::model($data, ['route' => ['campaign-list.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @else
						        {{ Form::open(['route'=>'campaign-list.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @endif
						    	<div class="form-group m-b-20">
									<label>Name</label>
									{{ Form::text('name', null, ['class' => 'form-control','placeholder'=>'Please Enter Campaign Name','id'=>'name','required'=>'required']) }}
					            </div>
					            <div class="form-group m-b-20">
					                <label>Internal Discount</label>
									{{ Form::number('internal_discount', null, ['class' => 'form-control','placeholder'=>'Please Enter Internal Discount','id'=>'internal_discount', 'step' => '.01', 'min' => '0', 'max' => '100','required'=>'required']) }}
					            </div>
					            <div class="form-group m-b-20">
					                <label>Supplier Discount</label>
									{{ Form::number('supplier_discount', null, ['class' => 'form-control','placeholder'=>'Please Enter Supplier Discount','id'=>'suplier_discount', 'step' => '.01', 'min' => '0', 'max' => '100','required'=>'required']) }}
								</div>
					            <div class="form-group m-b-20">
									<label>Booking Date</label>
									@if(isset($data))
									{{ Form::text('booking_date', date('d F Y', strtotime($data->booking_start_date)).' - '.date('d F Y', strtotime($data->booking_end_date)),['class' => 'form-control','id'=>'booking_date','required'=>'required']) }}
									@else
									{{ Form::text('booking_date', null,['class' => 'form-control','id'=>'booking_date','required'=>'required']) }}
									@endif
								</div>
					            <div class="form-group m-b-20">
					                <label>Schedule Date</label>
									
									@if(isset($data))
									{{ Form::text('schedule_date', date('d F Y', strtotime($data->schedule_start_date)).' - '.date('d F Y', strtotime($data->schedule_end_date)),['class' => 'form-control','id'=>'schedule_date','required'=>'required']) }}
									@else
									{{ Form::text('schedule_date', null,['class' => 'form-control','id'=>'schedule_date','required'=>'required']) }}
									@endif
								</div>
					            <div class="form-group m-b-20">
					            	<div class="col-md-3 col-lg-offset-9">
					            		<button type="submit" class="btn btn-block btn-lg btn-success waves-effect">Save</button>
									</div>
					            </div>
				            </form>
			            </div>
			        </div>
		        </div>
		    </div>
    	</div>
    </div>
    <!-- #END# Advanced Validation -->
	
@stop
@section('head-js')
@parent
    <!-- Sweet Alert Plugin Js -->
<script src="{{asset('plugins/sweetalert/sweetalert.min.js')}}"></script>

<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>

<script src="{{asset('js/pages/forms/form-validation.js')}}"></script>

<script src="{{asset('plugins/momentjs/moment.js')}}"></script>
<!-- Bootstrap date range picker -->
<script src="{{asset('plugins/boostrap-daterangepicker/daterangepicker.js')}}"></script>
<script>
	$("#booking_date").daterangepicker({
		opens: "center",
		// minDate: moment(), 
		locale: {
			format: 'DD MMMM YYYY',
		},
	});
	$("#schedule_date").daterangepicker({
		opens: "left",
		// minDate: moment(), 
		locale: {
			format: 'DD MMMM YYYY',
		},
	});
	$(document).on('ready', function() {
		
    });
</script>
@stop