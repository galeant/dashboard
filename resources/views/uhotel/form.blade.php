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
            uHotel
            <small>Assign city & province uHotel</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Assign city & province uHotel</h2>
		            <ul class="header-dropdown m-r--5">
		                <li class="dropdown">
		                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
		                        <i class="material-icons">more_vert</i>
		                    </a>
		                </li>
		            </ul>
		        </div>
		        <div class="body">
		        	@include('errors.error_notification')
			        <div class="row clearfix">
 						<div class="col-lg-8 m-l-30">
					        
						        {{ Form::model($data, ['route' => ['uhotel.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    	<div class="form-group m-b-20">
					                <label>Name</label>
					                 {{ Form::text('desc[name]', null, ['class' => 'form-control','placeholder'=>'Please Enter Full Name','id'=>'name','readonly'=> 'readonly']) }}
					            </div>
								<div class="form-group m-b-20">
					                <label>Province</label>
					                <select name="province_id" class="form-control" id="province_id" uniq="{{$data->province_id}}" required>
										<option value="">--Select Province--</option>
		                            </select>
					            </div>
								<div class="form-group m-b-20">
					                <label>City</label>
					                <select name="city_id" class="form-control" id="city_id" uniq="{{$data->city_id}}" required>
						                <option value="">--Select City--</option>
		                            </select>
					            </div>
						    	<div class="form-group m-b-20">
					                <label>Longitude</label>
					                 {{ Form::number('longitude', null, ['class' => 'form-control','placeholder'=>'Please Enter Longitude','id'=>'longitude','readonly'=> 'readonly']) }}
					            </div>
						    	<div class="form-group m-b-20">
					                <label>Latitude</label>
					                 {{ Form::number('latitude', null, ['class' => 'form-control','placeholder'=>'Please Enter Latitude','id'=>'latitude','readonly'=> 'readonly']) }}
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
<script>
	$(document).ready(function(){
		var idProvince = $('#province_id').attr('uniq');
		var idCity = $('#city_id').attr('uniq');
		ajaxProvince(idProvince);
		ajaxCity(idProvince,idCity);
		$('#province_id').change(function(){
			ajaxProvince($(this).val());
			ajaxCity($(this).val(),null);
		})
		function ajaxProvince(idProvince){
			var config = {
				method: "GET",
				url: "{{ url('json/province') }}"
			}
			$.ajax(config).done(function(response) {
				$("#city_id").empty().append('<option value="">--Select City--</option>');
				$.each(response.data, function(k, v) {
					if(v.id == idProvince){
						$("#province_id").append('<option value="'+v.id+'" selected>'+v.name+'</option>');
					}else{
						$("#province_id").append('<option value="'+v.id+'">'+v.name+'</option>');
					}
				});
			});
		}

		function ajaxCity(idProvince,idCity){
			if(idProvince != ''){
					var config = {
					method: "GET",
					url: "{{ url('json/city') }}",
					data: {province_id:idProvince}
				}
				$.ajax(config).done(function(response) {
					
					$.each(response.data, function(k, v) {
						if(v.id == idCity){
							$("#city_id").append('<option value="'+v.id+'" selected>'+v.name+'</option>');
						}else{
							$("#city_id").append('<option value="'+v.id+'">'+v.name+'</option>');
						}
					});
				});
			}
		}
		
	});
</script>
    <!-- <script type="text/javascript">
		$(function(){
		    $("#province_id").select2({
		            ajax: {
		                url: "/json/province",
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
		          if(typeof repo.name !== "undefined"){
		            $('#province_id').val(repo.id);
		          }
		          return repo.name || repo.text;
		        }
		});
    </script> -->
@stop