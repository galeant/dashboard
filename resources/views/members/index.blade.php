@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <style>
    .waves-effect{
        margin-top:20px;
    }
    #data-tables_filter{
        display:none;
    }
    </style>
@stop
@section('main-content')
			<div class="block-header">
                <h2>
                    Members List
                    <small>Admin Data / Members</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                All Members
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <!-- <li>
                                    <a href="/members/create" class="btn bg-teal btn-block waves-effect">Add Member</a>
                                </li> -->
                            </ul>
                            <div class="row">
                                <form method="GET" action="{{url('/members')}}">
                                    <div class="col-md-3">
                                        <label>Member Status</label>
                                        <select name="status" class="form-control">
                                            <option value="99" @if($request != null) selected @endif>All</option>
                                            <option value="1" @if($request != null) @if($request['status'] == 1) selected @endif @endif>Active</option>
                                            <option value="0" @if($request != null) @if($request['status'] == 0) selected @endif @endif>No-Verified</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Search a member by Member ID/Email Address/Fullname/Phone Number</label>
                                        <input type="text" class="form-control" name="keyword" @if($request != null) @if($request['keyword'] != null) value="{{$request['keyword']}}" @endif @endif/>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn bg-green waves-effect">
                                            <i class="material-icons">search</i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-modif table-bordered table-striped table-hover dataTable js-exportable" id="data-tables">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Email Address</th>
                                            <th>Fullname</th>
                                            <th>Phone Number</th>
                                            <th>Member Since</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $member)
                                        <tr>
                                            <td>{{$member->id}}</td>
                                            <td>{{$member->email}}</td>
                                            <td>{{$member->firstname}} {{$member->lastname}}</td>
                                            <td>{{$member->phone}}</td>
                                            <td>{{date('j F Y',strtotime($member->created_at))}}</td>
                                            <td>
                                            @if($member->password != null)
                                                @if($member->status == 1)
                                                    <span class="label bg-green">Active</span>
                                                @else
                                                    <span class="label bg-red">Not Verified</span>
                                                @endif
                                            @else 
                                                <span class="label bg-red">Not Verified</span>
                                            @endif
                                            </td>
                                            <td>
                                                <a style="margin:0px" href="/members/{{$member->id}}" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                                                    <i class="glyphicon glyphicon-eye-open"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

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
    
        $(document).ready(function() {
            $('#data-tables').DataTable();
        });
        // $("select[name='status'],input[name='keyword']").change(function(){
        //     var stat = $("select[name='status']").val();
        //     var keyword = $("input[name='keyword']").val();
        //     $.ajax({
        //         method: "GET",
        //         url: "{{url('/getmember')}}",
        //         data: { status: stat, keyword:keyword }
        //     }).done(function(response) {
        //         $("#data-tables tbody").empty();
        //         $.each(response.data, function (index, value) {
        //             $("#data-tables tbody").append("<tr><td>wdqwdqwd</td></tr>");
        //         });
        //     });
        // });
    </script>
@stop

