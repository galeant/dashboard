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
                                <div class="col-md-12">
                                    <label>Start Date</label>
                                    <input type="text" class="form-control" id="start"/>
                                </div>
                                <div class="col-md-12">
                                    <label>End Date</label>
                                    <input type="text" class="form-control" id="end"/>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn bg-green waves-effect" data-toggle="modal" data-target="#myModal" id="opener">
                                        <i class="material-icons">search</i>
                                        <span>Cari</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="{{url('settlement/filter')}}" id="filter">
                                @csrf
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <input type="hidden" name="start" value=""/>
                                            <input type="hidden" name="end" value=""/>
                                            Notes : <textarea rows="5" class="form-control no-resize" name="notes"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-link waves-effect">Proced</button>
                                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@stop
@section('head-js')
@parent
<!-- Moment Plugin Js -->
<script src="{{ asset('plugins/momentjs/moment.js') }}"></script>
<!-- Bootstrap date range picker -->
<script src="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.js') }}"></script>
<script>
    $(document).ready(function(){
        var dateFormat = 'DD-MM-YYYY';
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
            $(this).val(picker.startDate.format('DD-MM-YYYY')); 
            $("#opener").attr('start',picker.startDate.format('DD-MM-YYYY'));
            $('input#end').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            autoApply: true,
            locale: {
                format: dateFormat,
            },
            minDate: moment(picker.startDate.format('MM-DD-YYYY')),
            maxDate: moment().add(359, 'days'),
                opens: "right"
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY'));
                $("#opener").attr('end',picker.startDate.format('DD-MM-YYYY'));
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
            $(this).val(picker.startDate.format('DD-MM-YYYY'));
            $("#opener").attr('end',picker.startDate.format('DD-MM-YYYY'));
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