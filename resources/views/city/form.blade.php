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
            City
            <small>City Create</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Create City</h2>
		            <ul class="header-dropdown m-r--5">
		                <li class="dropdown">
		                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
		                        <i class="material-icons">more_vert</i>
		                    </a>
		                    <ul class="dropdown-menu pull-right">
		                        <li><a href="javascript:void(0);">Action</a></li>
		                        <li><a href="javascript:void(0);">Another action</a></li>
		                        <li><a href="javascript:void(0);">Something else here</a></li>
		                    </ul>
		                </li>
		            </ul>
		        </div>
		        <div class="body">
		        	@include('errors.error_notification')
			        <div class="row clearfix">
 						<div class="col-lg-8 m-l-30">
					        @if(isset($data))
						        {{ Form::model($data, ['route' => ['city.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @else
						        {{ Form::open(['route'=>'city.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @endif
						    	<div class="form-group m-b-20">
					                <label>Province</label>
					                <select name="province_id" class="form-control" id="province_id" required>
		                                @if(!empty($data->province_id))
						                <option value="{{$data->province_id}}">{{$data->province->name}}</option>
						                @else
						                <option>--Select Province--</option>
						                @endif
		                            </select>
					            </div>
					            <div class="form-group m-b-20">
					                <label>Type</label>
					                 {!! Form::select('type',Helpers::typeCity(),null,['class' => 'form-control show-tick']) !!}
					            </div>
						    	<div class="form-group m-b-20">
					                <label>Name</label>
					                 {{ Form::text('name', null, ['class' => 'form-control','placeholder'=>'Please Enter Full Name','id'=>'name','required'=>'required']) }}
					            </div>
						    	<div class="form-group m-b-20">
					                <label>Longitude</label>
					                 {{ Form::number('longitude', null, ['class' => 'form-control','placeholder'=>'Please Enter Longitude','id'=>'longitude','required'=>'required']) }}
					            </div>
						    	<div class="form-group m-b-20">
					                <label>Latitude</label>
					                 {{ Form::number('latitude', null, ['class' => 'form-control','placeholder'=>'Please Enter Latitude','id'=>'latitude','required'=>'required']) }}
					            </div>
						    	<div class="form-group m-b-20">
					                <label>Radius</label>
					                 {{ Form::number('radius', null, ['class' => 'form-control','placeholder'=>'Please Enter Radius','id'=>'radius','required'=>'required']) }}
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
    <script type="text/javascript">
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
    </script>
@stop