@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@stop
@section('main-content')
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
                <table class="table table-bordered table-striped table-hover dataTable js-exportable table-modif" id="data-tables">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Name EN</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
                <br><br>
                <div class="form-group">
                    <div class="col-md-12">
                        <a href="/master/destination-type/create" class="btn bg-teal btn-block waves-effect">Add Country</a>
                    </div>
                </div>
                <br>
            </div>
            
        </div>
        <div class="card" id="addPlaceType" hidden>
                @include('errors.error_notification')
            <form action="{{ url('/master/destination-type/') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="header">
                    <h2>Add New Place Type</h2>
                </div>
                <div class="body">
                    <div class="row form-group form-line">
                        <div class="col-md-6">
                            <h5>(EN) Place Type* :</h5>
                            <input type="text" name="name" class="form-control" required>
                            
                        </div>
                        <div class="col-md-6">
                            <h5>(ID) Place Type* :</h5>
                            <input type="text" name="name_EN" class="form-control" required>
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
    <script type="text/javascript">
        $(document).ready(function(){ 
            $('#data-tables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/master/destination-type',
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'name_EN'},
                    {data: 'created_at'},
                    {data: 'updated_at'},
                    {data: 'action'}
                ]
            });
        });
    </script>
@stop