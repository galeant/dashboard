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
            Partner Level
            <small>Partner Level Create</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Create Partner Level</h2>
		        </div>
		        <div class="body">
		        	@include('errors.error_notification')
			        <div class="row clearfix">
                        @if(isset($data))
                            {{ Form::model($data, ['route' => ['partner-level.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
                        @else
                            {{ Form::open(['route'=>'partner-level.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
                        @endif
 						<div class="col-lg-8 m-l-30">			        
                            <div class="form-group m-b-20">
                                <label>Name</label>
                                    {{ Form::text('name', null, ['class' => 'form-control','placeholder'=>'Please Enter Name','id'=>'code','required'=>'required']) }}
                            </div>
                        </div>
                        <div class='col-md-8 m-l-30'>
                            @php
                                $ar = array_pluck($data->companyLevelCommission->toArray(),'percentage','product_type_code');
                            @endphp
                            @foreach($type as $type)
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="checkbox" id="{{$type->code}}" name="commission[{{$type->code}},{{$type->id}}]" class="filled-in chk-col-red type" 
                                            @if(array_key_exists($type->code,$ar)) checked @endif 
                                        >
                                        <label for="{{$type->code}}">{{$type->name}}</label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group m-b-20">
                                            <label>Commission</label>
                                            <input type="number" class="form-control" id="commission" name="commission[{{$type->code}},{{$type->id}}][comission]" placeholder="Please Enter Commission Value" 
                                            @if(isset($data))
                                                @if(array_key_exists($type->code,$ar)) 
                                                    value= {{$ar[$type->code]}} required
                                                @else
                                                    disabled
                                                @endif 
                                            @else
                                                disabled
                                            @endif
                                            />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
    <!-- #END# Advanced Validation -->
	
@stop
@section('head-js')
@parent
    <!-- Sweet Alert Plugin Js -->
<script src="{{asset('plugins/sweetalert/sweetalert.min.js')}}"></script>

<script src="{{asset('js/pages/forms/form-validation.js')}}"></script>
<script>
    $(document).ready(function(){
        $("input.type").each(function(){
            $(this).change(function(){
                if($(this).is(':checked')){
                    $(this).closest("div.row").find("input#commission").removeAttr('disabled').attr('required','required');
                } else {
                    $(this).closest("div.row").find("input#commission").removeAttr('required').attr('disabled','disabled');
                }
            });
        });
    });
</script>
@stop