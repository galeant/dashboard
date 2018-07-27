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
            Destination Tips Question
            <small>Destination Tips Question Create</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Create Destination Tips Question</h2>
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
						        {{ Form::model($data, ['route' => ['tips-question.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @else
						        {{ Form::open(['route'=>'tips-question.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
						    @endif
						    	<div class="form-group m-b-20">
					                <label>Question</label>
					                 {{ Form::text('question', null, ['class' => 'form-control','placeholder'=>'Please Enter Question','id'=>'question','required'=>'required']) }}
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
<script src="{{asset('js/pages/forms/form-validation.js')}}"></script>
@stop