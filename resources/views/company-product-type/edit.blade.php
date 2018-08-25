@extends ('layouts.app')
@section('head-css')
@parent
<!-- Sweet Alert Css -->
<link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />

<link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
@stop
@section('main-content')
	<div class="block-header">
        <h2>
            Partner Product Type
            <small>Partner Product Type Create</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>
						<a href="{{route('activity-tag.index')}}">Partner Product Type</a>
						>  Create Partner Product Type
					</h2>
		        </div>
		        <div class="body">
		        	@include('errors.error_notification')
			        <div class="row clearfix">
 						<div class="col-lg-8 m-l-30">
						    {{ Form::open(['route'=>'partner-product-type.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
					            <input type="hidden" name="company_id" value="{{$company->id}}">
					            <div class="form-group m-b-20">
									<label>Product Type</label>
					                <select name="product_type_id" id="product_type_id" class="form-control">
										@foreach($product_type as $product_type)
										<option value="{{$product_type->id}}">{{$product_type->name}}</option>
										@endforeach
									</select>
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
	<div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2> 
						List Partner Product Type
					</h2>
		        </div>
		        <div class="body">
					@foreach ($company->product_types as $key => $product_types)
		        	<div class="row m-t-10 m-b-10">
						<div class="col-md-1">
							{{$key+1}}
						</div>
						<div class="col-md-3">
							{{$product_types->name}}
						</div>
						<div class="col-md-3">
							{{$product_types->code}}
						</div>
						<div class="col-md-2">
							{{$product_types->pivot->created_at}}
						</div>
						<div class="col-md-3">
							<a href="{{url('partner-product-type/delete/'.$company->id.'/'.$product_types->id)}}" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/activity-tag/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
								<i class="glyphicon glyphicon-trash"></i>
							</a>
						</div>
					</div>	
					@endforeach
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

<script src="{{asset('js/pages/forms/form-validation.js')}}"></script>
   
<script>
	$("select[name='company_id']").select2();
	$("select[name='product_type_id']").select2();
</script>
@stop