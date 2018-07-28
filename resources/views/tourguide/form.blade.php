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
						        {{ Form::model($data, ['route' => ['tour-guide.update', $data->id], 'method'=>'PUT','class'=>'form-horizontal','enctype' =>'multipart/form-data','id'=>'tour_guide']) }}
						    @else
						        {{ Form::open(['route'=>'tour-guide.store', 'method'=>'POST','id'=>'tour_guide','class'=>'form-horizontal','enctype' =>'multipart/form-data']) }}
						    @endif
						    	<h4 class="dd-title">
						    		Personal Information
						    	</h4>
						    	<div class="row">
							    	<div class="col-md-12">
								    	<div class="col-md-3">
								    		<div class="dd-avatar">
								    			<img src="{{!empty($data->avatar) ? cdn($data->avatar) : ''}}" class="img-responsive" id="img-avtr">
								    		</div>
								    		<input name="image_resize" type="text" value="" hidden>
								    		<a href="#" id="c_p_picture" class="btn bg-teal btn-block btn-xs waves-effect">Change Profile Picture</a>
							        		<input name="avatar" id="c_p_file" type='file' style="display: none" accept="image/x-png,image/gif,image/jpeg">
								    	</div>
								    	<div class="col-md-9">
								    		<div class="form-group m-b-20">
								                <label>Company</label>
								                <select name="company_id" class="form-control" id="company_id" required>
					                                @if(!empty($data->company_id))
									                <option value="{{$data->company_id}}">{{$data->company->company_name}}</option>
									                @else
									                <option value="">--Select Company--</option>
									                @endif
					                            </select>
					                            <label class="error error-company_id"></label>
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
											            <select name="nationality" class="form-control" id="nationality" required>
							                                @if(!empty($data->nationality))
											                <option value="{{$data->country_id}}" selected="selected">{{$data->nationality}}</option>
											                @else
											                <option value="">--Select Nationality--</option>
											                @endif
							                            </select>
							                            <label class="error error-nationality"></label>
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
			                                            <input type="hidden" class="form-control" id="format" name="format">	
			                                            <input type="tel" id="phone" class="form-control" name="phone"  data-old="@if(!empty(old('phone'))){{old('format').'-'.old('phone')}} @elseif(!empty($data)){{$data->phone}}@endif" required>
			                                            <label id="error-phone"></label>
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
		                                        {{ Form::text('experience_year', null, ['class' => 'form-control','placeholder'=>'Please Enter Personal Experience','id'=>'personal_experience','required'=>'required']) }}
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
		                                        <select name="language" class="form-control" id="language" required>
		                                        
		                                        	<option value="">--Select Language--</option>
					                            @foreach($languages as $language)
									                <option @if(!empty($data->language)) @if($language->name === $data->language) selected @endif @elseif(old('language') === $language->name) selected @endif>{{$language->name}}</option>
									            @endforeach
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
		                                    <small>(Please add minimum 1 province)</small>
		                                    </p>
		                                </div>
		                                
		                                <div class="col-md-5">
		                                    <div class="form-group">
		                                        <select id="coverage" name="coverage[]"  class="form-control" multiple="multiple" style="width: 100%" required>
		                                        @if(!empty($data))
					                                @if(!empty(count($data->coverage)))
						                                @foreach($data->coverage as $cover)

										                <option value="{{$cover->id}}" selected="selected">{{$cover->name}}</option>
										                @endforeach
									                @else
									                <option value="">--Select Coverage--</option>
									                @endif
									            @else
									            	 <option value="">--Select Coverage--</option>
									            @endif
									            @if(!empty(old('coverage')) && empty($data))
									            	@foreach(old('coverage') as $cov)
									            		<option value="{{$cov->id}}" selected="selected">{{$cov->name}}</option>
									            	@endforeach
									            @endif
					                            </select>
					                            <label id="error-coverage"></label>
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
		                                        <input name="license" type="radio" class="license" value="yes" id="license_yes"  
		                                        @if(!empty($data))
				                                	@if(!empty($data->guide_license))
				                                		checked	
				                                	@endif
				                                @else
				                                	@if(old('license') == 'yes')
				                                		checked
				                                	@endif
				                                @endif
				                                @if(empty(old('license')) && empty($data))
				                                		checked
				                                @endif>
		                                        <label for="license_yes">Yes</label>
		                                        <input name="license" type="radio" class="license" id="license_no" value="no" 
		                                        @if(!empty($data))
				                                	@if(empty($data->guide_license))
				                                		checked	
				                                	@endif
				                                @else
				                                	@if(old('license') == 'no')
				                                		checked
				                                	@endif
				                                @endif>
		                                        <label for="license_no">No License</label>
		                                    </div>
		                                    <div class="form-group" id="parent_license" >
		                                    	{{ Form::text('guide_license', null, ['class' => 'form-control','placeholder'=>'Please Enter License Number','id'=>'guide_license','required' => 'required']) }}
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
		                                        <input name="association" type="radio" id="association_yes" value="yes" class="association" 
		                                        @if(!empty($data))
				                                	@if(!empty($data->guide_association))
				                                		checked	
				                                	@endif
				                                @else
				                                	@if(old('association') == 'yes')
				                                		checked
				                                	@endif
				                                @endif
				                                @if(empty(old('association')) && empty($data))
				                                		checked
				                                @endif>
		                                        <label for="association_yes">Yes</label>
		                                        <input name="association" type="radio" id="association_no" value="no" class="association"
		                                        @if(!empty($data))
				                                	@if(empty($data->guide_association))
				                                		checked	
				                                	@endif
				                                @else
				                                	@if(old('association') == 'no')
				                                		checked
				                                	@endif
				                                @endif>
		                                        <label for="association_no">No License</label>
		                                    </div>
		                                    <div class="form-group" id="parent_association">
		                                    	{{ Form::text('guide_association', null, ['class' => 'form-control','placeholder'=>'Please Enter Registration Number','id'=>'guide_association','required' => 'required']) }}
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
				                                    <label>{{Form::checkbox('status',(!empty($data) ? $data->status : null))}}<span class="lever"></span></label>
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
		                                	<label class="error-services"></label>
		                                	@foreach($services as $service)
			                                	<div class="row">
				                                	<div class="col-md-12 srvcs-catch">
					                                    <input type="checkbox" id="basic_checkbox_{{$service->id}}" class="service-detail" data-id="{{$service->id}}" data-name="{{$service->name}}" data-value1="" data-value2="" name="services[]" value="{{$service->id}}">
					                                    <label for="basic_checkbox_{{$service->id}}" id="name_service_{{$service->id}}">{{$service->name}}</label>
				                                    </div>
			                                    </div>
		                                    @endforeach
		                                </div>
		                        	</div>
                            	</div>
                            	<div class="row clearfix">
                            		<div class="col-md-12 s-detail">
                            		@if(!empty($data->price))
								    	@foreach($data->price as $price)
								    	<div id="service_dt_{{$price->tour_guide_service_id}}" data-id ="{{$price->tour_guide_service_id}}" class="data-price">
									    	<h4 class="dd-title">
									    		{{$price->service_name}}
									    	</h4>
									    	<div class="row clearfix">
									    		<div class="col-md-12 m-b-20-i">
					                                <div class="col-md-3 form-control-label label-desc">
					                                    <label for="service_1_{{$price->tour_guide_service_id}}">Rate per day(*):</label>
					                                    <p>
					                                    <small>(Group size 1-9 people)</small>
					                                    </p>
					                                </div>
					                                <div class="col-md-3">
					                                	<div class="input-group input-group-sm">
					                                        <span class="input-group-addon">Rp</span>
					                                        <div class="form-line">
					                                            <input type="text" name="rate_per_day_{{$price->tour_guide_service_id}}" value="{{number_format((int)$price->rate_per_day)}}" class="form-control money-format" required ">
					                                        </div>
					                                        <span class="input-group-addon">per day.</span>
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-md-12 m-b-20-i">
					                                <div class="col-md-3 form-control-label label-desc">
					                                    <label for="service_2_{{$price->tour_guide_service_id}}">Rate per day(*):</label>
					                                    <p>
					                                    <small>(Group size 10 people and over)</small>
					                                    </p>
					                                </div>
					                                <div class="col-md-3">
					                                	<div class="input-group input-group-sm">
					                                        <span class="input-group-addon">Rp</span>
					                                        <div class="form-line">
					                                            <input type="text" name="rate_per_day2_{{$price->tour_guide_service_id}}" class="form-control money-format" value="{{number_format((int)$price->rate_per_day2)}}" required>
					                                        </div>
					                                        <span class="input-group-addon">per day.</span>
					                                     
					                                    </div>

					                                </div>
					                        	</div>
									    	</div>
									    </div>
								    	@endforeach
								    @endif
								    @if(!empty(old('services')))
								    	@foreach(old('services') as $id)
								    		<div id="service_dt_{{$id}}" data-id ="{{$id}}" class="data-price">
										    	<h4 class="dd-title">
										    		Service_1
										    	</h4>
										    	<div class="row clearfix">
										    		<div class="col-md-12 m-b-20-i">
						                                <div class="col-md-3 form-control-label label-desc">
						                                    <label for="service_1_{{$id}}">Rate per day(*):</label>
						                                    <p>
						                                    <small>(Group size 1-9 people)</small>
						                                    </p>
						                                </div>
						                                <div class="col-md-3">
						                                	<div class="input-group input-group-sm">
						                                        <span class="input-group-addon">Rp</span>
						                                        <div class="form-line">
						                                            <input type="text" name="rate_per_day_{{$id}}" value="{{old('rate_per_day_'.$id)}}" class="form-control money-format" required ">
						                                        </div>
						                                        <span class="input-group-addon">per day.</span>
						                                    </div>
						                                </div>
						                            </div>
						                            <div class="col-md-12 m-b-20-i">
						                                <div class="col-md-3 form-control-label label-desc">
						                                    <label for="service_2_{{$id}}">Rate per day(*):</label>
						                                    <p>
						                                    <small>(Group size 10 people and over)</small>
						                                    </p>
						                                </div>
						                                <div class="col-md-3">
						                                	<div class="input-group input-group-sm">
						                                        <span class="input-group-addon">Rp</span>
						                                        <div class="form-line">
						                                            <input type="text" name="rate_per_day2_{{$id}}" class="form-control money-format" value="{{old('rate_per_day2_'.$id)}}" required>
						                                        </div>
						                                        <span class="input-group-addon">per day.</span>
						                                     
						                                    </div>

						                                </div>
						                        	</div>
										    	</div>
										    </div>
								    	@endforeach
								    @endif
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
$( window ).on( "load", function() {
	@if(!empty(old('company_id')))
        $.ajax({
            type: "GET",
            data: {"id": {{old('company_id')}} },
            url: '/json/company',
            success: function(result) {
                if(result.code == 200){
                    $("#company_id").select2("trigger", "select", {
                        data: { id: result.data[0].id,name: result.data[0].name }
                    });
                }
            }
        });
    @endif
    @if(!empty(old('nationality')))
        $.ajax({
            type: "GET",
            data: {"id": {{old('nationality')}} },
            url: '/json/country',
            success: function(result) {
                if(result.code == 200){
                    $("#nationality").select2("trigger", "select", {
                        data: { id: result.data[0].id,name: result.data[0].name }
                    });
                }
            }
        });
    @endif
	if ($('#association_no').is(':checked')) {
		$('#parent_association').slideUp();
	}

	if ($('#license_no').is(':checked')) {
		$('#parent_license').slideUp();
	}
	$(".data-price").each(function(index) {
    	$('#basic_checkbox_'+$(this).attr('data-id')).attr('checked', true);
    	$( "#basic_checkbox_"+$(this).attr('data-id') ).addClass( "on" );
    	$(this).children('.dd-title').text($('#name_service_'+$(this).attr('data-id')).text());
    });
    var dbphone = $('input[name="phone"]').attr('data-old');
    if(dbphone != ""){
        var dbformat = dbphone.split("-");
        var pic_phone = "";
        $(dbformat).each(function(index,value) {
            if(index == 1){
                pic_phone = value;
            }
            else if(index > 1){
                pic_phone = pic_phone+'-'+value;
            }

        });
        $("input[name='format']").val(dbformat[0]);
        $("input[name='phone']").val(pic_phone).intlTelInput({
            separateDialCode: true,
        });
    }
    
});
$(document).ready(function () {
	$( "#tour_guide" ).validate({
      rules: {
      	email:{
      		email:true
      	},
        age: {
          number: true,
          maxlength:100
        },
        rate_per_day2_: {
          number: true
        },
        rate_per_day: {
          number: true
        },
        experience_year:{
        	number:true
        },
        nationality:{
        	required:true
        },
        'services[]':{
        	required:true
        }
      },
      messages: {
            'services[]': {
                required: "You must check at least 1 box",
                maxlength: "Check no more than {0} boxes"
            }
        },
        errorPlacement: function(error, element) {
        	
        	if(element.attr('name') == 'phone'){
        		error.insertAfter('.intl-tel-input');
        	}
        	else if(element.attr('name') == 'nationality'){
        		error.insertAfter('.error-nationality');
        	}
        	else if(element.attr('name') == 'company_id'){
        		error.insertAfter('.error-company_id');
        	}
        	else if(element.attr('name') == 'nationality'){
        		error.insertAfter('.error-company_id');
        	}
        	else if(element.attr('name') == 'coverage[]'){
        		error.insertAfter('#error-coverage');
        	}
        	else if(element.attr('name') == 'services[]'){
        		error.insertAfter('.error-services');
        	}else{
		  	error.insertAfter(element);
		  	}
		}
    });


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
			$('input[name="image_resize"]').val(originalData.toDataURL('image/jpeg'));
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
	       		if($('#service_dt_'+$(this).attr('data-id')).length < 1){
	       		var html = '<div id="service_dt_'+$(this).attr('data-id')+'" class="data-price">'+
                				'<h4 class="dd-title">'+
						    		$(this).attr('data-name')+
						    	'</h4>'+
						    	'<div class="row clearfix">'+
						    		'<div class="col-md-12 m-b-20-i">'+
		                                '<div class="col-md-3 form-control-label label-desc">'+
		                                    '<label for="service_1_">Rate per day(*):</label>'+
		                                    '<p><small>(Group size 1-9 people)</small></p>'+
		                                '</div>'+
		                                '<div class="col-md-3">'+
		                                	'<div class="input-group input-group-sm">'+
		                                        '<span class="input-group-addon">Rp</span>'+
		                                        '<div class="form-line">'+
		                                            '<input type="text" class="form-control money-format" name="rate_per_day_'+$(this).attr('data-id')+'" required>'+
		                                        '</div>'+
		                                        '<span class="input-group-addon">per day.</span>'+
		                                    '</div>'+
		                                '</div>'+
		                            '</div>'+
		                            '<div class="col-md-12 m-b-20-i">'+
		                                '<div class="col-md-3 form-control-label label-desc">'+
		                                    '<label for="service_'+$(this).attr('data-id')+'">Rate per day(*):</label>'+
		                                    '<p><small>(Group size 10 people and over)</small></p>'+
		                                '</div>'+
		                                '<div class="col-md-3">'+
		                                	'<div class="input-group input-group-sm">'+
		                                        '<span class="input-group-addon">Rp</span>'+
		                                        '<div class="form-line">'+
		                                            '<input type="text" class="form-control money-format" name="rate_per_day2_'+$(this).attr('data-id')+'" required>'+
		                                        '</div>'+
		                                        '<span class="input-group-addon">per day.</span>'+
		                                    '</div>'+
		                                '</div>'+
		                        	'</div>'+
						    	'</div>'+
                    		'</div>';
	       		$('.s-detail').append(html);
	       		}
	       }else{	 
	       		$('#service_dt_'+$(this).attr('data-id')).remove();  		
	       }
	});

	$("input[name='phone']").mask('000-0000-00000');
	$("input[name='experience_year']").mask('00');
	$("input[name='age']").mask('00');
	$("#format").val("+62");
	$("input[name='phone']").val("+62").intlTelInput({
		separateDialCode: true,
	});
	$('#license_yes').click(function(e){
		$('input[name="guide_license"]').attr('required','required');
		$('#parent_license').slideDown();
		$('#guide_license').val('');
	});
	$('#license_no').click(function(e){

		$('input[name="guide_license"]').removeAttr('required');
		$('#parent_license').slideUp();
	});
	$('#association_yes').click(function(e){
		$('input[name="guide_association"]').attr('required','required');
		$('#parent_association').slideDown();
		$('#guide_association').val('');
	});
	$('#association_no').click(function(e){
		$('input[name="guide_association"]').removeAttr('required');
		$('#parent_association').slideUp();
	});
	$('input[name="status"]').change(function(){
		if($(this).is(":checked")){
			$(this).val(1);
		} else{
			$(this).val(0);
		}
	});
	$('.money-format').change(function(){
		$(this).mask("#,##0", {reverse: true});
	});
	$(function(){
		@if(!empty($data->phone))
	    var dbphone = "{{$data->phone}}";
	    var dbformat = dbphone.split("-");
	    $("input[name='format']").val(dbformat[0]);
	    $("input[name='phone']").val(dbformat[1]+'-'+dbformat[2]+'-'+dbformat[3]).intlTelInput({
	        separateDialCode: true,
	    });
	    @endif
		
		$("#coverage").select2({
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