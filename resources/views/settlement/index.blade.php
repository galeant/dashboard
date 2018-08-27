@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@stop
@section('main-content')
			<div class="block-header">
                <h2>
                    Settlment group List
                    <small>Admin Data / Settlement</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                All Settlement Group
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-modif table-bordered table-striped table-hover dataTable js-exportable" id="data-tables">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Total Commission</th>
                                            <th>Total Paid</th>
                                            <th>Note</th>
                                            <th>Paid At</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($data) > 0)
                                        @foreach($data as $set)
                                        <tr>
                                            <td>{{$set->id}}</td>
                                            <td>{{$set->total_commission}}</td>
                                            <td>{{$set->total_paid}}</td>
                                            <td>{{$set->note}}</td>
                                            <td>{{$set->paid_at}}</td>
                                            <td>{{$set->status}}</td>
                                            <td>{{$set->created_at}}</td>
                                            <td></td>
                                        </tr>
                                        @endforeach
                                    @endif
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
   
@stop
