@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@stop
@section('main-content')
			<div class="block-header">
                <h2>
                    Members
                    <small>Admin Data / Members / Edit</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Member Details
                            </h2>
                            <ul class="header-dropdown m-r--5">
                            <a id="status" href="{{ url('product/tour-activity/'.$data->id.'/change/status/3') }}" class="btn bg-red waves-effect">Send Change Password Email</a>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="row" style="margin: 0px 3px 10px 3px;">
                                <div class="col-md-4">
                                    <img src="http://placehold.it/500x300" class="img-responsive">
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h5>Fullname</h5>
                                            <h5>{{$data->firstname}} {{$data->lastname}}</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>Email Address</h5>
                                            <h5>{{$data->email}}</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>Phone Number</h5>
                                            <h5>{{$data->phone}}</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>Member Since</h5>
                                            <h5>{{date('j F Y',strtotime($data->created_at))}}</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>Status</h5>
                                            @if($data->password != null)
                                                @if($data->status == 1)
                                                    <span class="label bg-green">Active</span>
                                                @else
                                                    <span class="label bg-red">Not Verified</span>
                                                @endif
                                            @else
                                                <span class="label bg-red">Not Verified</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(count($data->transactions) > 0)
                    <div class="card">
                        <div class="header">
                            <h2>
                                Latest Member Transaction
                            </h2>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>TR.Number</th>
                                        <th>Contact Name</th>
                                        <th>Contact Email Address</th>
                                        <th>Total Payment</th>
                                        <th>Last Updated</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach($data->transactions as $trans)
                                    <tr>
                                        <td>{{$trans->transaction_number}}</td>
                                        <td>
                                            @foreach($trans->contact_list as $con)
                                                <span class="label bg-black">{{$con->firstname}} {{$con->lastname}}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($trans->contact_list as $con)
                                                <span class="label bg-black">{{$con->email}}</span>
                                            @endforeach
                                        </td>
                                        <td>{{Helpers::idr($trans->total_paid)}}</td>
                                        <td>{{date('j F Y',strtotime($trans->updated_at))}}</td>
                                        <td><span class="label" style="background-color:{{$trans->transaction_status->color}}">{{$trans->transaction_status->name}}</span></td>
                                        <td>
                                            <a href="{{url('transaction/'.$trans->transaction_number)}}" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                                                <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
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
