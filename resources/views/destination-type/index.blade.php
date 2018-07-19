@extends('admin.layouts.routing')

@section('header')
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Jquery DataTable | Bootstrap Based Admin Template - Material Design</title>
    <!-- Favicon-->
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/node-waves/waves.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/animate-css/animate.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/telformat/css/intlTelInput.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/themes/all-themes.css') }}" rel="stylesheet" />
@endsection

@section('page')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Place Type</h2>
                    </div>
                </div>
                <div class="row clearfix" id="data_product">
                    
                </div>  
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card" id="listPlaceType">
            <div class="header">
                <h2>All Place Type</h2>
            </div>
            <div class="body form-group">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>(EN) Place Type</th>
                            <th>(ID) Place Type</th>
                            <th>Action</th>
                        </tr>
                    </thead> 
                    <tbody>
                        @foreach($place_type as $pt)
                        <tr>
                            <td>{{$pt->placeTypeId}}</td>
                            <td>{{$pt->placeTypeNameEN}}</td>
                            <td>{{$pt->placeTypeNameID}}</td>
                            <td>
                                <a href="javascript:editPlaceType({{$pt->placeTypeId}})">
                                    Edit
                                </a>  
                                <a href="javascript:deletePlaceType({{$pt->placeTypeId}})" style="padding-left:15px">
                                    Delete
                                </a>   
                            </td>                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <br><br>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="button" class="form-control bg-orange btn waves-effect" value="Add" id="showAddPlaceType">
                    </div>
                </div>
                <br>
            </div>
            
        </div>
        <div class="card" id="addPlaceType" hidden>
            <form action="{{ url('/admin/master/place-type/add') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="header">
                    <h2>Add New Place Type</h2>
                </div>
                <div class="body">
                    <div class="row form-group form-line">
                        <div class="col-md-6">
                            <h5>(EN) Place Type* :</h5>
                            <input type="text" name="placeTypeNameEN" class="form-control" required>
                            
                        </div>
                        <div class="col-md-6">
                            <h5>(ID) Place Type* :</h5>
                            <input type="text" name="placeTypeNameID" class="form-control" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="submit" class="form-control btn bg-orange waves-effect" value="Add New Place Type">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="button" class="form-control btn waves-effect" value="Cancel" id="cancelAddPlaceType">
                        </div>
                    </div>

                </div>
            </form>
        </div>  
        <div class="card" id="editPlaceType" hidden>
            <form action="{{ url('/admin/master/place-type/edit') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="header">
                    <h2>Edit Place Type</h2>
                </div>
                <div class="body">
                    <div class="row form-group form-line">
                        <input type="hidden" name="editPlaceTypeId">
                        <div class="col-md-6">
                            <h5>(EN) Place Type* :</h5>
                            <input type="text" name="editPlaceTypeNameEN" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <h5>(ID) Place Type* :</h5>
                            <input type="text" name="editPlaceTypeNameID" class="form-control" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="submit" class="form-control btn bg-orange waves-effect" value="Edit Place Type">
                        </div>
                    </div>
                    
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="button" class="form-control btn waves-effect" value="Cancel" id="cancelEditPlaceType">
                        </div>
                    </div>
                </div>
            </form>
        </div>  
    </div>
@endsection

@section('footer')
    <!-- Jquery Core Js -->
    
    <!-- Jquery Core Js -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('plugins/node-waves/waves.js') }}"></script>
    <script src="{{ asset('plugins/telformat/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/pages/tables/jquery-datatable.js') }}"></script>
    <script src="{{ asset('js/demo.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){ 
            // var table = $('#dataTable').DataTable();
            // $('#dataTable').on('click', 'td', function () {
            //     var data = table.row( this ).data();
            //     alert( 'You clicked on '+data[0]+'\'s row' );
            // } );
        });
        function editPlaceType(id){
            $.ajax({
                method: "GET",
                url: "{{ url('/admin/master/place-type/find') }}"+"/"+id
            }).done(function(response) {
                $("input[name='editPlaceTypeId']").val(response.placeTypeId);
                $("input[name='editPlaceTypeNameEN']").val(response.placeTypeNameEN);
                $("input[name='editPlaceTypeNameID']").val(response.placeTypeNameID);
                $("#listPlaceType").hide();
                $("#addPlaceType").hide();
                $("#editPlaceType").show();
            });
        }
        $("#showAddPlaceType").click(function(){
            $("#addPlaceType").show();
            $("#listPlaceType").hide();
            $("#editPlaceType").hide();
        });
        $("#cancelAddPlaceType").click(function(){
            $("#listPlaceType").show();
            $("#addPlaceType").hide();
            $("#editPlaceType").hide();
        });
        $("#cancelEditPlaceType").click(function(){
            $("#listPlaceType").show();
            $("#addPlaceType").show();
            $("#editPlaceType").hide();
        });
        function deletePlaceType(id){
            $(this).closest("tr").remove();
            $.ajax({
                method: "GET",
                url: "{{ url('/admin/master/place-type/delete') }}"+"/"+id
            }).done(function(response) {
                $('#dataTable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: "{{url('admin/master/place-type/list')}}",
                        type: 'GET'
                    },
                    columns: [
                        { data: 'placeTypeId' },
                        { data: 'placeTypeNameEN' },
                        { data: 'placeTypeNameID' },
                        { 
                            data: null, 
                            name: 'status',
                            "render": function ( data, type, full, meta ) {
                                return '<a href="javascript:editPlaceType('+data.placeTypeId+')">Edit</a><a href="javascript:deletePlaceType('+data.placeTypeId+')"  style="padding-left:15px">Delete</a>';
                            }
                        }
                    ]
                });
            }).error(function(request, status, error, response) {
                alert('This Place Type used in Place / Destination')
            });
            
        }
    </script>
@endsection
