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
                    Tour
                    <small>Report / Tour</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2>Total Sales</h2>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div id="total_sales_chart" class="graph"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2>Top Sales</h2>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div id="top_sales_chart" class="graph"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2>Total Activity</h2>
                                </div>
                                <div class="col-md-4">
                                    <div class="">
                                        <div class="form-group">
                                            <input type="text" id="filter" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="{{url('report/tour?status=all&option=today')}}" class="btn">Day</a>
                                        <a href="{{url('report/tour?status=all&option=this_week')}}" class="btn">This Week</a>
                                        <a href="{{url('report/tour?status=all&option=this_month')}}" class="btn">This Month</a>
                                        <a href="{{url('report/tour?status=all&option=this_year')}}" class="btn">This Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div id="total_activity_chart" class="graph"></div>
                        </div>
                    </div>
                </div>
            </div>

@stop
@section('head-js')
@parent
<script src="{{asset('plugins/raphael/raphael.min.js')}}"></script>
<script src="{{asset('plugins/morrisjs/morris.js')}}"></script>
<!-- Bootstrap date range picker -->
<script src="{{asset('plugins/momentjs/moment.js')}}"></script>
<script src="{{asset('plugins/boostrap-daterangepicker/daterangepicker.js')}}"></script>

<script>
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2
    })
    //total_sales
    $(document).ready(function(){
        
        var list = [];
        var i = 0;
        @foreach($total_sales as $key => $d)
            var member = [];
            member["label"] = "{{$d['tour_name']}}";         
            member["value"] = "{{$d['sales']}}";
            list.push(member);
        @endforeach
        Morris.Donut({
            element: total_sales_chart,
            data: list,
            xkey: 'date',
            ykeys: ['tour'],
            labels: ['Tour'],
            lineColors: ['rgb(233, 30, 99)', 'rgb(0, 188, 212)'],
            lineWidth: 2,
            resize: false,
            parseTime: false,
            formatter: function (y) {
                return formatter.format(y)
            }
        });
    });
    //top_sales
    $(document).ready(function(){
        
        var list_top_sales = [];
        var i = 0;
        @foreach($top_sales as $key => $d)
            var member = [];
            member["label"] = "{{$d['tour_name']}}";         
            member["value"] = "{{$d['total']}}";
            list_top_sales.push(member);
        @endforeach
        Morris.Donut({
            element: top_sales_chart,
            data: list_top_sales,
            xkey: 'date',
            ykeys: ['tour'],
            labels: ['Tour'],
            lineColors: ['rgb(233, 30, 99)', 'rgb(0, 188, 212)'],
            lineWidth: 2,
            resize: false,
            parseTime: false,
            formatter: function (y) {
                return y
            }
        });
    });

    $(document).ready(function(){
        $("#filter").daterangepicker({
            opens: "left",
            startDate: "{{date('d/F/Y', strtotime($start_date))}}",
            endDate:  "{{date('d/F/Y', strtotime($until_date))}}",
            // minDate: moment(), 
            locale: {
                format: 'DD MMMM YYYY',
            },
        },
            function(start, end){
                window.location = "{{url('report/tour')}}?status=all&start_date="+start.format('YYYY-MM-DD')+"&until_date="+end.format('YYYY-MM-DD');
            });

        var list_total_activity = [];
        var i = 0;
        @foreach($total_activity as $key => $d)
            var tour = [];
            tour["x"] = "{{$key}}";         
            tour["0"] = "{{$d[0]}}";         
            tour["1"] = "{{$d[1]}}";
            tour["2"] = "{{$d[2]}}";         
            tour["3"] = "{{$d[3]}}";
            list_total_activity.push(tour);
        @endforeach
        Morris.Bar({
            element: total_activity_chart,
            data: list_total_activity,
            xkey: 'x',
            ykeys: ['0', '1', '2', '3'],
            labels: ['Draft','Awaiting', 'Active', 'Disable'],
            barColors: ['rgb(233, 30, 99)', 'rgb(0, 188, 212)', 'rgb(0, 150, 136)'],
        });
    });
</script>
@stop
