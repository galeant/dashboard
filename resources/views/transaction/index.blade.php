@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{asset('plugins/jquery-qtips/jquery.qtip.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />
@stop
@section('main-content')
    <div class="block-header">
        <h2>
            Transaction List 
            <small>Transaction</small>
        </h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        All Transactions <span class="badge">{{$data->total()}}</span>
                    </h2>
                </div>
                <div class="row clearfix">
                    <div class="col-md-12 search-box">
                        {!!Form::open(['method' => 'GET','route' => ['transaction.index'],'style'=>'display:inline'])!!}
                            <div class="col-md-10 ">
                                <div class="row clearfix">
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="form-group">
                                            <label>Transaction Date</label>
                                            {!! Form::text('start_date',Request::input('start_date',null),['class' => 'form-control datepicker','placeholder' => '-Select Date Start-']) !!}
                                        </div>
                                    </div> 
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="form-group">
                                            <label>
                                                <span class="dd-till"> - </span>
                                            </label>
                                            {!! Form::text('end_date',Request::input('end_date',null),['class' => 'form-control datepicker m-t-5','placeholder' => '-Select Date End-']) !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Search Transaction (<small>By Tr. Number, Contact Name, Contact Email...</small>)</label>
                                            {!! Form::text('q',Request::input('q',null),['class' => 'form-control','placeholder' =>'Start Typing Here']) !!}
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-sm-2 col-xs-6">
                                <div class="button-search">
                                    <button type="submit" class="btn btn-success waves-effect"><i class="material-icons">search</i></button>
                                    <a href="{{url('/transaction')}}" class="btn btn-warning waves-effect" on="hide"><i class="material-icons">replay</i></a>
                                </div>
                            </div>                                 

                        </form>
                    </div>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-modif">
                            <thead>
                                <tr>
                                    <th>
                                        @if (Request::input('sort') == 'transaction_number')
                                            <a href="{!! Request::input('sort_tr_number') !!}" >
                                                Transaction Number @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                            </a>
                                        @else
                                            <a href="{!! Request::input('sort_tr_number') !!}">
                                                Transaction Number <i class="fa fa-fw fa-sort"></i>
                                            </a>
                                        @endif
                                    </th>
                                    <th>
                                        @if (Request::input('sort') == 'transaction_date')
                                            <a href="{!! Request::input('sort_tr_date') !!}" >
                                                TR. Date @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                            </a>
                                        @else
                                            <a href="{!! Request::input('sort_tr_date') !!}">
                                                TR. Date <i class="fa fa-fw fa-sort"></i>
                                            </a>
                                        @endif
                                    </th>
                                    <th>
                                        @if (Request::input('sort') == 'users.firstname')
                                            <a href="{!! Request::input('sort_ct_name') !!}" >
                                                Contact Name @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                            </a>
                                        @else
                                            <a href="{!! Request::input('sort_ct_name') !!}">
                                                Contact Name <i class="fa fa-fw fa-sort"></i>
                                            </a>
                                        @endif
                                    </th>
                                    <th>
                                        @if (Request::input('sort') == 'users.email')
                                            <a href="{!! Request::input('sort_ct_email') !!}" >
                                                Contact Email @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                            </a>
                                        @else
                                            <a href="{!! Request::input('sort_ct_email') !!}">
                                                Contact Email <i class="fa fa-fw fa-sort"></i>
                                            </a>
                                        @endif
                                    </th>
                                    <th>Voucher</th>
                                    {{-- <th>
                                        @if (Request::input('sort') == 'total_discount')
                                            <a href="{!! Request::input('sort_total_discount') !!}" >
                                                Total Discount @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                            </a>
                                        @else
                                            <a href="{!! Request::input('sort_total_discount') !!}">
                                                Total Discount <i class="fa fa-fw fa-sort"></i>
                                            </a>
                                        @endif
                                    </th> --}}
                                    <th>
                                        @if (Request::input('sort') == 'total_payment')
                                            <a href="{!! Request::input('sort_total_payment') !!}" >
                                                Total Payment @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                            </a>
                                        @else
                                            <a href="{!! Request::input('sort_total_payment') !!}">
                                                Total Payment <i class="fa fa-fw fa-sort"></i>
                                            </a>
                                        @endif
                                    </th>
                                    <th>Status</th>
                                    <th>Payment Method
                                    </th>
                                    <th>
                                        @if (Request::input('sort') == 'created_at')
                                            <a href="{!! Request::input('sort_created_at') !!}" >
                                                Created At @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                            </a>
                                        @else
                                            <a href="{!! Request::input('sort_created_at') !!}">
                                                Created At <i class="fa fa-fw fa-sort"></i>
                                            </a>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $dt)
                                <tr>
                                    <td><a href="/transaction/{{$dt->transaction_number}}" class="btn btn-primary">{{$dt->transaction_number}}</a></td>
                                    <td>
                                    @if(!empty($data->paid_at))
                                        {{date('d M Y H:i:s',strtotime($dt->paid_at))}}
                                    @else
                                        -
                                    @endif
                                    </td>
                                    <td>{{$dt->fullname}}</td>
                                    <td>{{$dt->email}}</td>
                                    <td>
                                        @if(!empty($dt->coupon_id))
                                        <span class="bg-green">{{$dt->coupon_code}}</span>
                                        @else
                                        <i class="material-icons font-16 bg-red">clear</i>
                                        @endif
                                    </td>
                                    {{-- <td>{{number_format($dt->total_discount)}}</td> --}}
                                    <td>{{Helpers::idr($dt->total_paid)}}</td>
                                    <td><span class="badge" style="background-color:{{$dt->status_color}}">{{$dt->status}}</span></td>
                                    <td>
                                        {{$dt->payment_method}}
                                    </td>
                                    <td>
                                        {{$dt->created_at}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <p>Displaying {{$data->perPage()}} of {{$data->total()}} transactions</p>
                            </div>
                            <div class="col-md-4 col-md-offset-2">
                                <div>{{$data->appends(['start_date' => Request::input('start_date'),'end_date' => Request::input('end_date'),'q' => Request::input('q'),'sort' => Request::input('sort'),'order' => Request::input('order')])->links('vendor.pagination.default-material')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stop
    @section('head-js')
    @parent
        <script src="{{asset('plugins/jquery-qtips/jquery.qtip.min.js')}}"></script>
        <script src="{{asset('plugins/momentjs/moment.js')}}"></script>
        <script src="{{asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
        <script type="text/javascript">
            $('.datepicker').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD  ',
                clearButton: true,
                weekStart: 1,
                time: false
            });

        </script>
    @stop
    