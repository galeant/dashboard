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
                    Members
                    <small>Report / Members</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2>Member Growth</h2>
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
                                        <a href="{{url('report/member?option=day')}}" class="btn">Day</a>
                                        <a href="{{url('report/member?option=this_week')}}" class="btn">This Week</a>
                                        <a href="{{url('report/member?option=this_month')}}" class="btn">This Month</a>
                                        <a href="{{url('report/member?option=this_year')}}" class="btn">This Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div id="line_chart" class="graph"></div>
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
                window.location = "{{url('report/member')}}?start_date="+start.format('YYYY-MM-DD')+"&until_date="+end.format('YYYY-MM-DD');
            });
        var list = [];
        var i = 0;
        @foreach($data as $key => $d)
            var member = [];
            member["date"] = "{{$key}}";         
            member["member"] = "{{$d}}";
            list.push(member);
        @endforeach
        console.log(list);  
        Morris.Line({
            element: line_chart,
            data: list,
            xkey: 'date',
            ykeys: ['member'],
            labels: ['Member'],
            lineColors: ['rgb(233, 30, 99)', 'rgb(0, 188, 212)'],
            lineWidth: 3,
            parseTime: false,
        });
    });
</script>
@stop
