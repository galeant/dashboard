@extends ('layouts.app')
@section('head-css')
@parent
    <!-- Tel Input -->
    <link href="{{ asset('plugins/telformat/css/intlTelInput.css') }}" rel="stylesheet">
    <!-- Bootstrap Select2 -->
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <!-- Date range picker -->
    <link href="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/cropper/cropper.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap-file-input/css/fileinput.css') }}" rel="stylesheet">
    <style type="text/css">
        .intl-tel-input{
            width: 100%;
            top:5px;
            margin-bottom: 10px;
        }
    </style>
@stop

@section('main-content')
<div class="block-header">
    <h2>
        Schedule Tour Activity
        <small>Master Data / Tour Activity / {{$data->product_name}} / Schedule</small>
    </h2>
</div>
<!-- Basic Example | Horizontal Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Schedule {{$data->product_name}}</h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="/product/tour-activity/{{$data->id}}/edit" class="btn btn-waves">Back</a>
                    </li>
                </ul>
            </div>
            <div class="body">
            @include('errors.error_notification')
            <form method="POST" action="{{ url('product/tour-activity/'.$data->id.'/'.$data->schedule_type.'/schedule/save') }}">
            @csrf
                <div id="schedule_body">
                    <div class="row" id="value" style="margin: 0px 3px 0px 3px;">
                        <div class="col-md-2 valid-info" id="scheduleCol1">
                            <h5>Start date*</h5>
                            <input type="text" id="scheduleField1" class="form-control" name="start_date" placeholder="DD-MM-YYYY" required/>
                        </div>
                        <div class="col-md-2 valid-info" id="scheduleCol2">
                            <h5>End date</h5>
                            <input type="text" id="scheduleField2" class="form-control" name="end_date" placeholder="DD-MM-YYYY"  readonly/>
                        </div>
                        <div class="col-md-2 valid-info" id="scheduleCol3">
                            <h5>Start hours *</h5>
                            <input type="text" id="scheduleField3" class="form-control" name="start_hours" placeholder="HH:mm:ss" required/>
                        </div>
                        <div class="col-md-2 valid-info" id="scheduleCol4">
                            <h5>End hours</h5>
                            <input type="text" id="scheduleField4" class="form-control" name="end_hours" placeholder="HH:mm:ss"  readonly/>
                        </div>
                        <div class="col-md-2 valid-info" id="scheduleCol5">
                            <h5>Max.Booking Date*</h5>
                            <input type="text" id="scheduleField5" class="form-control" name="max_booking_date_time" placeholder="DD-MM-YYYY" required/>
                        </div>
                        <div class="col-md-2 valid-info" id="scheduleCol6">
                            <h5>Max.Booking*</h5>
                            <input type="text" id="scheduleField6" class="form-control" name="maximum_booking" required/>
                        </div>
                        <div class="col-md-2 valid-info" id="scheduleCol7" style="margin-top:30px">
                            <button type="submit" class="btn btn-warning" style="outline:none;">
                                <i class="fa fa-plus"></i>
                                &nbsp;Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
                <div class="body table-responsive">
                <form method="POST" action="{{ url('product/tour-activity/schedule/'.$data->id.'/update') }}">
                @csrf
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th id="startDate">Start Date</th>
                                <th id="endDate">End Date</th>
                                <th id="startHours">Start Hours</th>
                                <th id="endHours">End Hours</th>
                                <th id="maxBookDate">Max Booking Date</th>
                                <th id="book">Number Of Booking</th>
                                <th id="maxBook">Max Booking</th>
                                <th id="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data->schedules) != 0)
                                @foreach($data->schedules as $schedule)
                                <tr id="value" data-id="{{$schedule->id}}">
                                    <td id="startDate">
                                        <p>{{date('d-m-Y',strtotime($schedule->start_date))}}</p>
                                        <input type="text" id="scheduleField1" class="form-control" name="start_date" value="{{date('d-m-Y',strtotime($schedule->start_date))}}"/>
                                    </td>
                                    <td id="endDate">
                                        <p>{{date('d-m-Y',strtotime($schedule->end_date))}}</p>
                                        <input type="text" id="scheduleField2" class="form-control" name="end_date" value="{{date('d-m-Y',strtotime($schedule->end_date))}}"  readonly/>
                                        
                                    </td>
                                    <td id="startHours">
                                        <p>{{$schedule->start_hours}}</p>
                                        <input type="text" id="scheduleField3" class="form-control" name="start_hours" value="{{$schedule->start_hours}}"/>
                                    </td>
                                    <td id="endHours">
                                        <p>{{$schedule->end_hours}}</p>
                                        <input type="text" id="scheduleField4" class="form-control" name="end_hours" value="{{$schedule->end_hours}}"  readonly/>
                                    </td>
                                    <td id="maxBookDate">
                                        <p>{{date('d-m-Y',strtotime($schedule->max_booking_date_time))}}</p>
                                        <input type="text" id="scheduleField5" class="form-control" name="max_booking_date_time" value="{{date('d-m-Y',strtotime($schedule->max_booking_date_time))}}"/>
                                    </td>
                                    <td id="book">
                                        0
                                    </td>
                                    <td id="maxBook">
                                        <p>{{$schedule->maximum_booking}}</p>
                                        <input type="text" id="scheduleField6" class="form-control" name="maximum_booking" value="{{$schedule->maximum_booking}}"/>
                                    </td>
                                    <td id="action">    
                                        <a href="#" id="update"><i class="material-icons">save</i></a>
                                        <a href="#" id="edit"><i class="material-icons">mode_edit</i></a>
                                        <a href="{{ url('product/tour-activity/schedule/'.$schedule->id.'/delete') }}"><i class="material-icons">delete</i></a>
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
 <!-- Moment Plugin Js -->
 <script src="{{ asset('plugins/momentjs/moment.js') }}"></script>
 <!-- Bootstrap date range picker -->
 <script src="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.js') }}"></script>
 <!-- Mask js -->
 <script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script>
<script>
    $(document).ready(function(){
        // 
        $("#scheduleField3,#scheduleField4").mask('00:00');
        $("td").each(function(){
            $(this).find('input,a#update').hide();
        });
        var proType = '{{$data->product_type}}';
        var maxPep = '{{$data->max_person}}';
        var scheType = '{{$data->schedule_type}}';
        var interval,time,hour,minutes;
        if(scheType == 2){
            interval = '{{$data->schedule_interval}}';
            time = interval.split(':');
            hours = time[0];
            minutes = time[1];
        }else{
            interval = '{{$data->schedule_interval}}';
        }
        
        if(proType != 'private'){
            $("input#scheduleField6").attr("readonly","readonly").val(maxPep);
        }
        if(scheType == 1){
            $("#scheduleCol3,#scheduleCol4").remove();
            $("th#startHours,th#endHours").each(function(){
                $(this).remove();
            });
            $("td#startHours,td#endHours").each(function(){
                $(this).remove();
            });
        }else if(scheType == 2){
            $("#scheduleCol2").remove();
            $("th#endDate").each(function(){
                $(this).remove();
            });
            $("td#endDate").each(function(){
                $(this).remove();
            });
        }else{
            $("#scheduleCol2,#scheduleCol3,#scheduleCol4").remove();
            $("th#endDate,th#startHours,th#endHours").each(function(){
                $(this).remove();
            });
            $("td#endDate,td#startHours,td#endHours").each(function(){
                $(this).remove();
            });
        }
        // 
        var dateFormat = 'DD-MM-YYYY';
        var options = {
            autoUpdateInput: false,
            singleDatePicker: true,
            autoApply: true,
            locale: {
                format: dateFormat,
            },
            minDate: moment().add(1, 'days'),
            maxDate: moment().add(359, 'days'),
            opens: "right"
        };
        $("input#scheduleField1").each(function(){
            $(this).daterangepicker(options).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY'));
                $(this).closest("#value").find("#scheduleField5").daterangepicker({
                    autoUpdateInput: false,
                    singleDatePicker: true,
                    autoApply: true,
                    opens: "left",
                    locale: {
                        format: 'DD-MM-YYYY',
                    },
                    minDate: moment().add(0, 'days'),
                    maxDate: picker.startDate.format('DD-MM-YYYY')
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD-MM-YYYY'));
                });
                var newdate = new Date(picker.startDate.format('YYYY-MM-DD'));
                var scheduledays = interval-1;
                newdate.setDate(newdate.getDate()+parseInt(scheduledays))
                    var datee = (newdate.getDate() < 10 ? '0' : '') + newdate.getDate();
                    var month = ((newdate.getMonth() + 1) < 10 ? '0' : '') + (newdate.getMonth() + 1);
                    var year = newdate.getFullYear();
                $(this).closest("#value").find("#scheduleField2").val(datee+"-"+month+"-"+year);
            });
        });
        $("input#scheduleField3").each(function(){
            $(this).change(function(){
                var choose = $(this).val();
                var newtime = new Date(moment(choose,['h:m:a','H:m']));
                newtime.setHours(newtime.getHours()+parseInt(hours));
                newtime.setMinutes(newtime.getMinutes()+parseInt(minutes));
                var hour = (newtime.getHours() < 10 ? '0' : '') + newtime.getHours();
                var minute = (newtime.getMinutes() < 10 ? '0' : '') + newtime.getMinutes();
                $(this).closest("#value").find("input#scheduleField4").val(hour+":"+minute);
            });
        });

        $("td#action").each(function(){
            $(this).find("a#edit").click(function(){
                var me = $(this).closest("td#action");
                me.find("a").hide();
                me.find("a#update").show().click(function(){
                    var id = me.closest("tr#value").attr('data-id');
                    var start_date = me.closest("tr#value").find("input[name='start_date']").val();
                    var end_date =  me.closest("tr#value").find("input[name='end_date']").val();
                    var start_hours = me.closest("tr#value").find("input[name='start_hours']").val();
                    var end_hours = me.closest("tr#value").find("input[name='end_hours']").val();
                    var max_booking_date_time = me.closest("tr#value").find("input[name='max_booking_date_time']").val();
                    var maximum_booking = me.closest("tr#value").find("input[name='maximum_booking']").val();
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        method: "POST",
                        url: "{{ url('product/tour-activity/schedule/update') }}",
                        data: { 
                            id: id,
                            start_date: start_date,
                            end_date: end_date,
                            start_hours: start_hours, 
                            end_hours: end_hours, 
                            max_booking_date_time: max_booking_date_time, 
                            maximum_booking: maximum_booking
                        }
                    }).done(function(response) {
                        location. reload();
                    });
                });
                me.closest("tr#value").find("p").hide();
                me.closest("tr#value").find("input").show();
            });
        });
        $("td").find("#scheduleField5").each(function(){
            var maxBookLocal = $(this).closest("td").siblings("td#startDate").find("#scheduleField1").val();
            $(this).daterangepicker({
                autoUpdateInput: false,
                singleDatePicker: true,
                autoApply: true,
                opens: "left",
                locale: {
                    format: 'DD-MM-YYYY',
                },
                minDate: moment().add(0, 'days'),
                maxDate: maxBookLocal
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY'));
            });
        });
       
    });
</script>
@stop
