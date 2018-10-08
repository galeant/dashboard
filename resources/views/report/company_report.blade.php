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
                                        <a href="{{url('report/company?option_transaksi=today')}}" class="btn">Day</a>
                                        <a href="{{url('report/company?option_transaksi=week')}}" class="btn">This Week</a>
                                        <a href="{{url('report/company?option_transaksi=month')}}" class="btn">This Month</a>
                                        <a href="{{url('report/company?option_transaksi=year')}}" class="btn">This Year</a>
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
                                    <tbody>
                                        @foreach($data_transaksi as $k=>$dt)
                                            <tr>
                                                <td>{{$dt['company']}}</td>
                                                <td>{{count($dt['book_success'])}}</td>
                                                <td>{{count($dt['book_unsuccess'])}}</td>
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
        $('#data-tables').DataTable();
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
        $("#filter_transaksi").daterangepicker({
            opens: "left",
            startDate: "{{date('d/F/Y', strtotime($start_transaksi))}}",
            endDate:  "{{date('d/F/Y', strtotime($end_transaksi))}}",
            // minDate: moment(), 
            locale: {
                format: 'DD MMMM YYYY',
            },
        },function(start, end){
            window.location = "{{url('report/company')}}?start_transaksi="+start.format('YYYY-MM-DD')+"&end_transaksi="+end.format('YYYY-MM-DD');
        });
        // PIE
        var ar = [];
        var label = [];
        var bgColor = [];
        // var label = ["Not Active", "Awaiting Submission", "Awaiting Moderation", "Insufficient Data", "Rejected", "Active", "Disabled"];
        @foreach($pie as $i=>$d)
            ar.push({{count($d)}});
            if({{$i}} == 0){
                label.push('Not Active');
                bgColor.push("#e74c3c");
            }else if({{$i}} == 1){
                label.push('Awaiting Submission');
                bgColor.push("#3498db");
            }else if({{$i}} == 2){
                label.push('Awaiting Moderation');
                bgColor.push("#95a5a6");
            }else if({{$i}} == 3){
                label.push('Insufficient Data');
                bgColor.push("#9b59b6");
            }else if({{$i}} == 4){
                label.push('Rejected');
                bgColor.push("#f1c40f");
            }else if({{$i}} == 5){
                label.push('Active');
                bgColor.push("#2ecc71");
            }else if({{$i}} == 6){
                label.push('Disabled');
                bgColor.push("#34495e");
            }
        @endforeach
        console.log(ar);
        // console.log(label);
        if(ar.length == 0){
            ar = [100];
            label = ['No Data'];
        }
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: label,
            datasets: [{
            backgroundColor: bgColor,
            data: ar
            }]
        }
        });
        // BAR
        var label_bar = [];
        var na = [];
        var aws = [];
        var am = [];
        var id = [];
        var re = [];
        var ac = [];
        var di = [];

        
        @foreach($bar as $k=>$b)
            label_bar.push("{{$k}}");
            na.push({{$b['Not Active']}});
            aws.push({{$b['Awaiting Submission']}});
            am.push({{$b['Awaiting Moderation']}});
            id.push({{$b['Insufficient Data']}});
            re.push({{$b['Rejected']}});
            ac.push({{$b['Active']}});
            di.push({{$b['Disabled']}});
        @endforeach

        var dataSet = [{
            label : "Not Active",
            backgroundColor : "#e74c3c",
            data : na
        },{
            label : "Awaiting Submission",
            backgroundColor : "#3498db",
            data : aws
        },{
            label : "Awaiting Moderation",
            backgroundColor : "#95a5a6",
            data : am
        },{
            label : "Insufficient Data",
            backgroundColor : "#9b59b6",
            data : id
        },{
            label : "Rejected",
            backgroundColor : "#f1c40f",
            data : re
        },{
            label : "Active",
            backgroundColor : "#2ecc71",
            data : ac
        },{
            label : "Disabled",
            backgroundColor : "#34495e",
            data : di
        }];
        console.log(dataSet);
        var ctx1 = document.getElementById("myChart1").getContext('2d');
        var data = {
        labels: label_bar,
        datasets: dataSet
        };

        var myBarChart = new Chart(ctx1, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                }
            }]
            }
        }
        });
    });
</script>
@stop
