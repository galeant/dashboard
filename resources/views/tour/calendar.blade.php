@extends ('layouts.app')
@section('head-css')
@parent
    <!-- Tel Input -->
    <link href="{{ asset('plugins/telformat/css/intlTelInput.css') }}" rel="stylesheet">
    <!-- Bootstrap Select2 -->
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <!-- Date range picker -->

    <link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />
    <link href="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/cropper/cropper.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap-file-input/css/fileinput.css') }}" rel="stylesheet">

    <link href='{{asset("plugins/fullcalendar/fullcalendar.min.css")}}' rel='stylesheet' />
    <link href='{{asset("plugins/fullcalendar/fullcalendar.print.min.css")}}' rel='stylesheet' media='print' />

    <style type="text/css">
        .intl-tel-input{
            width: 100%;
            top:5px;
            margin-bottom: 10px;
        }
        #calendar{
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
@stop

@section('main-content')
<div class="block-header">
    <h2>
        Schedule Tour Activity
        <small>Master Data / Tour Activity / {{$data->product_name}} / Off Day</small>
    </h2>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Off Day {{$data->product_name}}</h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="/product/tour-activity/{{$data->id}}/edit" class="btn btn-default btn-sm btn-waves">Back</a>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>
@stop
@section('head-js')
@parent

 <!-- Moment Plugin Js -->
<script src="{{ asset('plugins/momentjs/moment.js') }}"></script>
<script src='{{asset("plugins/fullcalendar/fullcalendar.min.js")}}'></script>


    <script src="{{asset('plugins/sweetalert/sweetalertnew.min.js')}}"></script>
 <!-- Bootstrap date range picker -->
<script src="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.js') }}"></script>
 <!-- Mask js -->
<script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
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
          events: '/product/tour-activity/{{$data->id}}/off-day',
          dayClick: function(date, jsEvent, view) {
            var me = $(this);
            $.ajax({
                method: "GET",
                url: "{{ url('product/tour-activity/'.$data->id.'/off-day/check') }}",
                data: {
                        date : date.format()
                        }
            }).done(function(response){
                if(response == true){
                    swal({
                        title: "Are you sure?",
                        text: "You will delete this off day",
                        dangerMode: true,
                        buttons: {
                            cancel: true,
                            confirm: true,
                        },
                        closeOnEsc: false,
                        closeOnClickOutside: false
                    }).then(function (isConfirm) {
                        if(isConfirm){
                            $.ajax({
                                method: "POST",
                                url: "{{ url('product/tour-activity/'.$data->id.'/off-day/save') }}",
                                data: {
                                        date : date.format(),
                                        action : 'delete'
                                        }
                            }).done(function(response){
                                swal("Success", 'The Off-Day was deleted!', "success"); 
                                location.reload();
                            }).error(function(xhr, ajaxOptions, thrownError){
                                swal("Error", xhr.responseJSON.message, "error");
                            });
                        }
                    });
                }
                else{
                    swal({
                        title: "Are you sure?",
                        text: "You will add this off day",
                        dangerMode: true,
                        buttons: {
                            cancel: true,
                            confirm: true,
                        },
                        closeOnEsc: false,
                        closeOnClickOutside: false
                    }).then(function (isConfirm) {
                        if(isConfirm){
                            $.ajax({
                                method: "POST",
                                url: "{{ url('product/tour-activity/'.$data->id.'/off-day/save') }}",
                                data: {
                                        date : date.format(),
                                        action : 'add'
                                      }
                            }).done(function(response){
                                me.css('background-color','#ffb7ba');
                                swal("Success", 'The Off-Day was created!', "success"); 
                            }).error(function(xhr, ajaxOptions, thrownError){
                                swal("Error", xhr.responseJSON.message, "error");
                            });
                        }
                    });
                }
            }).error(function(xhr, ajaxOptions, thrownError){
                swal("Error", xhr.responseJSON.message, "error");
            });
          }
        });
</script>
@stop
