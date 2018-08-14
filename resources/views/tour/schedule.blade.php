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
    <link href='{{asset("plugins/fullcalendar/fullcalendar.min.css")}}' rel='stylesheet' />
    <link href='{{asset("plugins/fullcalendar/fullcalendar.print.min.css")}}' rel='stylesheet' media='print' />
    <!-- Sweetalert Css -->
    <link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/jquery-qtips/jquery.qtip.min.css')}}" rel="stylesheet"/>
    <style type="text/css">
        .intl-tel-input{
            width: 100%;
            top:5px;
            margin-bottom: 10px;
        }
        #calendar{
            max-width: 1200px;
            margin: 0 auto;
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
                        <a href="/product/tour-activity/{{$data->id}}/edit" class="btn btn-default btn-sm btn-waves">Back</a>
                    </li>
                </ul>
            </div>
            <div class="body">
                @include('errors.error_notification')
                <div id='calendar' data-interval="{{$data->schedule_interval}}" data-type="{{$data->schedule_type}}" data-id="{{$data->id}}"></div>
            </div>
        </div>
    </div>
</div>
<!-- Default Size -->
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-schedule">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel"></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="">
                    <div class="form-group form-float">
                        <label class="form-label">Start Date *</label>
                        <input type="text" class="form-control start-date" name="start_date" placeholder="DD-MM-YYYY" value="" required disabled/>
                    </div>
                    <div class="form-group form-float">
                        <label class="form-label">End Date *</label>
                        <input type="text" class="form-control end-date" name="end_date" placeholder="DD-MM-YYYY" value="" required disabled/>
                    </div>
                    <div class="form-group form-float">
                        <label>Start hours *</label>
                        <input type="text" class="form-control start-hours" name="start_hours" placeholder="HH:mm:ss" required/>
                    </div>
                    <div class="form-group form-float">
                        <label>End hours</label>
                        <input type="text" class="form-control end-hours" name="end_hours" placeholder="HH:mm:ss" readonly/>
                    </div>
                    <div class="form-group form-float">
                        <label class="form-label">Maximum booking / trip</label>
                        <input type="number" class="form-control maximum-booking" name="maximum_booking" min=1 required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-link waves-effect">SAVE CHANGES</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="add-schedule">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Add New Schedule</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="">
                    <div class="form-group form-float">
                        <label class="form-label">Start Date *</label>
                        <input type="text" class="form-control start-date" name="start_date" placeholder="DD-MM-YYYY" value="" required disabled/>
                    </div>
                    <div class="form-group form-float">
                        <label class="form-label">End Date *</label>
                        <input type="text" class="form-control end-date" name="end_date" placeholder="DD-MM-YYYY" value="" required disabled/>
                    </div>
                    <div class="form-group form-float">
                        <label>Start hours *</label>
                        <input type="text" class="form-control start-hours" name="start_hours" placeholder="HH:mm" required/>
                    </div>
                    <div class="form-group form-float">
                        <label>End hours</label>
                        <input type="text" class="form-control end-hours" name="end_hours" placeholder="HH:mm" readonly/>
                    </div>
                    <div class="form-group form-float">
                        <label class="form-label">Maximum booking / trip</label>
                        <input type="number" class="form-control maximum-booking" name="maximum_booking" min=1 required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-link waves-effect">SAVE CHANGES</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('head-js')
@parent
 <!-- Moment Plugin Js -->
 <script src="{{ asset('plugins/momentjs/moment.js') }}"></script>

<script src='{{asset("plugins/fullcalendar/fullcalendar.min.js")}}'></script>
 <!-- Bootstrap date range picker -->
 <script src="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.js') }}"></script>
 <!-- Mask js -->
 <script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script>

    <script src="{{asset('plugins/sweetalert/sweetalertnew.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-qtips/jquery.qtip.min.js')}}"></script>
<script>
    var eventOnClicked;
    $("body").keydown(function(e) {
      if(e.keyCode == 37) { // left
        $('.fc-prev-button').click();
      }
      else if(e.keyCode == 39) { // right
        $('.fc-next-button').click();
      }
    });
    $(document).ready(function(){
        // 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".start-hours,.end-hours").mask('00:00');

        $("td").each(function(){
            $(this).find('input,a#update,a#cancel').hide();
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
            $(".maximum-booking").attr("readonly","readonly").val(1);
        }else{
            $(".maximum-booking").val(1);
        }
        if(scheType == 1){
            $('.start-hours,.end-hours').parent().remove();
        }else if(scheType == 2){
            $('.start-date,.end-date').parent().remove();
        }else{
            $('.start-hours,.end-hours').parent().remove();
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
        
        $('.start-hours').change(function(){
            if($(this).val().length == 5){
                var choose = $(this).val();
                var newtime = new Date(moment(choose,['h:m:a','H:m']));
                newtime.setHours(newtime.getHours()+parseInt(hours));
                newtime.setMinutes(newtime.getMinutes()+parseInt(minutes));
                var hour = (newtime.getHours() < 10 ? '0' : '') + newtime.getHours();
                var minute = (newtime.getMinutes() < 10 ? '0' : '') + newtime.getMinutes();
                $('.end-hours').val(hour+":"+minute);
            }
        });
        
        $('#form-schedule').submit(function(e){
            // console.log()
            e.preventDefault();
            if(scheType == 1 || scheType == 3){
                data = {
                    'maximum_booking':$('#form-schedule .maximum-booking').val(),
                    'start_date': $('#form-schedule .start-date').val(),
                    'end_date':$('#form-schedule .end-date').val(),
                    'id':$('#form-schedule input[name=id]').val()
                };
            }else{
                data = {
                    'start_date': $('#form-schedule .start-hours').attr('start-date'),
                    'maximum_booking':$('#form-schedule .maximum-booking').val(),
                    'start_hours': $('#form-schedule .start-hours').val(),
                    'end_hours':$('#form-schedule .end-hours').val(),
                    'id':$('#form-schedule input[name=id]').val()
                };
            }
            $.ajax({
                method: "POST",
                url: "{{ url('product/tour-activity/schedule/update') }}",
                data: data
            }).done(function(response){
                eventOnClicked.max_booking = response.data.max_booking;
                eventOnClicked.description = response.data.description;
                $('#calendar').fullCalendar('updateEvent', eventOnClicked);
                $('#defaultModal').modal('hide');
                swal("Success", 'The Schedule was updated!', "success");   
            }).error(function(xhr, ajaxOptions, thrownError){
                swal("Error", xhr.responseJSON.message, "error");
            });
        });
        $('#add-schedule').submit(function(e){
            e.preventDefault();
            var id = $('#calendar').attr('data-id');
            var type = $('#calendar').attr('data-type');
            var data;
            if(type == 1 || type == 3){
                data = {
                    'maximum_booking':$('#add-schedule .maximum-booking').val(),
                    'start_date': $('#add-schedule .start-date').val(),
                    'end_date':$('#add-schedule .end-date').val()
                };
            }else{
                data = {
                    'start_date': $('#add-schedule .start-hours').attr('start-date'),
                    'maximum_booking':$('#add-schedule .maximum-booking').val(),
                    'start_hours': $('#add-schedule .start-hours').val(),
                    'end_hours':$('#add-schedule .end-hours').val()
                };

            }
            $.ajax({
                method: "POST",
                url: "{{ url('product/tour-activity/'.$data->id.'/'.$data->schedule_type.'/schedule/save') }}",
                data: data
            }).done(function(response){
                $('#addModal').modal('hide');
                swal("Success", 'The Schedule was created!', "success");
                $('#calendar').fullCalendar( 'renderEvent', response, false);
            }).error(function(xhr, ajaxOptions, thrownError){
                $('#addModal').modal('hide');
                swal("Error", xhr.responseJSON.error, "error");
            });
        });
        $(document).delegate('.btn-delete', 'click', function(e){
            e.preventDefault();
            var thisElement = $(this);
            console.log(thisElement);
            swal({
                title: "Are you sure?",
                text: "Delete this schedule",
                dangerMode: true,
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                closeOnEsc: false,
                closeOnClickOutside: false
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        method: "POST",
                        url: "/product/tour-activity/schedule/"+thisElement.attr('data-id')+"/delete",
                        data: {id:thisElement.attr('data-id')}
                    }).done(function(response){
                        $('#addModal').modal('hide');
                        swal("Success", 'The Schedule was deleted!', "success");
                        $('#calendar').fullCalendar( 'renderEvent', response, false);
                        $('#calendar').fullCalendar('removeEvents',thisElement.attr('data-event-id'));
                    }).error(function(xhr, ajaxOptions, thrownError){
                        $('#addModal').modal('hide');
                        swal("Error", xhr.responseJSON.error, "error");
                    });
                    $('.schedule-'+thisElement.attr('data-id')).remove();
                    
                }
            });
        });
    });
    
    $(document).ready(function() {
    @if($data->always_available_for_sale == 1)
        $('#calendar').fullCalendar({
          eventClick: function(event) {
           
          },
          dayClick: function(date, jsEvent, view) {
            showAjaxLoaderMessage();
            $(this).css('background-color', 'red');

          }
        });
        function showAjaxLoaderMessage() {
            swal({
                title: "Ajax request example",
                text: "Submit to run ajax request",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                console.log('here');
            });
        }
    @else
        $('#calendar').fullCalendar({
          header: {
            left: 'today',
            center: 'title',
            right: 'prev,month,next'
          },
          axisFormat: 'HH:mm',
           timeFormat: 'HH:mm',
           minTime: 0,
           maxTime: 24,  
          events: '/product/tour-activity/{{$data->id}}/schedule',
          eventClick: function(event) {
            $('#form-schedule .start-date').val(event.start.format('YYYY-MM-DD'));
            $('#form-schedule .end-date').val(event.end_date);
            $('#form-schedule .start-hours').val(event.start_hours);
            $('#form-schedule .end-hours').val(event.end_hours);
            $('#form-schedule .maximum-booking').val(event.max_booking);
            $('#form-schedule input[name=id]').val(event.id);
            $('#form-schedule .start-hours').attr('start-date',event.start.format('YYYY-MM-DD'));
            $('#defaultModal').modal('show');
            eventOnClicked = event;
          },
          editable: true,
          eventDragStart: function(){
            $('.qtip').hide();
          },
          eventDrop: function(event, delta, revertFunc) {
            swal({
                title: "Are you sure?",
                text: "You will change the schedule!",
                dangerMode: true,
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                closeOnEsc: false,
                closeOnClickOutside: false
            }).then(function (isConfirm) {
                if (isConfirm) {
                    if(event.end == null){
                        event.end = event.start;
                    }
                    var data ={
                        'maximum_booking':event.max_booking,
                        'start_date': event.start.format(),
                        'end_date': event.end.subtract(1, 'days').format(),
                        'start_hours': event.start_hours,
                        'end_hours': event.end_hours,
                        'id':event.id
                    };
                    $.ajax({
                        method: "POST",
                        url: "{{ url('product/tour-activity/schedule/update') }}",
                        data: data
                    }).done(function(response){
                        $('#calendar').fullCalendar('removeEvents',event._id);
                        $('#calendar').fullCalendar( 'renderEvent', response.data, true);
                        swal("Success", 'The Schedule was Changed', "success");   
                    }).error(function(xhr, ajaxOptions, thrownError){
                        swal("Error", xhr.responseJSON.message, "error");
                        revertFunc();
                    });
                } else {
                    revertFunc();
                }
            });
          },
          dayClick: function(date, jsEvent, view) {
            @if($data->schedule_type == 1)
                $('#addModal .start-date').val(date.format());
                $('#addModal .end-date').val(date.add($('#calendar').attr('data-interval')-1,'days').format());
            @elseif($data->schedule_type == 2)
                $('#addModal .start-hours').attr('start-date',date.format());
            @else
                $('#addModal .start-date').val(date.format());
                $('#addModal .end-date').val(date.add($('#calendar').attr('data-interval')-1,'days').format());
            @endif
            $('#addModal').modal('show');
          },
          eventRender: function(event, element) {
            element.find('.fc-title').append(' <span class="badge bg-clay">'+event.booked+'</span> ');
            element.addClass('schedule-'+event.id);
            // console.log(event);
            element.qtip({
                content: {    
                    title: { text: event.title+' <span class="badge bg-clay">'+event.booked+'</span><a class="badge bg-red btn-delete" data-id="'+event.id+'" data-event-id='+event._id+'>X</a>' },
                    text: event.description       
                },
                hide: { 
                    fixed: true,
                    effect: function() {
                        $(this).slideUp();
                    } 
                },
                show: { solo: true,
                    effect: function() {
                        $(this).slideDown();
                    } 
                },
                position: {
                   // target: 'mouse', // Position at the mouse...
                   // adjust: { mouse: false } // ...but don't follow it!
                   my: 'top left',  // Position my top left...
                   at: 'bottom left', // at the bottom right of...
                   target: element // my target

                },
                style: 'low-zindex'
            });
          }
        });
        
    @endif
    });
</script>
@stop
