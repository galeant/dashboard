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
                                <div class="row" id="all-permission">
                                    <div class="col-md-12">
                                        <input type="checkbox" id="all" class="filled-in chk-col-deep-orange" name="permission"
                                        @if(count($contain_permission) == 151)
                                            checked
                                        @endif
                                        > 
                                        <label for="all">All</label>
                                    </div>
                                    @foreach($permission as $index=>$value)
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="header">
                                                <h2>{{$index}}</h2>
                                            </div>
                                            <div class="body">
                                                <div class="row" id="content-permission">
                                                    <div class="col-md-12">
                                                        <input type="checkbox" id="{{$index}}" class="filled-in chk-col-deep-orange grouping" name="{{$index}}"
                                                        @if(in_array($index,$contain_permission))
                                                            checked
                                                        @endif
                                                        > 
                                                        <label for="{{$index}}">All</label>
                                                    </div>
                                                    @foreach($value as $a)
                                                        <div class="col-md-6">
                                                            <input type="checkbox" id="{{$a['id']}}" class="filled-in chk-col-deep-orange" name="permission_id[{{$a['id']}}]"
                                                            @if(in_array($a['id'],$contain_permission))
                                                                checked
                                                            @endif
                                                            >
                                                            <label for="{{$a['id']}}">
                                                                @if($a['method'] == 'GET')
                                                                    VIEW
                                                                @elseif($a['method'] == 'POST')
                                                                    SAVE
                                                                @elseif($a['method'] == 'PUT ')
                                                                    UPDATE
                                                                @else
                                                                    DELETE
                                                                @endif
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
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
    $("#all").change(function(){
        if ($(this).prop('checked')) {
            // $(this).closest("div#all-permission")
            $(this).closest("div#all-permission").find("input[type='checkbox']").prop('checked', true);
        }else {
            $(this).closest("div#all-permission").find("input[type='checkbox']").prop('checked', false);
        } 
    });
    $('.grouping').each(function(){
        $(this).change(function(){
            if ($(this).prop('checked')) {
                // $(this).closest("#content-permission").css('background-color','red');
                $(this).closest("#content-permission").find("input[type='checkbox']").prop('checked', true);
            }else {
                $(this).closest("#content-permission").find("input[type='checkbox']").prop('checked', false);
            }
        });
    });

    // $("#content-permission").each(function(){
    //     var pp = $("input[type='checkbox']:checked").length;
    // //     var pp = $("input[type='checkbox']").prop('checked',true).length;
    //     console.log(pp);
    // })

    $("input[type='checkbox']").each(function(){
        $(this).change(function(){
            if ($(this).prop('unchecked')) {
                $(this).closest("#content-permission").find(".grouping").prop('checked',false);
                $("#all").prop('checked',false);
            }
        });
    })
    


    // $("#all-index").change(function(){
    //     if ($(this).prop('checked')) {
    //         $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
    //     }else {
    //         $("input[type='checkbox']").prop('checked', false);
    //     }
    // });
    // $("#all-detail").change(function(){
    //     if ($(this).prop('checked')) {
    //         $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
    //     }else {
    //         $("input[type='checkbox']").prop('checked', false);
    //     }
    // });
    // $("#all-add").change(function(){
    //     if ($(this).prop('checked')) {
    //         $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
    //     }else {
    //         $("input[type='checkbox']").prop('checked', false);
    //     }
    // });
    // $("#all-del").change(function(){
    //     if ($(this).prop('checked')) {
    //         $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
    //     }else {
    //         $("input[type='checkbox']").prop('checked', false);
    //     }
    // });
    // $("#all-up").change(function(){
    //     if ($(this).prop('checked')) {
    //         $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
    //     }else {
    //         $("input[type='checkbox']").prop('checked', false);
    //     }
    // });
    // $("#all-ot").change(function(){
    //     if ($(this).prop('checked')) {
    //         $(this).closest("div.tab-pane").find("input[type='checkbox']").prop('checked', true);
    //     }else {
    //         $("input[type='checkbox']").prop('checked', false);
    //     }
    // });
</script>
@stop