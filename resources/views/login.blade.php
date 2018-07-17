@extends ('layouts.app',['class_body' => 'login-page'])
@section ('body')
	<div class="login-box">
	    <div class="logo">
	        <a href="javascript:void(0);"><b>Pigijo</b></a>
	        <small>Travel Planner</small>
	    </div>
	    <div class="card">
	        <div class="body">
	            {{ Form::open(['action' => 'EmployeeController@login','id'=>'sign_in','method'=>'POST']) }}
	                <div class="msg">Sign in to start your session</div>
	                @if(Session::has('error'))
		            <div class="alert alert-danger alert-dismissible" role="alert">
	                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                    {{ Session::get('error') }}
	                </div>
				    @endif
	                <div class="input-group" style="margin-bottom: 20px">
	                    <span class="input-group-addon">
	                        <i class="material-icons">person</i>
	                    </span>
	                    <div class="form-line">
	                        <input type="text" class="form-control" name="email" placeholder="Username / Email" required autofocus>
	                    </div>
	                </div>
	                <div class="input-group" style="margin-bottom: 20px">
	                    <span class="input-group-addon">
	                        <i class="material-icons">lock</i>
	                    </span>
	                    <div class="form-line">
	                        <input type="password" class="form-control" name="password" placeholder="Password" required>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-xs-8 p-t-5">
	                        <input type="checkbox" name="remember_me" id="rememberme" class="filled-in chk-col-pink">
	                        <label for="rememberme">Remember Me</label>
	                    </div>
	                    <div class="col-xs-4">
	                        <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
	                    </div>
	                </div>
	            {{ Form::close() }}
	        </div>
	    </div>
	</div>
	@section('head-js')
	<!-- Jquery Core Js -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.js')}}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{asset('plugins/node-waves/waves.js')}}"></script>

    <!-- Validation Plugin Js -->
    <script src="{{asset('plugins/jquery-validation/jquery.validate.js')}}"></script>

    <!-- Custom Js -->
    <script src="{{asset('js/admin.js')}}"></script>
    <script src="{{asset('js/pages/examples/sign-in.js')}}"></script>
	@stop
@stop
