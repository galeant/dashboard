@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@stop
@section('main-content')

			<div class="block-header">
                <h2>
                    City List
                    <small>Report / City</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Total Tour & Destinasi
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li >
                                    <a href="{{ url('report/city/ext?type=sum&action=export') }}" class="btn bg-teal btn-block waves-effect">Export Excel</a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable table-modif" id="data-tables">
                                    <thead>
                                        <tr>
                                            <th>Provinsi</th>
                                            <th>Kota</th>
                                            <th>Jumlah Tour</th>
                                            <th>Jumlah Destinasi</th>
                                        </tr>
                                    </thead>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>City & Tour</h2>
                            <ul class="header-dropdown m-r--5">
                                <li >
                                    <a href="{{ url('report/city/ext?type=tour&action=export') }}" class="btn bg-teal btn-block waves-effect">Export Excel</a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable table-modif" id="data-tables1">
                                    <thead>
                                        <tr>
                                            <th>Kota</th>
                                            <th>Tour</th>
                                        </tr>
                                    </thead>
                                   
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>City & Destinations</h2>
                            <ul class="header-dropdown m-r--5">
                                <li >
                                    <a href="{{ url('report/city/ext?type=dest&action=export') }}" class="btn bg-teal btn-block waves-effect">Export Excel</a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable table-modif" id="data-tables2">
                                    <thead>
                                        <tr>
                                            <th>Kota</th>
                                            <th>Destinasi</th>
                                        </tr>
                                    </thead>
                                    
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
    <script>
    $(document).ready(function() {
        // $('#data-tables,#data-tables1,#data-tables2').DataTable();\ 
        $('#data-tables').DataTable({
            "ajax": {
                "url" : "{{ url('report/city/ext') }}",
                "type" : "GET",
                "data" :{
                    "type" : "sum",
                    "action" : "ajax"
                }
            },"columns": [
                { "data": "provinsi" },
                { "data": "kota" },
                { "data": "jumlah_tour" },
                { "data": "jumlah_destinasi" }
            ]
        });
        $('#data-tables1').DataTable({
            "ajax": {
                "url" : "{{ url('report/city/ext') }}",
                "type" : "GET",
                "data" :{
                    "type" : "tour",
                    "action" : "ajax"
                }
            },"columns": [
                { "data": "kota" },
                { "data": "tour" }
            ]
        });
        $('#data-tables2').DataTable({
            "ajax": {
                "url" : "{{ url('report/city/ext') }}",
                "type" : "GET",
                "data" :{
                    "type" : "dest",
                    "action" : "ajax"
                }
            },"columns": [
                { "data": "kota" },
                { "data": "destinations" }
            ]
        });
    });
    </script>
@stop