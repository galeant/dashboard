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
            Village
            <small>Village Create</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Create Village</h2>
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
						        {{ Form::model($data, ['route' => ['village.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @else
						        {{ Form::open(['route'=>'village.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @endif
						    	<div class="form-group m-b-20">
					                <label>District</label>
					                <select name="district_id" class="form-control" id="district_id" required>
		                                @if(!empty($data->district_id))
						                <option value="{{$data->district_id}}">{{$data->district->name}}</option>
						                @else
						                <option value="">--Select District--</option>
						                @endif
		                            </select>
					            </div>
						    	<div class="form-group m-b-20">
					                <label>Name</label>
					                 {{ Form::text('name', null, ['class' => 'form-control','placeholder'=>'Please Enter Full Name','id'=>'name','required'=>'required']) }}
					            </div>
						    	<div class="form-group m-b-20">
					                <label>Postal Code</label>
					                 {{ Form::text('postal_code', null, ['class' => 'form-control','placeholder'=>'Please Enter Postal Code','id'=>'postal_code','required'=>'required']) }}
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
		    $("#district_id").select2({
		            ajax: {
		                url: "/json/district",
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
		            $('#district_id').val(repo.id);
		          }
		          return repo.name || repo.text;
		        }
		});
    </script>
@stop