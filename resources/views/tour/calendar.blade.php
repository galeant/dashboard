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
        <small>Master Data / Tour Activity / {{$data->product_name}} / Calendar</small>
    </h2>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Schedule {{$data->product_name}}</h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="/product/tour-activity/{{$data->id}}/schedule"  class="btn btn-sm bg-red waves-effect">
                            <i class="material-icons">list</i>
                        </a>
                    </li>
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

    <script src="{{asset('plugins/sweetalert/sweetalert.min.js')}}"></script>
 <!-- Bootstrap date range picker -->
<script src="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.js') }}"></script>
 <!-- Mask js -->
<script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
          events: [
            {
              title: 'My Event',
              start: '2018-08-07'
            }
            // other events here
          ],
          eventClick: function(event) {
            console.log('here');
          },
          dayClick: function(date, jsEvent, view) {
            showAjaxLoaderMessage();
            // alert('Clicked on: ' + date.format());

            // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

            // alert('Current view: ' + view.name);

            // change the day's background color just for fun
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
                setTimeout(function () {
                    swal("Ajax request finished!");
                }, 2000);
            });
        }

    });
</script>
@stop
