@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
    <style>
    ul.header-dropdown{
        margin-top:-15px;
    }
    a#export{
        float:right;
        margin:0px 5px;
    }
    #head_card{
        float:left;
        padding-bottom:10px;
    }
    </style>
@stop
@section('main-content')
			<div class="block-header">
                <h2 id="head_card">
                    Settlment {{ date('d m Y',strtotime($data->period_start)) }} - {{ date('d m Y',strtotime($data->period_end)) }}
                    <small>Admin Data / Settlement</small>
                </h2>
                <a id="export" href="{{ url('settlement/excel/'.$data->id) }}" class="btn bg-deep-orange waves-effect">Generate Excel</a>
                <a id="export" href="#" class="btn bg-orange waves-effect">Generate PDF</a>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Settlment {{ date('d m Y',strtotime($data->period_start)) }} - {{ date('d m Y',strtotime($data->period_end)) }} <span class="badge bg-orange">{{count($data->settlement)}}</span>
                            </h2>
                            <ul class="header-dropdown">
                                <li>
                                    @if($data->status == 1)
                                    <form method="POST" action="{{ url('settlement/paid') }}" id="paid">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$data->id}}"/>
                                        <a id="paid" class="btn bg-teal btn-block waves-effect">Paid</a>
                                    <form>
                                    @else
                                        <a class="btn bg-deep-orange btn-block waves-effect">Clear</a>
                                    @endif
                                    <p><span class="badge bg-cyan">{{ date('d m Y',strtotime($data->period_start)) }} - {{ date('d m Y',strtotime($data->period_end)) }}</span></p>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            @include('errors.error_notification')
                            <div class="table-responsive">
                                <table class="table table-modif table-bordered table-striped table-hover dataTable js-exportable" id="data-tables">
                                    <thead>
                                        <tr>
                                            <th>Booking Number</th>
                                            <th>Product Type</th>
                                            <th>Product Name</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Total Paid</th>
                                            <th>Total Commssion</th>
                                            <th>Account Bank</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($data->settlement) > 0)
                                        @foreach($data->settlement as $set)
                                        <tr>
                                            <td>{{$set->booking_number}}</td>
                                            <td>{{$set->product_type}}</td>
                                            <td>{{$set->product_name}}</td>
                                            <td>{{$set->qty}}</td>
                                            <td>{{Helpers::idr($set->unit_price)}}</td>
                                            <td>{{Helpers::idr($set->total_price - $set->commission)}}</td>
                                            <td>{{Helpers::idr($set->total_commission)}}</td>
                                            
                                            <td>
                                                @if($set->bank_account_number != null)
                                                <button type="button" class="btn btn-primary btn-block waves-effect" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="left" title="" data-content="{{$set->bank_name}} : {{$set->bank_account_number}}" data-original-title="{{$set->bank_account_name}}">
                                                    {{$set->bank_account_number}}
                                                </button>
                                                @else
                                                    Bank account not inserted
                                                @endif     
                                            </td>
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
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script>
    $(document).ready(function(){
        $("#paid").click(function(){
            var form = $("form#paid");
            swal({
                title: "Are you sure?",
                text: "You will not be able to change this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function(isConfirm){
                if (isConfirm) form.submit();
            });
        });
        $('[data-toggle="popover"]').popover();
    });
   </script>
@stop
