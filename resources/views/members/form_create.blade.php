@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@stop
@section('main-content')
			<div class="block-header">
                <h2>
                    Add Members
                    <small>Admin Data / Members / Add</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Add New Members
                            </h2>
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
                          <br>
                            {{ Form::open(['route'=>'members.store', 'method'=>'POST', 'class'=>'','id'=>'form_advanced_validation']) }}
                              @csrf
                              <div class="row clearfix">
                                  <div class="col-sm-12">
                                      <div class="form-group">
                                          <div class="form-line">
                                            <h2 class="card-inside-title">Gendre</h2>
                                            <select name="gendre" class="form-control show-tick">
                                                <option value="">-- Please select --</option>
                                                <option value="Mr">Mr</option>
                                                <option value="Ms">Ms</option>
                                                <option value="Mrs">Mrs</option>
                                            </select>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">First Name</h2>
                                          <div class="form-line">
                                              <input name="firstname" type="text" class="form-control" placeholder="Firstname" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Last Name</h2>
                                          <div class="form-line">
                                              <input name="lastname" type="text" class="form-control" placeholder="Lastname" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">User Name</h2>
                                          <div class="form-line">
                                              <input name="username" type="text" class="form-control" placeholder="Username" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">E-Mail</h2>
                                          <div class="form-line">
                                              <input name="email" type="email" class="form-control" placeholder="Email" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Phone</h2>
                                          <div class="form-line">
                                              <input type="text" name="phone" class="form-control" placeholder="Phone" />
                                          </div>
                                      </div>
                                      <div class="demo-radio-button">
                                        <h2 class="card-inside-title">Status</h2>
                                          <input name="status" type="radio" id="radio_7" class="radio-col-purple" value="1" checked />
                                          <label for="radio_7">Active</label>
                                          <input name="status" type="radio" id="radio_8" class="radio-col-yellow" value="2" />
                                          <label for="radio_8">Non Active</label>
                                          <input name="status" type="radio" id="radio_9" class="radio-col-red" value="3" />
                                          <label for="radio_9">Banned</label>
                                      </div>
                                  </div>
                              </div>
                              <br>
                              <br>
                              <br>
                              <button type="submit" class="btn btn-info center">Save</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

@stop
@section('head-js')
@parent
    <script src="{{asset('plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}"></script>
    <!-- <script src="{{asset('js/pages/tables/jquery-datatable.js')}}"></script> -->
    <script type="text/javascript">
    	$('#data-tables').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '/admin/members',
	        columns: [
              {data: 'id'},
              {data: 'firstname'},
              {data: 'created_at'},
              {data: 'updated_at'},
              {data: 'action'}
	        ]
	    });
    </script>
@stop
