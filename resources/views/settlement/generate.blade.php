@extends ('layouts.app')
@section('head-css')
@parent
<!-- Date range picker -->
<link href="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
<style>
.settlement .col-md-12,.settlement .col-md-3{
    margin-top:10px;
}
</style>
@stop
@section('main-content')
			<div class="block-header">
                <h2>
                    Settlement
                    <small>Admin Data / Settlement</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Search all booking who want to processed</h2>
                            <ul class="header-dropdown">
                                <!-- <li>
                                    <button type="button" class="btn bg-deep-orange waves-effect" start="{{ date('d-m-Y')}}" end="{{ date('d-m-Y')}}" data-toggle="modal" data-target="#myModal" id="period">Generate Settlement Today</button>
                                </li> -->
                            </ul>
                        </div>
                        <div class="body settlement">
                            @include('errors.error_notification')
                            <div class="row">
                                {{ Form::open(['route'=>'settlement.filter', 'method'=>'POST','id'=>'search']) }}
                                    @csrf
                                    <div class="col-md-12">
                                        <label>Start Date</label>
                                        {{ Form::text('start', null, ['class' => 'form-control','placeholder'=>'yyyy-mm-dd','id'=>'start']) }}
                                    </div>
                                    <div class="col-md-12">
                                        <label>End Date</label>
                                        {{ Form::text('end', null, ['class' => 'form-control','placeholder'=>'yyyy-mm-dd','id'=>'end']) }}
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn bg-green waves-effect">
                                            <i class="material-icons">search</i>
                                            <span>Cari</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="{{url('settlement/procced')}}" id="proced">
                                @csrf
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            Notes : <textarea rows="5" class="form-control no-resize" name="notes"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-link waves-effect">PROCESS</button>
                                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($data != null)
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <a class="btn bg-deep-orange waves-effect" data-toggle="modal" data-target="#myModal" id="opener">Process</a>
                        </div>
                        @foreach($data as $key=>$d)
                        <div class="body">
                            <div class="card">
                                <div class="header">
                                    <a class="btn bg-green waves-effect">Product will start at : {{$key}}</a>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-modif table-bordered table-striped table-hover dataTable js-exportable" id="data-tables">
                                            <thead>
                                                <tr>
                                                    <th>Booking Number</th>
                                                    <th>Product Type</th>
                                                    <th>Product Name</th>
                                                    <th>Qty</th>
                                                    <th>Unit Price</th>
                                                    <th>Total Price</th>
                                                    <th>Total Commssion</th>
                                                    <th>Total Payment</th>
                                                    <th>Account Bank</th>
                                                    <th>Transaction Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($d) > 0)
                                                    @foreach(array_collapse($d) as $set)
                                                    <tr>
                                                        <td>{{$set['booking_number']}}</td>
                                                        <td>
                                                            @if(array_key_exists('hotel_name',$set))
                                                                Hotel
                                                            @elseif(array_key_exists('tour_name',$set))
                                                                Tour
                                                            @else
                                                                Car Rental
                                                            @endif

                                                        </td>
                                                        <td>
                                                            @if(array_key_exists('hotel_name',$set))
                                                                {{$set['hotel_name']}} - {{$set['room_name']}}
                                                            @elseif(array_key_exists('tour_name',$set))
                                                                {{$set['tour_name']}}
                                                            @else
                                                                {{$set['vehicle_name']}} - {{$set['vehicle_type']}} - {{$set['vehicle_brand']}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(array_key_exists('hotel_name',$set))
                                                                {{$set['number_of_rooms']}}
                                                            @elseif(array_key_exists('tour_name',$set))
                                                                {{$set['number_of_person']}}
                                                            @else
                                                                {{$set['number_of_day']}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(array_key_exists('hotel_name',$set))
                                                                {{Helpers::idr($set['price_per_night'])}}
                                                            @elseif(array_key_exists('tour_name',$set))
                                                                {{Helpers::idr($set['price_per_person'])}}
                                                            @else
                                                                {{Helpers::idr($set['price_per_day'])}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(array_key_exists('total_price',$set))
                                                                {{Helpers::idr($set['total_price'])}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(array_key_exists('commission',$set))
                                                                {{Helpers::idr($set['commission'])}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(array_key_exists('net_price',$set))
                                                                {{Helpers::idr($set['net_price'])}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(array_key_exists('tours',$set))
                                                                @if($set['tours']['company']['bank_account_number'] != null)
                                                                <button type="button" class="btn btn-primary btn-block waves-effect" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="left" title="" data-content="{{$set['tours']['company']['bank_name']}} : {{$set['tours']['company']['bank_account_number']}}" data-original-title="{{$set['tours']['company']['bank_account_name']}}">
                                                                    {{$set['tours']['company']['bank_account_number']}}
                                                                </button>
                                                                @endif
                                                            @endif 
                                                        </td>
                                                        <td>
                                                            {{$set['transactions']['paid_at']}}
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
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
@stop
@section('head-js')
@parent
<!-- Moment Plugin Js -->
<script src="{{ asset('plugins/momentjs/moment.js') }}"></script>
<!-- Bootstrap date range picker -->
<script src="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.js') }}"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
        var dateFormat = 'YYYY-MM-DD';
        var options = {
            autoUpdateInput: false,
            singleDatePicker: true,
            autoApply: true,
            locale: {
                format: dateFormat,
            },
            maxDate: moment().add(359, 'days'),
            opens: "right"
        };
        $('input#start').daterangepicker(options).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD')); 
            $("#opener").attr('start',picker.startDate.format('YYYY-MM-DD'));
            $('input#end').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            autoApply: true,
            locale: {
                format: dateFormat,
            },
            minDate: moment(picker.startDate.format('YYYY-MM-DD')),
            maxDate: moment().add(359, 'days'),
                opens: "right"
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
                $("#opener").attr('end',picker.startDate.format('YYYY-MM-DD'));
            });
        })
        $('input[name="end"]').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            autoApply: true,
            locale: {
                format: dateFormat,
            },
            maxDate: moment().add(359, 'days'),
            opens: "right"
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $("#opener").attr('end',picker.startDate.format('YYYY-MM-DD'));
        });
        $('#myModal').on('show.bs.modal', function (e) {

            //we get details from attributes
            var start=$("#opener").attr('start');
            var end=$("#opener").attr('end');

            //set what we got to our form
            $('form#filter').find("input[name='start']").val(start);
            $('form#filter').find("input[name='end']").val(end);
        });
    });
</script>
@stop