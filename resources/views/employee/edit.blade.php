@extends ('layouts.app')
@section('head-css')
@parent
<!-- Sweet Alert Css -->
<link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />

<link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
<style>
    .row{
        margin-bottom:10px;
    }
    #action{
        margin-top:25px;
    }
</style>
@stop
@section('main-content')
	<div class="block-header">
        <h2>
            Admin
            <small>Autorization / Roles / Edit</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Edit Admin</h2>
		        </div>
		        <div class="body">
		        	@include('errors.error_notification')
			        <div class="row clearfix">
 						<div class="col-lg-8 m-l-30">
                            {{ Form::model($employee, ['route' => ['employee.update', $employee->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Fullname</h5>
                                        <input type="text" class="form-control" name="fullname"  value="{{$employee->fullname}}" required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Email</h5>
                                        <input type="email" class="form-control" name="email"  value="{{$employee->email}}" required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Phone</h5>
                                        <input type="text" class="form-control" name="phone"  value="{{$employee->phone}}" required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Username</h5> 
                                        <input type="text" class="form-control" name="username"  value="{{$employee->username}}"  required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Password</h5>
                                        <input type="password" class="form-control" name="password"  value="{{$employee->email}}"  required/>
                                    </div>
                                </div>
                                
                                <div class="row" id="list_role">
                                    @php
                                        $employee_role = array_pluck($employee->Roles, 'id');
                                    @endphp
                                    @foreach($role as $index=>$ro)
                                        <div class="col-md-3">
                                            <input type="checkbox" id="{{$ro->id}}" class="filled-in chk-col-deep-orange" name="role_id[{{$ro->id}}]" 
                                            @if(in_array($ro->id,$employee_role))
                                                checked
                                            @endif
                                            >
                                            <label for="{{$ro->id}}">{{$ro->description}}</label>
                                        </div>
                                    @endforeach
                                    
                                </div>
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

@stop