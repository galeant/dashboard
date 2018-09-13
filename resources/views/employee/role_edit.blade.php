@extends ('layouts.app')
@section('head-css')
@parent
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
            Roles
            <small>Autorization / Roles / Edit</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Edit Roles</h2>
		        </div>
		        <div class="body">
		        	@include('errors.error_notification')
			        <div class="row clearfix">
 						<div class="col-lg-8 m-l-30">
                                {{ Form::model($role, ['route' => ['roles.update', $role->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Code role</h5>
                                        <input type="text" class="form-control" name="code" value="{{$role->code}}" required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Name role</h5>
                                        <input type="text" class="form-control" name="name" value="{{$role->description}}" required/>
                                    </div>
                                </div>
                                <div class="row" id="list_role">
                                    @php
                                        $role_permission = array_pluck($role->rolePermission, 'id');
                                    @endphp
                                    @foreach($permission as $index=>$per)
                                        @if($per->description != null || $per->description != '')
                                        <div class="col-md-6">
                                            <input type="checkbox" id="{{$per->id}}" class="filled-in chk-col-deep-orange" name="permission_id[{{$per->id}}]" 
                                                @if(in_array($per->id,$role_permission))
                                                    checked
                                                @endif
                                            >
                                            <label for="{{$per->id}}">{{$per->description}}</label>
                                        </div>
                                        @endif
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

<script src="{{asset('js/pages/forms/form-validation.js')}}"></script>
<script type="text/javascript">

</script>
@stop