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
                    <small>Master Data / City</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                All City
                            </h2>
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
                                    <tbody>
                                        @foreach($data as $key=>$d)
                                            @foreach($d as $k=>$c) 
                                                @foreach($c as $i=>$n) 
                                                <tr>
                                                    <td>{{$key}}</td>
                                                    <td>{{$i}}</td>
                                                    <td>{{$n['tour']}}</td>
                                                    <td>{{$n['destinations']}}</td>
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>City & Tour</h2>
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
                                    <tbody>
                                        @foreach($data as $key=>$d)
                                            @foreach($d as $k=>$c) 
                                                @foreach($c as $i=>$n) 
                                                    @foreach($n['list_tour'] as $t)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$t['product_name']}}</td>
                                                    </tr>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>City & Destinations</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable table-modif" id="data-tables1">
                                    <thead>
                                        <tr>
                                            <th>Kota</th>
                                            <th>Destinasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $key=>$d)
                                            @foreach($d as $k=>$c) 
                                                @foreach($c as $i=>$n) 
                                                    @foreach($n['list_destinations'] as $dt)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$dt['destination_name']}}</td>
                                                    </tr>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
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
    <script>
    $(document).ready(function() {
        $('#data-tables,#data-tables1,#data-tables2').DataTable();
    } );
    </script>
@stop