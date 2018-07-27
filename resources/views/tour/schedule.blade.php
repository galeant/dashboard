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
                <img style="margin-left: auto;
    margin-right: auto; width: 50%; display: block;" src="https://allridepowersports.com/wp-content/uploads/2018/02/Under-Construction-Sign-1024x483.png">
            </div>
        </div>
    </div>
</div>
@stop
@section('head-js')
@parent
@stop
