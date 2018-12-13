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
            Area
            <small>Area Create</a></small>
        </h2>
    </div>

    <div class="row clearfix">
      <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Create Area</h2>
		        </div>
		        <div class="body">
		        	@include('errors.error_notification')
			        <div class="clearfix">
     						<div class="col-lg-12">
                  @if(isset($data))
                      {{ Form::model($data, ['route' => ['area.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
                  @else
                      {{ Form::open(['route'=>'area.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
                  @endif
                      <div class="col-lg-8">
                        <div class="form-group m-b-20">
                            <label>Country*</label>
                            <select name="country_id" class="form-control" id="country_id" required>
                              @if(!empty($data))
                                @if(!empty($data->country_id))
                                <option value="{{$data->country_id}}" selected="selected">{{$data->country->name}}</option>
                                @else
                                <option value="103" selected="selected">Indonesia</option>
                                @endif
                              @endif
                            </select>
                        </div>
      						    	<div class="form-group m-b-20">
      			                <label>Province*</label>
      			                <select name="province_id" class="form-control" id="province_id" required>
                              @if(!empty($data))
                                @if(!empty($data->province_id))
        				                <option value="{{$data->province_id}}">{{$data->province->name}}</option>
        				                @else
        				                <option>--Select Province--</option>
        				                @endif
                              @endif
                            </select>
      			            </div>
                        <div class="form-group m-b-20">
                          <label>
                            Select Related City
                          </label>
                          <select id="city" name="city[]"  class="form-control" multiple="multiple" style="width: 100%">
                            @if(!empty($data))
                              @if(!empty(count($data->city)))
                                @foreach($data->city as $cover)
                                      <option value="{{$cover->id}}" selected="selected">{{$cover->name}}</option>
                                @endforeach
                              @else
                              <option value="">--Select city--</option>
                              @endif
                            @else
                               <option value="">--Select city--</option>
                            @endif
                            
                          </select>
                        </div>
      						    	<div class="form-group m-b-20">
      			                <label>Area Name*</label>
      			                 {{ Form::text('area_name', null, ['class' => 'form-control','placeholder'=>'Please Enter Area Name','id'=>'name','required'=>'required']) }}
      			            </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group m-b-20">
                                <label>Longitude*</label>
                                 {{ Form::text('longitude', null, ['class' => 'form-control','placeholder'=>'Please Enter Longitude','id'=>'longitude','required'=>'required']) }}
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group m-b-20">
                                <label>Latitude*</label>
                                 {{ Form::text('latitude', null, ['class' => 'form-control','placeholder'=>'Please Enter Latitude','id'=>'latitude','required'=>'required']) }}
                            </div>
                          </div>
                        </div>
      						    	<div class="form-group m-b-20">
      			                <label>Radius*</label>
      			                 {{ Form::number('radius', null, ['class' => 'form-control','placeholder'=>'Please Enter Radius','id'=>'radius','required'=>'required']) }}
      			            </div>

      						    	<div class="form-group m-b-20">
      			               <label>Status</label>
      		                 <div class="switch">
                             <label>
                             <input type="checkbox" name="status" @if(!empty($data)){{$data->status === 1 ? 'checked' : ''}} @endif>
                             <span class="lever"></span>
                             </label>
                           </div>
      			            </div>
                        <div class="form-group m-b-20">
  					            	<div class="col-md-3 col-lg-offset-9">
  					            		<button type="submit" class="btn btn-block btn-lg btn-success waves-effect">Save</button>
  									      </div>
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
		                  //
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
            $("#province_id").change(function(){
                $("#city option").remove();
            });
            $("#city").select2({
                 ajax: {
                     url: "/json/city",
                     dataType: 'json',
                     delay: 250,
                     data: function (params) {
                       return {
                         province_id:$("#province_id").val(),
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
            $("#country_id").select2({
    	            ajax: {
    	                url: "/json/country",
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
		});
    </script>
@stop
