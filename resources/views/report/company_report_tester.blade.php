@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/boostrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" />
@stop
@section('main-content')
			<div class="block-header">
                <h2>
                    Company
                    <small>Report / Company</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2>Company Growth</h2>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" id="filter" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="{{url('report/company?option=today')}}" class="btn">Day</a>
                                        <a href="{{url('report/company?option=week')}}" class="btn">This Week</a>
                                        <a href="{{url('report/company?option=month')}}" class="btn">This Month</a>
                                        <a href="{{url('report/company?option=year')}}" class="btn">This Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-md-12">
                                    <canvas id="myChart"></canvas>
                                </div>
                                <div class="col-md-12">
                                    <canvas id="myChart1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2>Company Transaksi</h2>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" id="filter_transaksi" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a id="transaksi" data-type="today" class="btn">Day</a>
                                        <a id="transaksi" data-type="week" class="btn">This Week</a>
                                        <a id="transaksi" data-type="month" class="btn">This Month</a>
                                        <a id="transaksi" data-type="year" class="btn">This Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable table-modif" id="data-tables">
                                    <thead>
                                        <tr>
                                            <th>Company</th>
                                            <th>Transaksi Berhasil</th>
                                            <th>Transaksi Lainnya</th>
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
<script src="{{ asset('plugins/chartjs/Chart.bundle.js') }}"></script>
<!-- Bootstrap date range picker -->
<script src="{{asset('plugins/momentjs/moment.js')}}"></script>
<script src="{{asset('plugins/boostrap-daterangepicker/daterangepicker.js')}}"></script>

<script src="{{asset('plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
<script src="{{asset('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}"></script>
<script src="{{asset('plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
<script src="{{asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}"></script>

<script>
    $(document).ready(function(){
        $("#filter").daterangepicker({
            opens: "left",
            startDate: "{{date('d/F/Y', strtotime($start))}}",
            endDate:  "{{date('d/F/Y', strtotime($end))}}",
            // minDate: moment(), 
            locale: {
                format: 'DD MMMM YYYY',
            },
        },function(start, end){
            window.location = "{{url('report/company')}}?start="+start.format('YYYY-MM-DD')+"&end="+end.format('YYYY-MM-DD');
        });
        var start_transaksi,end_transaksi;
        $("#filter_transaksi").daterangepicker({
            opens: "left",
            startDate: "{{date('d/F/Y', strtotime($start_transaksi))}}",
            endDate:  "{{date('d/F/Y', strtotime($end_transaksi))}}",
            // minDate: moment(), 
            locale: {
                format: 'DD MMMM YYYY',
            },
        },function(start, end){
            var start_transaksi = start;
            var edn_transaksi = end;
            // window.location = "{{url('report/company')}}?start_transaksi="+start.format('YYYY-MM-DD')+"&end_transaksi="+end.format('YYYY-MM-DD');
        });
        console.log(start_transaksi);
        var oTable = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'https://datatables.yajrabox.com/eloquent/advance-filter-data',
            data: function (d) {
                d.name = $('input[name=name]').val();
                d.operator = $('select[name=operator]').val();
                d.post = $('input[name=post]').val();
            }
        },
        columns: [
            {data: 'id', name: 'users.id'},
            {data: 'name', name: 'users.name'},
            {data: 'email', name: 'users.email'},
            {data: 'count', name: 'count', searchable: false},
            {data: 'created_at', name: 'users.created_at'},
            {data: 'updated_at', name: 'users.updated_at'}
        ]
    });
        
    });
</script>
@stop
