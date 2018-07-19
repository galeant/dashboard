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
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>All Place / Destination</h2>
                    </div>
                    <div class="body" style="padding-left:20px">
                        <div class="row">
                            <div class="col-md-3">Place Type *</div>
                            <div class="col-md-3">Province *</div>
                            <div class="col-md-6">City *</div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" name="placeType" id="placeType">
                                    <option value="">-Select Type-</option>
                                    @foreach($destination_type as $dt)
                                    <option value="{{$dt->name_EN}}">{{$dt->name_EN}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="province" id="province">
                                    <option value="">-Select Province-</option>
                                    @foreach($province as $p)
                                    <option value="{{$p->id}}">{{$p->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="city" id="city"  class="form-control" required>
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
                            <div class="col-md-12">
                                Search a place*
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="keyword" id="keyword" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix" id="data_product">
                    
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
                <table class="table table-bordered table-striped table-hover dataTable">
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
                    <tbody id="listPlace">
                    @foreach($destination as $d)
                        <tr>
                            <td>{{$d->destination_types->name}}</td>
                            <td>{{$d->destination_name}}</td>
                            <td>{{$d->provinces->name}}</td>
                            <td>{{$d->cities->name}}</td>
                            <td>{{$d->updated_at}}</td>
                            <td>
                                @if($d->status == "active")
                                <div class="switch">
                                    <label><input type="checkbox" id="status{{$d->id}}" onchange="updatestatus({{$d->id}})" checked><span class="lever"></span></label>
                                </div>
                                @else
                                <div class="switch">
                                    <label><input type="checkbox" id="status{{$d->id}}" onchange="updatestatus({{$d->id}})"><span class="lever"></span></label>
                                </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{url('/master/destination/'.$d->id.'/edit')}}">
                                    View Detail
                                </a>
                                
                            </td>

                        </tr>   
                    @endforeach
                         
                </tbody>
                </table>
                
            </div>
        </div>  
    </div>
@endsection

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
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/pages/tables/jquery-datatable.js') }}"></script>
    <script src="{{ asset('js/demo.js') }}"></script>
    <script type="text/javascript">
    	$(document).ready(function(){
            $("input[name='placeScheduleType']:radio").change(function () {
                var choose = $(this).val();
                if(choose=="yes"){
                    $("#placeSchedule").show();
                }
                else{
                    $("#placeSchedule").hide();
                }
            });
            $("select[name='placeActivity[]']").select2({
                tags:true,
                placeholder: "Start type here."
            });

            $("select.type").change(function(){
                var value = $(this).val();
                if(value=="Close"){
                    $(this).closest(".row").find(".time").hide();
                    $(this).closest(".row").find(".checkbox").hide();
                }
                else{

                    $(this).closest(".row").find(".time").show();
                    $(this).closest(".row").find(".checkbox").show();
                }
            });
        });
        $("ul.list a").click(function(){
            $(this).closest("ul").find(".active").removeAttr("class");
            $(this).closest("li").addClass("active")
            var a = $(this).attr("href");
            console.log(a);
        });
        $("title").text("Super Admin Pigijo");
    </script>
    <script>
        $(document).ready(function(){
            var table = $('.dataTable').DataTable({
                searching: false,
                responsive: true,
                order: [[4, "desc"]]
            });
            $("#placeType, #province, #city").change(function(){
                var table = $('.dataTable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: "{{url('admin/master/place/find')}}",
                        data: { 
                            place_type : $("#placeType").val(),
                            province : $("#province").val(),
                            city : $("#city").val(),
                            keyword : $("#keyword").val(),
                        },
                        type: 'GET'
                    },
                    columns: [
                        { data: 'placeTypeNameEN' },
                        { data: 'destination' },
                        { data: 'province', name: 'province' },
                        { data: 'city', name: 'city' },
                        { data: 'updated', name: 'updated' },
                        { 
                            data: null, 
                            name: 'status',
                            "render": function ( data, type, full, meta ) {
                                var statusPlace = "status"+data.destinationId;
                                if(data.status=="active"){
                                    return '<div class="switch"><label><input type="checkbox" id="status'+data.destinationId+'" onchange="updatestatus('+data.destinationId+')" checked><span class="lever"></span></label></div>';
                                }
                                else{
                                    return '<div class="switch"><label><input type="checkbox" id="status'+data.destinationId+'" onchange="updatestatus('+data.destinationId+')"><span class="lever"></span></label></div>';
                                }
                            }
                        },
                        { 
                            data: 'action', 
                            name: 'destinationId',
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
                        url: "{{url('admin/master/place/find')}}",
                        data: { 
                            place_type : $("#placeType").val(),
                            province : $("#province").val(),
                            city : $("#city").val(),
                            keyword : $("#keyword").val(),
                        },
                        type: 'GET'
                    },
                    columns: [
                        { data: 'placeTypeNameEN' },
                        { data: 'destination' },
                        { data: 'province', name: 'province' },
                        { data: 'city', name: 'city' },
                        { data: 'updated', name: 'updated' },
                        { 
                            data: null, 
                            name: 'status',
                            "render": function ( data, type, full, meta ) {
                                var statusPlace = "status"+data.destinationId;
                                if(data.status=="active"){
                                    return '<div class="switch"><label><input type="checkbox" id="status'+data.destinationId+'" onchange="updatestatus('+data.destinationId+')" checked><span class="lever"></span></label></div>';
                                }
                                else{
                                    return '<div class="switch"><label><input type="checkbox" id="status'+data.destinationId+'" onchange="updatestatus('+data.destinationId+')"><span class="lever"></span></label></div>';
                                }
                            }
                        },
                        { 
                            data: 'action', 
                            name: 'destinationId',
                        }
                    ]
                });
            });
            $("select[name='province']").change(function(){
                var idProvince = $(this).val();
                $("select[name='city']").empty();
                $.ajax({
                    method: "GET",
                    url: "{{ url('/admin/dataapi/findCity') }}"+"/"+idProvince
                }).done(function(response) {
                    $("select[name='city']").append("<option value=''>-Select City-</option>");
                    $.each(response, function (index, value) {
                        $("select[name='city']").append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                    var table = $('.dataTable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: "{{url('admin/master/place/find')}}",
                        data: { 
                            place_type : $("#placeType").val(),
                            province : $("#province").val(),
                            city : $("#city").val(),
                            keyword : $("#keyword").val(),
                        },
                        type: 'GET'
                    },
                    columns: [
                        { data: 'placeTypeNameEN' },
                        { data: 'destination' },
                        { data: 'province', name: 'province' },
                        { data: 'city', name: 'city' },
                        { data: 'updated', name: 'updated' },
                        { 
                            data: null, 
                            name: 'status',
                            "render": function ( data, type, full, meta ) {
                                var statusPlace = "status"+data.destinationId;
                                if(data.status=="active"){
                                    return '<div class="switch"><label><input type="checkbox" id="status'+data.destinationId+'" onchange="updatestatus('+data.destinationId+')" checked><span class="lever"></span></label></div>';
                                }
                                else{
                                    return '<div class="switch"><label><input type="checkbox" id="status'+data.destinationId+'" onchange="updatestatus('+data.destinationId+')"><span class="lever"></span></label></div>';
                                }
                            }
                        },
                        { 
                            data: 'action', 
                            name: 'destinationId',
                        }
                    ]
                });
                });
            });
            
        });
    </script>
    <script>
        function updatestatus(id){
            if($("#status"+id).prop("checked")){
                alert()
                $.ajax({
                    url: "{{url('destination/status/active')}}/"+id,
                    type: "GET"
                });
            }
            else{
                $.ajax({
                    url: "{{url('destination/status/disabled')}}/"+id,
                    type: "GET"
                });
            }
        }
    </script>
@stop

