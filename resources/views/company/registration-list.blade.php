@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@stop
@section('main-content')
			<div class="block-header">
                <h2>
                    Partner Activity List
                    <small>Master Data / Partners</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Registration Activity
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>@if (Request::input('sort_by') == 'id')
                                                    <a href="{{ Request::input('sort_id')}}">
                                                        ID @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                                    </a>
                                                @else
                                                    <a href="{{ Request::input('sort_id')}}">
                                                        ID <i class="fa fa-fw fa-sort"></i>
                                                    </a>
                                                @endif
                                            </th> 
                                            <th>Name</th>
                                            <th>PIC Email</th>
                                            <th>PIC Name</th>
                                            <th>@if (Request::input('sort_by') == 'status')
                                                    <a href="{{ Request::input('sort_status')}}">
                                                        Status @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                                    </a>
                                                @else
                                                    <a href="{{ Request::input('sort_status')}}">
                                                        Status <i class="fa fa-fw fa-sort"></i>
                                                    </a>
                                                @endif
                                            </th> 
                                            <th>@if (Request::input('sort') == 'create')
                                                    <a href="{{ Request::input('sort_create')}}">
                                                        Created at @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                                    </a>
                                                @else
                                                    <a href="{{ Request::input('sort_create')}}">
                                                        Created at <i class="fa fa-fw fa-sort"></i>
                                                    </a>
                                                @endif
                                            </th> 
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($datas as $data)
                                            <tr>
                                                <td>{{$data->id}}</td>
                                                <td>{{$data->company_name}}</td>
                                                <td>{{$data->suppliers[0]->email}}</td>
                                                <td>{{$data->suppliers[0]->fullname}}</td>
                                                <td>
                                                    @switch($data->status)
                                                        @case(0)
                                                            <span class="badge bg-grey">Not verified</span>
                                                            @break
                                                        @case(1)
                                                            <span class="badge bg-cyan">Awaiting Submission</span>
                                                            @break
                                                        @case(2)
                                                            <span class="badge bg-indigo">Awaiting Moderation</span>
                                                            @break
                                                        @case(3)
                                                            <span class="badge bg-orange">Insufficient Data</span>
                                                            @break
                                                        @case(4)
                                                            <span class="badge bg-purple">Rejected</span>
                                                            @break
                                                        @case(5)
                                                            <span class="badge bg-green">Active</span>
                                                            @break
                                                        @case(6)
                                                            <span class="badge bg-red">Disabled</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>{{$data->created_at}}</td>
                                                <td>
                                                    <a href="{{ url('partner/'.$data->id.'/edit') }}" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                                                        <i class="glyphicon glyphicon-edit"></i>
                                                    </a>
                                                    <a href="{{ url('partner/'.$data->id.'/delete') }}" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="partner/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                                                        <i class="glyphicon glyphicon-trash"></i>
                                                    </a>
                                                </td>
                                            <tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-6">
                                        {{ $datas->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@stop
@section('head-js')
@parent
<!-- Jquery Core Js -->

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
   
@stop