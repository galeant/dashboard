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
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Search all booking who want to processed</h2>
                            
                        </div>
                        <div class="body settlement">
                            @include('errors.error_notification')
                            <div class="row">
                                <form method="POST" action="{{url('settlement/filter')}}">
                                @csrf
                                    <div class="col-md-12">
                                        <label>Start Date</label>
                                        <input type="text" class="form-control" name="start"/>
                                    </div>
                                    <div class="col-md-12">
                                        <label>End Date</label>
                                        <input type="text" class="form-control" name="end"/>
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
        $('input[name="start"]').daterangepicker(options).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY')); 
            $('input[name="end"]').daterangepicker({
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
        });
    });
</script>
@stop