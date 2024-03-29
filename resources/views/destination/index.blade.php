@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/telformat/css/intlTelInput.css') }}" rel="stylesheet" />
@stop
@section('main-content')
    <div class="block-header">
        <h2>
            Destinations
            <small>Master Data / Destinations</small>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>All Place / Destination</h2>
                </div>
                <form method="get" action="{{ route('destination.index')}}" >
                <div class="body" style="padding-left:20px">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="destination_type_id">Destination Type *</label>
                            <select class="form-control" name="destination_type_id" id="destination_type_id">
                                <option value="">-Select Type-</option>
                                @foreach($destination_type as $dt)
                                <option value="{{$dt->id}}">{{$dt->name_EN}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="destination_type_id">Province *</label>
                            <select class="form-control" name="province_id" id="province_id">
                                <option value="">-Select Province-</option>
                                @foreach($province as $p)
                                <option value="{{$p->id}}" data="{{$p->id}}">{{$p->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="destination_type_id">City *</label>
                            <select name="city_id" id="city_id"  class="form-control">
                                <option value="">--Select City--</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ URL('/master/destination/create') }}">
                                <button type="button" class="btn btn-primary waves-effect">
                                    <i class="material-icons">extension</i>
                                    <span>Add New Destination</span>
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <label for="">Search a place*</label>
                            <input type="text" name="keyword" id="keyword" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn bg-green waves-effect">
                                <i class="material-icons">search</i>
                                <span>Search</span>
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="header">
            <h2>All Place / Destination</h2>
        </div>
        <div class="body table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Destination Type</th>
                        <th>Destination Name</th>
                        <th>Province</th>
                        <th>City</th>
                        <th>Last Updated</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead> 
                <tbody>
                    @foreach($datas as $data)
                    <tr>
                        <td>{{$data->destination_types->name}}</td>
                        <td>{{$data->destination_name}}</td>
                        <td>{{$data->provinces->name}}</td>
                        <td>{{$data->cities->name}}</td>
                        <td>{{$data->updated_at}}</td>
                        <td>
                            <div class="switch">
                                <label>
                                    <input type="checkbox" id="status{{$data->id}}" onchange="updatestatus({{$data->id}})" @if($data->status == 'active')checked @endif>
                                    <span class="lever"></span>
                                </label>
                            </div>
                        </td>
                        <td>
                        <a href="{{ url('master/destination/'.$data->id.'/edit') }}" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <a href="{{ url('master/destination/'.$data->id.'/delete') }}" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/destination/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $datas->links() }}
        </div>
    </div>  
@endsection

@section('head-js')
@parent
    <!-- <script src="{{asset('plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}"></script>
    <script src="{{asset('js/pages/tables/jquery-datatable.js')}}"></script> -->

    <!-- <script>
        $(document).ready(function(){
            $(document).ready(function(){ 
            $('#data-tables').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: '/master/destination',
                columns: [
                    {data: 'destination_types.name_EN'},
                    {data: 'destination_name'},
                    {data: 'provinces.name'},
                    {data: 'cities.name'},
                    {data: 'updated_at'},
                    { 
                        data: null, 
                        name: 'status',
                        "render": function ( data, type, full, meta ) {
                            var statusPlace = "status"+data.id;
                            if(data.status=="active"){
                                return '<div class="switch"><label><input type="checkbox" id="status'+data.id+'" onchange="updatestatus('+data.id+')" checked><span class="lever"></span></label></div>';
                            }
                            else{
                                return '<div class="switch"><label><input type="checkbox" id="status'+data.id+'" onchange="updatestatus('+data.id+')"><span class="lever"></span></label></div>';
                            }
                        }
                    },
                    {data: 'action'}
                ]
            });
        });
            $("#destination_type_id, #province_id, #city_id").change(function(){
                var table = $('.dataTable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: "{{url('master/destination/find')}}",
                        data: { 
                            destination_type_id : $("#destination_type_id").val(),
                            province_id : $("#province_id").val(),
                            city_id : $("#city_id").val(),
                            keyword : $("#keyword").val(),
                        },
                        type: 'GET'
                    },
                    columns: [
                        { data: 'destination_types.name_EN' },
                        { data: 'destination_name' },
                        { data: 'provinces.name', name: 'province' },
                        { data: 'cities.name', name: 'city' },
                        { data: 'updated_at', name: 'updated_at' },
                        { 
                            data: null, 
                            name: 'status',
                            "render": function ( data, type, full, meta ) {
                                var statusPlace = "status"+data.destinationId;
                                con
                                if(data.status=="active"){
                                    return '<div class="switch"><label><input type="checkbox" id="status'+data.id+'" onchange="updatestatus('+data.id+')" checked><span class="lever"></span></label></div>';
                                }
                                else{
                                    return '<div class="switch"><label><input type="checkbox" id="status'+data.id+'" onchange="updatestatus('+data.id+')"><span class="lever"></span></label></div>';
                                }
                            }
                        },
                        { 
                            data: 'action', 
                            name: 'id',
                        }
                    ]
                });
            });
            $("#keyword").keyup(function(){
                var table = $('.dataTable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: "{{url('master/destination/find')}}",
                        data: { 
                            destination_type_id : $("#destination_type_id").val(),
                            province_id : $("#province_id").val(),
                            city_id : $("#city_id").val(),
                            keyword : $("#keyword").val(),
                        },
                        type: 'GET'
                    },
                    columns: [
                        { data: 'destination_types.name_EN' },
                        { data: 'destination_name' },
                        { data: 'provinces.name', name: 'province' },
                        { data: 'cities.name', name: 'city' },
                        { data: 'updated_at', name: 'updated_at' },
                        { 
                            data: null, 
                            name: 'status',
                            "render": function ( data, type, full, meta ) {
                                var statusPlace = "status"+data.destinationId;
                                if(data.status=="active"){
                                    return '<div class="switch"><label><input type="checkbox" id="status'+data.id+'" onchange="updatestatus('+data.id+')" checked><span class="lever"></span></label></div>';
                                }
                                else{
                                    return '<div class="switch"><label><input type="checkbox" id="status'+data.id+'" onchange="updatestatus('+data.id+')"><span class="lever"></span></label></div>';
                                }
                            }
                        },
                        { 
                            data: 'action', 
                            name: 'id',
                        }
                    ]
                });
            });
            $("select[name='province_id']").change(function(){
                var idProvince =  $('option:selected', this).attr('data');
                $("select[name='city_id']").empty();
                $.ajax({
                    method: "GET",
                    url: "{{ url('/json/city') }}",
                    data: { 
                        province_id : idProvince,
                    },
                }).done(function(response) {
                    $("select[name='city_id']").append("<option value=''>-Select City-</option>");
                    $.each(response.data, function (index, value) {
                        $("select[name='city_id']").append(
                            "<option value='"+value.id+"'>"+value.name+"</option>"
                        );
                    });
                    var table = $('.dataTable').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        searching: false,
                        ajax: {
                            url: "{{url('master/destination/find')}}",
                            data: { 
                                destination_type_id : $("#destination_type_id").val(),
                                province_id : $("#province_id").val(),
                                city_id : $("#city_id").val(),
                                keyword : $("#keyword").val(),
                            },
                            type: 'GET'
                        },
                        columns: [
                            { data: 'destination_types.name_EN' },
                            { data: 'destination_name' },
                            { data: 'provinces.name', name: 'province' },
                            { data: 'cities.name', name: 'city' },
                            { data: 'updated_at', name: 'updated_at' },
                            { 
                                data: null, 
                                name: 'status',
                                "render": function ( data, type, full, meta ) {
                                    var statusPlace = "status"+data.destinationId;
                                    if(data.status=="active"){
                                        return '<div class="switch"><label><input type="checkbox" id="status'+data.id+'" onchange="updatestatus('+data.id+')" checked><span class="lever"></span></label></div>';
                                    }
                                    else{
                                        return '<div class="switch"><label><input type="checkbox" id="status'+data.id+'" onchange="updatestatus('+data.id+')"><span class="lever"></span></label></div>';
                                    }
                                }
                            },
                            { 
                                data: 'action', 
                                name: 'id',
                            }
                        ]
                    });
                });
            });
            
        });
    </script> -->
    <script>
        function updatestatus(id){
            if($("#status"+id).prop("checked")){
                $.ajax({
                    url: "{{url('master/destination/status/active')}}/"+id,
                    type: "GET"
                });
            }
            else{
                $.ajax({
                    url: "{{url('master/destination/status/disabled')}}/"+id,
                    type: "GET"
                });
            }
        }
    </script>
@stop

