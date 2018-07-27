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
            Supplier
            <small>Supplier Create</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Create Supplier</h2>
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
						        {{ Form::model($data, ['route' => ['supplier.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @else
						        {{ Form::open(['route'=>'supplier.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @endif
								<div class="form-group m-b-20">
									<label>Salutation</label>
									{{ Form::select('salutation', array('Mr' => 'Mr', 'Mrs' => 'Mrs', 'Ms' => 'Ms'), null ,['class' => 'form-control', 'id'=>'salution','required'=>'required'])}}
								</div>
								<div class="form-group m-b-20">
									<label>Email</label>
									{{ Form::email('email', null, ['class' => 'form-control','placeholder'=>'example@email.com','id'=>'email','required'=>'required']) }}
								</div>
						    	<div class="form-group m-b-20">
					                <label>User Name</label>
					                 {{ Form::text('username', null, ['class' => 'form-control','placeholder'=>'Please User Name','id'=>'username']) }}
					            </div>
					            <div class="form-group m-b-20">
					                <label>Full Name</label>
					                 {{ Form::text('fullname', null, ['class' => 'form-control','placeholder'=>'Please Enter Full Name','id'=>'fullname','required'=>'required']) }}
					            </div>
						    	<div class="form-group m-b-20">
					                <label>Password</label>
					                 {{ Form::password('password', ['class' => 'form-control','placeholder'=>'Enter Password','id'=>'password']) }}
					            </div>
					            <div class="form-group m-b-20">
					                <label>Phone Number</label>
					                 {{ Form::text('phone', null, ['class' => 'form-control','placeholder'=>'Please Enter Phone Number','id'=>'phone','required'=>'required']) }}
					            </div>
					            <div class="form-group m-b-20">
									<label>Company</label>
									{{ Form::select('company_id', $company, null ,['class' => 'form-control', 'id'=>'company_id','required'=>'required'])}}
								</div>
								<div class="form-group m-b-20">
									<label>Role</label>
									{{ Form::select('role_id', $supplier_role, null ,['class' => 'form-control', 'id'=>'role_id','required'=>'required'])}}
								</div>
					            <div class="form-group m-b-20">
					            	<div class="col-md-4 col-lg-offset-5">
										@if(isset($data))
										<a href="{{ url('/master/supplier/password_reset/'.$data->id) }}">
											<button type="button" class="btn btn-block btn-lg btn-primary waves-effect">Send Email Password</button>
										</a>											
										@endif
									</div>
					            	<div class="col-md-3">
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

<script src="{{asset('js/pages/forms/form-validation.js')}}"></script>
   
@stop