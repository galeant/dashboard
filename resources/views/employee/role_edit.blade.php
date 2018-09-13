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
                                <div class="row">
                                    @php
                                        $role_permission = array_pluck($role->rolePermission, 'id');
                                    @endphp
                                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                                        <li role="presentation" class="active"><a href="#index" data-toggle="tab">INDEX</a></li>
                                        <li role="presentation"><a href="#detail" data-toggle="tab">DETAIL</a></li>
                                        <li role="presentation"><a href="#add" data-toggle="tab">ADD</a></li>
                                        <li role="presentation"><a href="#delete" data-toggle="tab">DELETE</a></li>
                                        <li role="presentation"><a href="#update" data-toggle="tab">UPDATE</a></li>
                                        <li role="presentation"><a href="#other" data-toggle="tab">OTHER</a></li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade in active" id="index">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="all-index" class="filled-in chk-col-deep-orange" name="permission"> 
                                                    <label for="all-index">All</label>
                                                </div>
                                                @foreach($index as $i=>$ind)
                                                    @if($ind->description != null || $ind->description != '')
                                                    <div class="col-md-6">
                                                        <input type="checkbox" id="{{$ind->id}}" class="filled-in chk-col-deep-orange" name="permission_id[{{$ind->id}}]"
                                                        @if(in_array($ind->id,$role_permission))
                                                            checked
                                                        @endif
                                                        >
                                                        <label for="{{$ind->id}}">{{$ind->description}}</label>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade in" id="detail">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="all-detail" class="filled-in chk-col-deep-orange" name="permission"> 
                                                    <label for="all-detail">All</label>
                                                </div>
                                                @foreach($detail as $i=>$dt)
                                                    @if($dt->description != null || $dt->description != '')
                                                    <div class="col-md-6">
                                                        <input type="checkbox" id="{{$dt->id}}" class="filled-in chk-col-deep-orange" name="permission_id[{{$dt->id}}]"
                                                        @if(in_array($dt->id,$role_permission))
                                                            checked
                                                        @endif
                                                        >
                                                        <label for="{{$dt->id}}">{{$dt->description}}</label>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade in" id="add">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="all-add" class="filled-in chk-col-deep-orange" name="permission"> 
                                                    <label for="all-add">All</label>
                                                </div>
                                                @foreach($add as $i=>$dd)
                                                    @if($dd->description != null || $dd->description != '')
                                                    <div class="col-md-6">
                                                        <input type="checkbox" id="{{$dd->id}}" class="filled-in chk-col-deep-orange" name="permission_id[{{$dd->id}}]" 
                                                        @if(in_array($dd->id,$role_permission))
                                                            checked
                                                        @endif
                                                        >
                                                        <label for="{{$dd->id}}">{{$dd->description}}</label>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade in" id="delete">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="all-del" class="filled-in chk-col-deep-orange" name="permission"> 
                                                    <label for="all-del">All</label>
                                                </div>
                                                @foreach($delete as $i=>$dl)
                                                    @if($dl->description != null || $dl->description != '')
                                                    <div class="col-md-6">
                                                        <input type="checkbox" id="{{$dl->id}}" class="filled-in chk-col-deep-orange" name="permission_id[{{$dl->id}}]" 
                                                        @if(in_array($dl->id,$role_permission))
                                                            checked
                                                        @endif
                                                        >
                                                        <label for="{{$dl->id}}">{{$dl->description}}</label>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade in" id="update">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="all-up" class="filled-in chk-col-deep-orange" name="permission"> 
                                                    <label for="all-up">All</label>
                                                </div>
                                                @foreach($update as $i=>$up)
                                                    @if($up->description != null || $up->description != '')
                                                    <div class="col-md-6">
                                                        <input type="checkbox" id="{{$up->id}}" class="filled-in chk-col-deep-orange" name="permission_id[{{$up->id}}]" 
                                                        @if(in_array($up->id,$role_permission))
                                                            checked
                                                        @endif
                                                        >
                                                        <label for="{{$up->id}}">{{$up->description}}</label>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade in" id="other">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="checkbox" id="all-ot" class="filled-in chk-col-deep-orange" name="permission"> 
                                                    <label for="all-ot">All</label>
                                                </div>
                                                @foreach($other as $i=>$ot)
                                                    @if($ot->description != null || $ot->description != '')
                                                    <div class="col-md-6">
                                                        <input type="checkbox" id="{{$ot->id}}" class="filled-in chk-col-deep-orange" name="permission_id[{{$ot->id}}]" 
                                                        @if(in_array($ot->id,$role_permission))
                                                            checked
                                                        @endif
                                                        >
                                                        <label for="{{$ot->id}}">{{$ot->description}}</label>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
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
    $("#all-index").change(function(){
        if ($(this).prop('checked')) {
            $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
        }else {
            $("input[type='checkbox']").prop('checked', false);
        }
    });
    $("#all-detail").change(function(){
        if ($(this).prop('checked')) {
            $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
        }else {
            $("input[type='checkbox']").prop('checked', false);
        }
    });
    $("#all-add").change(function(){
        if ($(this).prop('checked')) {
            $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
        }else {
            $("input[type='checkbox']").prop('checked', false);
        }
    });
    $("#all-del").change(function(){
        if ($(this).prop('checked')) {
            $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
        }else {
            $("input[type='checkbox']").prop('checked', false);
        }
    });
    $("#all-up").change(function(){
        if ($(this).prop('checked')) {
            $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
        }else {
            $("input[type='checkbox']").prop('checked', false);
        }
    });
    $("#all-ot").change(function(){
        if ($(this).prop('checked')) {
            $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
        }else {
            $("input[type='checkbox']").prop('checked', false);
        }
    });
</script>
@stop