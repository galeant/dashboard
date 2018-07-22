@extends ('layouts.app')
@section('head-css')
@parent
<!-- Sweet Alert Css -->
<link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />

<link href="{{ asset('plugins/cropper/cropper.min.css') }}" rel="stylesheet">
<!-- Tel Input -->
<link href="{{ asset('plugins/telformat/css/intlTelInput.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
<style type="text/css">
	.intl-tel-input{display: block;}
</style>
@stop
@section('main-content')
	<div class="block-header">
        <h2>
            Tour Guide
            <small>Tour Guide Create</a></small>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Create Tour Guide</h2>
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
			        	<div class="col-md-12">
					        @if(isset($data))
						        {{ Form::model($data, ['route' => ['tour-guide.update', $data->id], 'method'=>'PUT','class'=>'form-horizontal','enctype' =>'multipart/form-data','id'=>'form_advanced_validation']) }}
						    @else
						        {{ Form::open(['route'=>'tour-guide.store', 'method'=>'POST','id'=>'form_advanced_validation','class'=>'form-horizontal','enctype' =>'multipart/form-data']) }}
						    @endif
						    	<h4 class="dd-title">
						    		Personal Information
						    	</h4>
						    	<div class="row">
							    	<div class="col-md-12">
								    	<div class="col-md-3">
								    		<div class="dd-avatar">
								    			<img src="" class="img-responsive" id="img-avtr">
								    		</div>
								    		<a href="#" id="c_p_picture" class="btn bg-teal btn-block btn-xs waves-effect">Change Profile Picture</a>
							        		<input name="avatar" id="c_p_file" type='file' style="display: none" accept="image/x-png,image/gif,image/jpeg">
								    	</div>
								    	<div class="col-md-9">
								    		<div class="form-group m-b-20">
								                <label>Company</label>
								                <select name="company_id" class="form-control" id="company_id" required>
					                                @if(!empty($data->company_id))
									                <option value="{{$data->company_id}}">{{$data->company->name}}</option>
									                @else
									                <option>--Select Company--</option>
									                @endif
					                            </select>
								            </div>
								            <div class="row clearfix">
									            <div class="col-md-2">
									            	<div class="form-group m-b-20">
										                <label>Salutation(*)</label>
										                 {!! Form::select('salutation',Helpers::salutation(),null,['class' => 'form-control show-tick']) !!}
										            </div>
									            </div>
									            <div class="col-md-10">
										            <div class="form-group m-b-20">
										                <label>Full Name(*)</label>
										                 {{ Form::text('fullname', null, ['class' => 'form-control','placeholder'=>'Please Enter Full Name','id'=>'fullname','required'=>'required']) }}
										            </div>
									            </div>
								            </div>
								            <div class="row clearfix">
									            <div class="col-md-6">
									            	<div class="form-group m-b-20">
										                <label>Age(*)</label>
										                 {{ Form::text('age', null, ['class' => 'form-control','placeholder'=>'Please Enter Age','id'=>'age','required'=>'required']) }}
										            </div>
									            </div>
									            <div class="col-md-6">
									            	<div class="form-group m-b-20">
										                <label>Nationality(*)</label>
											                 <select name="nationality" class="form-control" id="nationality">
							                                @if(!empty($data->nationality))
											                <option value="{{$data->nationality}}">{{$data->nationality}}</option>
											                @else
											                <option>--Select Nationality--</option>
											                @endif
							                            </select>
										            </div>
									            </div>
									        </div>
									        <div class="row clearfix">
									            <div class="col-md-6">
									            	<div class="form-group m-b-20">
										                <label>Email(*)</label>
										                 {{ Form::text('email', null, ['class' => 'form-control','placeholder'=>'Please Enter Full Email','id'=>'email','required'=>'required']) }}
										            </div>
									            </div>
									            <div class="col-md-6">
									            	<div class="form-group m-b-20">
			                                            <label>Phone(*)</label>
			                                            <input type="hidden" class="form-control" id="format">	
			                                            <input type="tel" class="form-control" name="phone" required>	
			                                        </div>
									            </div>
									        </div>
							        		
								    	</div>
							    	</div>
						    	</div>
						    	<h4 class="dd-title">
						    		Service Details
						    	</h4>
						    	<div class="row clearfix">
							    	<div class="col-md-12 m-b-10-i">
		                                <div class="col-md-3 form-control-label">
		                                    <label for="personal_experience">Professional Experience(*)</label>
		                                </div>
		                                <div class="col-md-3">
		                                    <div class="form-group">
		                                        {{ Form::text('fullname', null, ['class' => 'form-control','placeholder'=>'Please Enter Personal Experience','id'=>'personal_experience','required'=>'required']) }}
		                                    </div>
		                                </div>
		                                <div class="col-md-6">
		                                	<p style="margin-top: 5px"><small>year(s) on providing professional services.</small></p>
		                                </div>
	                            	</div>
                            	</div>
                            	<div class="row clearfix">
							    	<div class="col-md-12 m-b-10-i">
		                                <div class="col-md-3 form-control-label">
		                                    <label for="language">Language Spoken(*)</label>
		                                </div>
		                                <div class="col-md-3">
		                                    <div class="form-group">
		                                        <select name="language" class="form-control" id="language" >
					                                @if(!empty($data->language))
									                <option value="{{$data->language}}">{{$data->language}}</option>
									                @else
									                <option>--Select Language--</option>
									                @endif
					                            </select>
		                                    </div>
		                                </div>
		                        	</div>
                            	</div>
                            	<div class="row clearfix">
							    	<div class="col-md-12 m-b-10-i">
		                                <div class="col-md-3 form-control-label label-desc">
		                                    <label for="coverage">Area of Coverage(*)</label>
		                                    <p>
		                                    <small>(Please add minimum 1 city)</small>
		                                    </p>
		                                </div>
		                                <div class="col-md-5">
		                                    <div class="form-group">
		                                        <select id="coverage" name="coverage[]" multiple="multiple" style="width: 100%">
					                                @if(!empty($coverage))
									                <option value="{{$coverage->city_id}}">{{$data->city_name}}</option>
									                @else
									                <option>--Select Coverage--</option>
									                @endif
					                            </select>
		                                    </div>
		                                </div>
		                        	</div>
                            	</div>
                            	<div class="row clearfix">
							    	<div class="col-md-12 m-b-10-i">
		                                <div class="col-md-3 form-control-label">
		                                    <label for="personal_experience">Do you have Guide License?</label>
		                                </div>
		                                <div class="col-md-5">
		                                    <div class="form-group">
		                                        <input name="license" type="radio" id="license_no" checked="">
		                                        <label for="license_no">Yes</label>
		                                        <input name="license" type="radio" id="license_yes">
		                                        <label for="license_yes">No License</label>
		                                    </div>
		                                    <div class="form-group" id="parent_license">
		                                    	{{ Form::text('guide_license', null, ['class' => 'form-control','placeholder'=>'Please Enter License Number','id'=>'guide_license']) }}
		                                    </div>
		                                </div>
		                        	</div>
                            	</div>
                            	<div class="row clearfix">
							    	<div class="col-md-12 m-b-10-i">
		                                <div class="col-md-3 form-control-label">
		                                    <label for="parent_association">Are you registered in Guide Association?</label>
		                                </div>
		                                <div class="col-md-5">
		                                    <div class="form-group">
		                                        <input name="association" type="radio" id="association_no" checked="">
		                                        <label for="association_no">Yes</label>
		                                        <input name="association" type="radio" id="association_yes">
		                                        <label for="association_yes">No License</label>
		                                    </div>
		                                    <div class="form-group" id="parent_association">
		                                    	{{ Form::text('guide_association', null, ['class' => 'form-control','placeholder'=>'Please Enter Registration Number','id'=>'guide_association']) }}
		                                    </div>
		                                </div>
		                        	</div>
                            	</div>
                            	<div class="row clearfix">
							    	<div class="col-md-12 m-b-10-i">
		                                <div class="col-md-3 form-control-label">
		                                    <label for="status">Status<label>
		                                </div>
		                                <div class="col-md-5">
		                                    <div class="form-group">
		                                        <div class="switch" style="margin-top:7px">
				                                    <label>{{Form::checkbox('status','1',true)}}<span class="lever"></span></label>
				                                </div>
		                                    </div>
		                                </div>
		                        	</div>
                            	</div>
                            	<div class="row clearfix">
							    	<div class="col-md-12 m-b-10-i">
		                                <div class="col-md-3 form-control-label">
		                                    <label for="service">What kind of service do you offer?(*)</label>
		                                </div>
		                                <div class="col-md-5 ">
		                                	<div class="row">
			                                	<div class="col-md-12 srvcs-catch">
				                                    <input type="checkbox" id="basic_checkbox_1" class="service-detail" data-id="1" data-name="City Tour">
				                                    <label for="basic_checkbox_1">City Tour</label>
			                                    </div>
		                                    </div>
		                                    <div class="row">
			                                	<div class="col-md-12 srvcs-catch">
				                                    <input type="checkbox" id="basic_checkbox_2" class="service-detail">
				                                    <label for="basic_checkbox_2">Default</label>
			                                    </div>
		                                    </div>
		                                </div>
		                        	</div>
                            	</div>
                            	<div class="row clearfix">
                            		<div class="col-md-12 s-detail">
                            		</div>
                            		<!--
                            		<div class="col-md-12 s-detail">
                        				<h4 class="dd-title">
								    		Service Details
								    	</h4>
								    	<div class="row clearfix">
								    		<div class="col-md-12 m-b-20-i">
				                                <div class="col-md-3 form-control-label label-desc">
				                                    <label for="service_1_">Rate per day:</label>
				                                    <p>
				                                    <small>(Group size 1-9 people)</small>
				                                    </p>
				                                </div>
				                                <div class="col-md-3">
				                                	<div class="input-group input-group-sm">
				                                        <span class="input-group-addon">Rp</span>
				                                        <div class="form-line">
				                                            <input type="text" class="form-control">
				                                        </div>
				                                        <span class="input-group-addon">per day.</span>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-md-12 m-b-20-i">
				                                <div class="col-md-3 form-control-label label-desc">
				                                    <label for="service_1_">Rate per day:</label>
				                                    <p>
				                                    <small>(Group size 10 people and over)</small>
				                                    </p>
				                                </div>
				                                <div class="col-md-3">
				                                	<div class="input-group input-group-sm">
				                                        <span class="input-group-addon">Rp</span>
				                                        <div class="form-line">
				                                            <input type="text" class="form-control">
				                                        </div>
				                                        <span class="input-group-addon">per day.</span>
				                                    </div>
				                                </div>
				                        	</div>
								    	</div>
                            		</div>
                            		-->
                            	</div>
						        <div class="col-md-12"> 
							        <div class="col-md-3 col-md-offset-3">   
							            <div class="form-group m-b-20">
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

<script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script>

<script src="{{asset('js/pages/forms/form-validation.js')}}"></script>
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('plugins/cropper/cropper.min.js') }}"></script>   
<!-- Tel format -->
<script src="{{ asset('plugins/telformat/js/intlTelInput.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
	window.addEventListener('DOMContentLoaded', function () {
	    var image = document.getElementById('crop-image');
	    var cropBoxData;
	    var canvasData;
	    var cropper;

	    $('#defaultModal').on('shown.bs.modal', function () {
	        cropper = new Cropper(image, {
	            autoCropArea: 1,
	            aspectRatio: 3/4,
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
			$('#img-avtr').attr('src',originalData.toDataURL('image/jpeg'));
			$('.btn-img-close').click();
		});
	});

	$('#c_p_picture').click(function(e){
		e.preventDefault();
		$('input[name="avatar"]').click();

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
	$( ".srvcs-catch" ).delegate( ".service-detail", "change", function(e) {
	       $(this).toggleClass("on");
	       if($(this).hasClass('on')){
	       		var html = '<div id="service_dt_'+$(this).attr('data-id')+'">'+
                				'<h4 class="dd-title">'+
						    		$(this).attr('data-name')+
						    	'</h4>'+
						    	'<div class="row clearfix">'+
						    		'<div class="col-md-12 m-b-20-i">'+
		                                '<div class="col-md-3 form-control-label label-desc">'+
		                                    '<label for="service_1_">Rate per day:</label>'+
		                                    '<p><small>(Group size 1-9 people)</small></p>'+
		                                '</div>'+
		                                '<div class="col-md-3">'+
		                                	'<div class="input-group input-group-sm">'+
		                                        '<span class="input-group-addon">Rp</span>'+
		                                        '<div class="form-line">'+
		                                            '<input type="text" class="form-control">'+
		                                        '</div>'+
		                                        '<span class="input-group-addon">per day.</span>'+
		                                    '</div>'+
		                                '</div>'+
		                            '</div>'+
		                            '<div class="col-md-12 m-b-20-i">'+
		                                '<div class="col-md-3 form-control-label label-desc">'+
		                                    '<label for="service_'+$(this).attr('data-id')+'">Rate per day:</label>'+
		                                    '<p><small>(Group size 10 people and over)</small></p>'+
		                                '</div>'+
		                                '<div class="col-md-3">'+
		                                	'<div class="input-group input-group-sm">'+
		                                        '<span class="input-group-addon">Rp</span>'+
		                                        '<div class="form-line">'+
		                                            '<input type="text" class="form-control">'+
		                                        '</div>'+
		                                        '<span class="input-group-addon">per day.</span>'+
		                                    '</div>'+
		                                '</div>'+
		                        	'</div>'+
						    	'</div>'+
                    		'</div>';
	       		$('.s-detail').append(html);
	       }else{	 
	       		$('#service_dt_'+$(this).attr('data-id')).remove();  		
	       }
	});
	$("input[name='phone']").mask('000-0000-00000');
	$("#format").val("+62");
	$("input[name='phone']").val("+62").intlTelInput({
		separateDialCode: true,
	});
	$('#license_no').click(function(e){
		$('#parent_license').slideDown();
		$('#guide_license').val('');
	});
	$('#license_yes').click(function(e){
		$('#parent_license').slideUp();
	});
	$('#association_no').click(function(e){
		$('#parent_association').slideDown();
		$('#guide_association').val('');
	});
	$('#association_yes').click(function(e){
		$('#parent_association').slideUp();
	});
	$(function(){
		
		$("#coverage").select2({
	            ajax: {
	                url: "/json/city",
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
	    $("#company_id").select2({
	            ajax: {
	                url: "/json/company",
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
        $("#nationality").select2({
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
        $("#language").select2({
            ajax: {
                url: "/json/language",
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
});
</script>   
@stop