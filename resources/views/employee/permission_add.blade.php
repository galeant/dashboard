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
            <small>Autorization / Roles / Create</a></small>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-md-12">
		    <div class="card">
		        <div class="header">
		            <h2>Create Roles</h2>
		        </div>
		        <div class="body">
		        	@include('errors.error_notification')
			        <div class="row clearfix">
 						<div class="col-lg-8 m-l-30">
						    {{ Form::open(['route'=>'permission.store', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'form_advanced_validation']) }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Code permission</h5>
                                        <input type="text" class="form-control" name="code" required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>URI</h5>
                                        <input type="text" class="form-control" name="uri" required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Path permission name</h5>
                                        <input type="text" class="form-control" name="name" required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Description</h5>
                                        <input type="text" class="form-control" name="description" required/>
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