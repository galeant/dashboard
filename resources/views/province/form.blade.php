@extends ('layouts.app')
@section('head-css')
@parent
<!-- Sweet Alert Css -->
<link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />

<link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />

<link href="{{ asset('plugins/cropper/cropper.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/bootstrap-file-input/css/fileinput.css') }}" rel="stylesheet">
@stop
@section('main-content')
	<div class="block-header">
        <h2>
            Province
            <small>Province Create</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Create Province</h2>
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
						        {{ Form::model($data, ['route' => ['province.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @else
						        {{ Form::open(['route'=>'province.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @endif
						    	<div class="form-group m-b-20">
					                <label>Country</label>
					                <select name="country_id" class="form-control" id="country_id" required>
		                                @if(!empty($data->country_id))
						                <option value="{{$data->country_id}}">{{$data->country->name}}</option>
						                @else
						                <option value="">--Select Country--</option>
						                @endif
		                            </select>
					            </div>
					            <div class="form-group m-b-20 cover-image">
                                    <label>Cover Image*</label>
                                    <div class="dd-main-image">
                                        <img style="width: 100%" src="{{(!empty($data)? cdn($data->cover_image.'/large/'.$data->cover_filename) : 'http://via.placeholder.com/500x500' )}}" id="cover-img">
                                    </div>
                                    {{ Form::hidden('image_resize', null, ['class' => 'form-control','required'=>'required']) }}
                                    <a href="#" id="c_p_picture" class="btn bg-teal btn-block btn-xs waves-effect">Upload Cover Image</a>
                                    <input name="cover_img" id="c_p_file" type='file' style="display: none" accept="image/x-png,image/gif,image/jpeg">
                                </div>
						    	<div class="form-group m-b-20">
					                <label>Name*</label>
					                 {{ Form::text('name', null, ['class' => 'form-control','placeholder'=>'Please Enter Full Name','id'=>'name','required'=>'required']) }}
					            </div>
								<div class="form-group m-b-20">
									<label>Airport</label>
									<select id="airport_id" name="airport_id[]"  class="form-control" multiple="multiple" style="width: 100%">
									@if(!empty($data))
										@if(count($data->airport) != 0)
											@foreach($data->airport as $air)
											<option value="{{$air->id}}" selected="selected">{{$air->airport_name}}</option>
											@endforeach
										@else
										<option value="">--Select Airport--</option>
										@endif
									@else
										<option value="">--Select Airport--</option>
									@endif
									@if(!empty(old('airport')) && empty($data))
										@foreach(old('airport') as $air)
											<option value="{{$air->id}}" selected="selected">{{$air->name}}</option>
										@endforeach
									@endif
									</select>
									<label id="error-coverage"></label>
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
<!-- #END# Basic Example | Horizontal Layout -->
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Cropper Image</h4>
            </div>
            <div class="modal-body">
                <div class="img-container">
                  <img id="crop-image" src="" alt="Picture" class="img-responsive">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect btn-img-save">SAVE CHANGES</button>
                <button type="button" class="btn btn-link waves-effect btn-img-close" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('head-js')
@parent
    <!-- Sweet Alert Plugin Js -->
<script src="{{asset('plugins/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script src="{{asset('js/pages/forms/form-validation.js')}}"></script>
<script src="{{ asset('plugins/cropper/cropper.min.js') }}"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			$("#airport_id").select2({
	            ajax: {
	                url: "{{ url('master/airport/json') }}",
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
	        //   if(typeof repo.name !== "undefined"){
	        //     $('#country_id').val(repo.id);
	        //   }
	          return repo.name || repo.text;
	        }

			
	        window.addEventListener('DOMContentLoaded', function () {
                var image = document.getElementById('crop-image');
                var cropBoxData;
                var canvasData;
                var cropper;

                $('#defaultModal').on('shown.bs.modal', function () {
                    cropper = new Cropper(image, {
                        autoCropArea: 1,
                        aspectRatio: 1,
                        strict: false,
                        guides: false,
                        highlight: false,
                        dragCrop: false,
                        zoomable: false,
                        scalable: false,
                        rotatable: false,
                        cropBoxMovable: true,
                        cropBoxResizable: false,
                        responsive: true,
                        viewMode: 1,
                        ready: function () {
                            // Strict mode: set crop box data first
                            cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                        }
                    });
                    
                }).on('hidden.bs.modal', function () {
                    cropBoxData = cropper.getCropBoxData();
                    canvasData = cropper.getCanvasData();
                    originalData = cropper.getCroppedCanvas();
                    cropper.destroy();
                });
                $('.btn-img-save').click(function(){
                    data = originalData = cropper.getCroppedCanvas();
                    $('input[name="image_resize"]').val(originalData.toDataURL('image/jpeg'));
                    $('#cover-img').attr('src',originalData.toDataURL('image/jpeg'));
                    $('.btn-img-close').click();
                });
            });

            $('#c_p_picture').click(function(e){
                e.preventDefault();
                $('input[name="cover_img"]').click();

            });
            function readURL(input) {
            if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('img#crop-image').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(document).delegate('#c_p_file', 'change', function(e){
                e.preventDefault();
                $('#defaultModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                readURL(this);
            });

		});
    </script>
@stop
