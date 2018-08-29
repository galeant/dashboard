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
                @if($data->status != 1)
                    <a id="export" href="{{ url('settlement/excel/'.$data->id) }}" class="btn bg-deep-orange waves-effect">Generate Excel</a>
                    <a id="export" href="{{ url('settlement/pdf/'.$data->id) }}" class="btn bg-orange waves-effect">Generate PDF</a>
                @endif
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
                                        @if($data['complete'] == 1)
                                            <form method="POST" action="{{ url('settlement/paid') }}" id="paid">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$data->id}}"/>
                                                <a id="paid" class="btn bg-teal btn-block waves-effect">Paid</a>
                                            <form>
                                        @else
                                            <a class="btn bg-red btn-block waves-effect">Data Not Complete</a>
                                        @endif
                                    @else
                                        <a class="btn bg-deep-orange btn-block waves-effect">Settled</a>
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
                                            @if($data->status != 1)
                                                <button type="button" class="btn btn-primary btn-block waves-effect" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="left" title="" data-content="{{$set->bank_name}} : {{$set->bank_account_number}}" data-original-title="{{$set->bank_account_name}}">
                                                    {{$set->bank_account_number}}
                                                </button>
                                            @else
                                                @if($set->bank_account_number != null)
                                                <button class="btn btn-primary" data-id="{{$set->id}}" bank-name="{{$set->bank_name}}" bank-account-name="{{$set->bank_account_name}}" bank-number="{{$set->bank_account_number}}" data-toggle="modal" data-target="#myModal">
                                                    {{$set->bank_account_number}}
                                                </button>
                                                @else
                                                <button class="btn bg-red" data-id="{{$set->id}}" data-toggle="modal" data-target="#myModal">
                                                    Add Account Bank
                                                </button>
                                                @endif     
                                            @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                 <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <form id="notesForm" method="POST" action="{{ url('settlement/update-bank') }}">
                                        @csrf
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value=""/>
                                                    <!-- Bank Name : <input type="text" class="form-control" name="bank_name"> -->
                                                    Bank Name : 
                                                    <select class="form-control" name="bank_name">
                                                        <option value="BCA">BCA</option>
                                                        <option value="Mandiri">Mandiri</option>
                                                        <option value="CIMB">CIMB</option>
                                                    <select>
                                                    Bank Account Name : <input type="text" class="form-control" name="bank_account_name">
                                                    Bank Account Number : <input type="text" class="form-control" name="bank_account_number">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-link waves-effect">SAVE CHANGES</button>
                                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                                </div>
                                            </div>
                                        </form>
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
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>
    <!-- Mask js -->
    <script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script> 
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
        $('#myModal').on('show.bs.modal', function (e) {
            // get information to update quickly to modal view as loading begins
            var opener=e.relatedTarget;//this holds the element who called the modal

            //we get details from attributes
            var id=$(opener).attr('data-id');
            var bank_name=$(opener).attr('bank-name');
            var bank_account_name=$(opener).attr('bank-account-name');
            var bank_number=$(opener).attr('bank-number');
            console.log(id);
            console.log(bank_name);
            console.log(bank_account_name);
            console.log(bank_number);
            // //set what we got to our form
            $('#notesForm').find('[name="id"]').val(id);
            $('#notesForm').find('[name="bank_name"]').val(bank_name);
            $('#notesForm').find('[name="bank_account_name"]').val(bank_account_name);
            $('#notesForm').find('[name="bank_account_number"]').val(bank_number).mask('0000000000000000');

        });
        
    });
   </script>
@stop
