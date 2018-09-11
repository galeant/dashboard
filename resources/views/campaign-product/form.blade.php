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
						        {{ Form::model($data, ['route' => ['campaign-product.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','placeholder' => '--Select Product Type--','id'=>'form_advanced_validation']) }}
						    @else
						        {{ Form::open(['route'=>'campaign-product.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @endif
						    	<div class="form-group m-b-20">
									<label>Product Type</label>
									@if(isset($data))
									{{ Form::select('product_type', $product_type, $data->product_type,['class' => 'form-control','id'=>'product_type','required'=>'required']) }}
									@else
									{{ Form::select('product_type', $product_type, null,['class' => 'form-control','id'=>'product_type','required'=>'required']) }}
									@endif
								</div>
								<div class="form-group m-b-20">
									<label>Product Name</label>
									<select name="product_id" class="form-control" id="product_id" required>
										@if(isset($data))
										<option value="{{$data->product_id}}">{{$data->tours->product_name}}</option>
										@else
										<option value="">--Select Product--</option>
										@endif
									</select>
								</div>
								<div class="form-group m-b-20">
									<label>Product Name</label>
									@if(isset($data))
									{{ Form::select('campaign_id', $campaign, $data->campaign_id,['class' => 'form-control','id'=>'campaign_id','required'=>'required']) }}
									@else
									{{ Form::select('campaign_id', $campaign, null,['class' => 'form-control','id'=>'campaign_id','required'=>'required']) }}
									@endif
								</div>
								
					            <div class="form-group m-b-20">
					                <label>Supplier Discount</label>
									{{ Form::number('supplier_discount', null, ['class' => 'form-control','placeholder'=>'Please Enter Supplier Discount','id'=>'suplier_discount', 'step' => '.01', 'min' => '0', 'max' => '100','required'=>'required']) }}
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
	$(document).on('ready', function() {
		$("#product_type").change(function(){
			console.log($(this).val());
			if($(this).val()==1){

			}
			else if($(this).val()==5){
				// $.ajax({
				// 	method: "GET",
				// 	url: "{{ url('/json/tour') }}",
				// }).done(function(response) {
				// 	$.each(response.data, function (index, value) {
				// 		$("#product_id").append(
				// 			"<option value="+value.id+">"+value.name+"</option>"
				// 		);
				// 	});
				// });

				$("#product_id").select2({
					ajax: {
						url: "/json/tour",
						dataType: 'json',
						delay: 250,
						data: function (params) {
						return {
							name: params.term, // search term
							page: params.page,
						};
						},
						processResults: function (data, params) {
						// parse the results into the format expected by Select2
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data, except to indicate that infinite
						// scrolling can be used
						params.page = params.page || 1;
						return {
							results: data.data,
							pagination: {
							more: (params.page * 30) < data.total_count
							}
						};
						},
						cache: true
					},
					escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
					minimumInputLength: 1,
					templateResult: formatRepo, // omitted for brevity, see the source of this page
					templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
				});
				function formatRepo (repo) {
					if (repo.loading) return repo.text;

					var markup = "<div class='select2-result-repository clearfix'>" +

						"<div class='select2-result-repository__meta'>" +
						"<div class='select2-result-repository__title'>" + repo.name + "</div>";

					"</div></div>";

					return markup;
					}
				function formatRepoSelection (repo) {
				return repo.name || repo.text;
				}
			}
		});
		// $("#product_id").select2();

		
    });
</script>
@stop