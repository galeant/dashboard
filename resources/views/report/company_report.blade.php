@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <style>
        .chart_container{
            width:50px:
        }
    </style>
@stop
@section('main-content')
    <div class="block-header">
        <h2>Report Company</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#home_only_icon_title" data-toggle="tab">
                                <i class="material-icons">view_list</i>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#profile_only_icon_title" data-toggle="tab">
                                <i class="material-icons">graphic_eq</i>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#messages_only_icon_title" data-toggle="tab">
                                <i class="material-icons">pie_chart</i>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="home_only_icon_title">
                            @php
                                $ar = $data->groupBy('status');
                            @endphp
                            <div class="card">
                                <div class="body">
                                    <div class="row">
                                        @foreach($ar as $i=>$d)
                                        <div class="col-md-3">
                                            <div class="info-box">
                                                <div class="icon bg-indigo">
                                                    <i class="material-icons">face</i>
                                                </div>
                                                <div class="content">
                                                    <div class="text">
                                                        @if($i == 0)
                                                            NOT VERIFIED
                                                        @elseif($i == 1)
                                                            AWAITING SUBMISSION
                                                        @elseif($i == 2)
                                                            AWAITING MODERATION
                                                        @elseif($i == 3)
                                                            INSUFFICIENT DATA
                                                        @elseif($i == 4)
                                                            REJECTED
                                                        @elseif($i == 5)
                                                            ACTIVE
                                                        @elseif($i == 6)
                                                            DISABLED
                                                        @else
                                                            ALL
                                                        @endif                                                        
                                                    </div>
                                                    <div class="number count-to" data-from="0" data-to="{{count($d)}}" data-speed="10" data-fresh-interval="10">{{count($d)}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <!-- <div class="col-md-3">
                                            <div class="info-box">
                                                <div class="icon bg-indigo">
                                                    <i class="material-icons">face</i>
                                                </div>
                                                <div class="content">
                                                    <div class="text">AWAITING SUBMISSION</div>
                                                    <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">257</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box">
                                                <div class="icon bg-indigo">
                                                    <i class="material-icons">face</i>
                                                </div>
                                                <div class="content">
                                                    <div class="text">AWAITING MODERATION</div>
                                                    <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">257</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box">
                                                <div class="icon bg-indigo">
                                                    <i class="material-icons">face</i>
                                                </div>
                                                <div class="content">
                                                    <div class="text">INSUFFICIENT DATA</div>
                                                    <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">257</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box">
                                                <div class="icon bg-indigo">
                                                    <i class="material-icons">face</i>
                                                </div>
                                                <div class="content">
                                                    <div class="text">REJECTED</div>
                                                    <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">257</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box">
                                                <div class="icon bg-indigo">
                                                    <i class="material-icons">face</i>
                                                </div>
                                                <div class="content">
                                                    <div class="text">ACTIVE</div>
                                                    <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">257</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box">
                                                <div class="icon bg-indigo">
                                                    <i class="material-icons">face</i>
                                                </div>
                                                <div class="content">
                                                    <div class="text">DISABLED</div>
                                                    <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">257</div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            @php
                                $list = $data->toArray();
                            @endphp
                            <div class="card">
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable js-exportable table-modif" id="data-tables">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Transaksi Sukses</th>
                                                    <th>Transaksi Gagal/Pending</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($list as $a=>$d)
                                                <tr>
                                                    <td>{{$d['id']}}</td>
                                                    <td>{{$d['company_name']}}</td>
                                                    @php
                                                        $s = [];
                                                        $f = [];
                                                        foreach($d['tours'] as $t){ 
                                                            foreach($t['booking_tours'] as $bt){
                                                                if($bt['status'] == 2 || $bt['status'] == 5){
                                                                    $s[] = $bt['id'];
                                                                }else{
                                                                    $f[] = $bt['id'];
                                                                }
                                                            }
                                                        }  
                                                    @endphp
                                                    <td>{{count($s)}}</td>
                                                    <td>{{count($f)}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="profile_only_icon_title">
                            <canvas id="myChart1"></canvas>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="messages_only_icon_title">
                            <canvas id="myChart2"></canvas>
                        </div>
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
    <!-- Chart Plugins Js -->
    <script src="{{ asset('plugins/chartjs/Chart.bundle.js') }}"></script>
    <script>
        @php
            $list = $data->toArray();
        @endphp
        $(function () {
            new Chart(document.getElementById("myChart1").getContext("2d"), getChartJs('bar'));
        });
        function getChartJs(type) {
            var config = null;
            if (type === 'bar') {
                config = {
                    type: 'bar',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July"],
                        datasets: [{
                            label: "My First dataset",
                            data: [65, 59, 80, 81, 56, 55, 40],
                            backgroundColor: 'rgba(0, 188, 212, 0.8)'
                        }, {
                            label: "My Second dataset",
                            data: [28, 48, 40, 19, 86, 27, 90],
                            backgroundColor: 'rgba(233, 30, 99, 0.8)'
                        },{
                            label: "My third dataset",
                            data: [12, 31, 34, 32, 55, 11, 12],
                            backgroundColor: 'rgba(233, 30, 121, 0.3)',
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: false
                    }
                }
            }
            return config;
        }
    </script>
@stop