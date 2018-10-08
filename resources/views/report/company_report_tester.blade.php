@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/boostrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" />
    <style>
        .loading{
            left: 45%;
            position: absolute;
            top: 50%;
            z-index: 100;
            display:none;
        }
        .blur{
            background-color: rgba(0, 0, 0,0.7);
        }
    </style>
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
                                        <a id="status" data-type="today" class="btn">Day</a>
                                        <a id="status" data-type="week" class="btn">This Week</a>
                                        <a id="status" data-type="month" class="btn">This Month</a>
                                        <a id="status" data-type="year" class="btn">This Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="body chart_content">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{ asset('img/loading.gif') }}" class="loading chart">
                                    <canvas id="myChart"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <img src="{{ asset('img/loading.gif') }}" class="loading chart">
                                    <canvas id="myChart1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="table_transaksi" class="card">
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
                                    <tbody>
                                        <img src="{{ asset('img/loading.gif') }}" class="loading trans">
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
    // STATUS
        // INITIAL
        ajax();
        // DATE
        $("#filter").daterangepicker({
            opens: "left",
            startDate: "{{date('d/F/Y', strtotime($start))}}",
            endDate:  "{{date('d/F/Y', strtotime($end))}}",
            // minDate: moment(), 
            locale: {
                format: 'DD MMMM YYYY',
            },
        },function(start, end){
            var start = start.format("YYYY-MM-DD");
            var end = end.format("YYYY-MM-DD");
            ajax(start,end,null);
        });
        // BUTTON ACTION
        $("a#status").click(function(){
            var duration = $(this).attr('data-type');
            console.log(null,null,duration);
            ajax(null,null,duration);
        })

        function ajax(start = null,end = null, duration=null){ 
            $(".chart_content").addClass("blur")
            $("img.chart").show();
            $.ajax({
                method: "GET",
                url: "{{ url('report/company/grafik') }}",
                data: {
                    start : start,
                    end : end,
                    option : duration
                }
            }).done(function(response) {
                // PIE
                var ar = [];
                var label = [];
                var bgColor = [];
                // BAR
                var label_bar = [];
                var na = [];
                var aws = [];
                var am = [];
                var id = [];
                var re = [];
                var ac = [];
                var di = [];
                // PIE
                if(response.pie.length != 0){
                    $.each(response.pie,function(index,val){
                        ar.push(val.length);
                        if(index == 0){
                            label.push('Not Active');
                            bgColor.push("#e74c3c");
                        }else if(index == 1){
                            label.push('Awaiting Submission');
                            bgColor.push("#3498db");
                        }else if(index == 2){
                            label.push('Awaiting Moderation');
                            bgColor.push("#95a5a6");
                        }else if(index == 3){
                            label.push('Insufficient Data');
                            bgColor.push("#9b59b6");
                        }else if(index == 4){
                            label.push('Rejected');
                            bgColor.push("#f1c40f");
                        }else if(index == 5){
                            label.push('Active');
                            bgColor.push("#2ecc71");
                        }else if(index == 6){
                            label.push('Disabled');
                            bgColor.push("#34495e");
                        }
                    })
                }else{
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
                $.each(response.bar,function(index,value){
                    label_bar.push(index);
                    na.push(value.Not_Active);
                    aws.push(value.Awaiting_Submission);
                    am.push(value.Awaiting_Moderation);
                    id.push(value.Insufficient_Data);
                    re.push(value.Rejected);
                    ac.push(value.Active);
                    di.push(value.Disabled);
                })
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
                var ctx1 = document.getElementById("myChart1").getContext('2d');
                var data = {
                    labels: label_bar,
                    datasets: dataSet
                };

                var myBarChart = new Chart(ctx1, {
                type: 'bar',
                data: data,
                options: {
                    barValueSpacing: 20
                    }
                });
                // console.log(ar);
                $(".chart_content").removeClass("blur")
                $("img.chart").hide();
            });
        }
    // TRANSAKSI
        $("#filter_transaksi").daterangepicker({
            opens: "left",
            startDate: "{{date('d/F/Y', strtotime($start_transaksi))}}",
            endDate:  "{{date('d/F/Y', strtotime($end_transaksi))}}",
            // minDate: moment(), 
            locale: {
                format: 'DD MMMM YYYY',
            },
        },function(start, end){
            $("div#table_transaksi").addClass("table_blur");
            $("img.loading").show();
            var start_transaksi = start.format("YYYY-MM-DD");
            var end_transaksi = end.format("YYYY-MM-DD");
            // window.location = "{{url('report/company')}}?start_transaksi="+start.format('YYYY-MM-DD')+"&end_transaksi="+end.format('YYYY-MM-DD');
            table.ajax.url("company/transaksi?start_transaksi="+start_transaksi+"&end_transaksi="+end_transaksi).load(function(){
                $("div#table_transaksi").removeClass("table_blur");
                $("img.loading").hide();
            });
        });
        var table = $('#data-tables').DataTable({
            "ajax": {
                "url" : "{{ url('report/company/transaksi') }}",
                "type" : "GET"
            },"columns": [
                { "data": "company" },
                { "data": "book_success" },
                { "data": "book_unsuccess" }
            ]
        });
        $("a#transaksi").click(function(){
            $("div#table_transaksi").addClass("blur");
            $("img.trans").show();
            var option = $(this).attr("data-type");
            table.ajax.url("company/transaksi?option_transaksi="+option).load(function(){
                $("div#table_transaksi").removeClass("blur");
                $("img.trans").hide();
            });
        });
    });
</script>
@stop
