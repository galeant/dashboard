@extends ('layouts.app')
@section('head-css')
@parent
    <!-- Bootstrap File-Input -->
    <link href="{{ asset('plugins/bootstrap-file-input/css/fileinput.css') }}" rel="stylesheet" media="all">
    <!-- Tel Input -->
    <link href="{{ asset('plugins/telformat/css/intlTelInput.css') }}" rel="stylesheet">
    <!-- Bootstrap Select2 -->
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <!-- Date range picker -->
    <link href="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/cropper/cropper.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap-file-input/css/fileinput.css') }}" rel="stylesheet">
    <!-- Light Gallery Plugin Css -->
    <link href="{{asset('plugins/light-gallery/css/lightgallery.css')}}" rel="stylesheet">
    <style type="text/css">
        .intl-tel-input{
            width: 100%;
            top:5px;
            margin-bottom: 10px;
        }
        a#status{
            float:right;
            margin:0px 5px;
        }
        #head_card{
            float:left;
            padding-bottom:10px;
        }
        #price_list small, #price_list_container small{
            color:red;
        }
        .extended-button{
            margin-top:35px;
        }
    </style>
@stop

@section('main-content')
<div class="block-header">
    <h2 id="head_card">
        Detail Tour Activity
        <small>Master Data / Tour Activity</small>
    </h2>
    @if($data->status == 0 || $data->status == 3)
        <a id="status" href="{{ url('product/tour-activity/'.$data->id.'/change/status/1') }}" class="btn bg-green waves-effect">Publish</a>
    @elseif($data->status == 1)
        <a id="status" href="{{ url('product/tour-activity/'.$data->id.'/change/status/0') }}" class="btn bg-red waves-effect">Unpublish</a>
        <a id="status" href="{{ url('product/tour-activity/'.$data->id.'/change/status/2') }}" class="btn bg-green waves-effect">Activate Product</a>
    @else
        <a id="status" href="{{ url('product/tour-activity/'.$data->id.'/change/status/3') }}" class="btn bg-red waves-effect">Disable</a>
    @endif
        <a id="status"> <button type="button" class="btn btn-warning waves-effect" id="change-status" data-toggle="modal" data-target="#statusModal">View Status Log</button></a>
</div>
<!-- Basic Example | Horizontal Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Edit Tour Activity 
                @if($data->status == 0)
                <span class="badge bg-purple">Draft</span>
                @elseif($data->status ==1)
                <span class="badge bg-blue">Awaiting Moderation</span>
                @elseif($data->status ==2)
                <span class="badge bg-green">Active</span>
                @elseif($data->status ==3)
                <span class="badge bg-red">Disabled</span>
                @elseif($data->status ==4)
                <span class="badge bg-pink">Edited</span>
                @else
                <span class="badge bg-red">Expired</span>
                @endif
                </h2>

                <ul class="header-dropdown m-r--5">
                    @if($data->schedule_type != 0)
                    <li>
                        @if($data->always_available_for_sale == 0)
                        <a href="/product/tour-activity/{{$data->id}}/schedule" class="btn bg-teal btn-block waves-effect">Schedule</a>
                        @else
                        <a href="/product/tour-activity/{{$data->id}}/off-day" class="btn bg-black btn-block waves-effect">Off Day</a>
                        @endif
                    </li>
                    @endif
                    <li id="backProduct">
                        <a href="/product/tour-activity" class="btn btn-waves">Back</a>
                    </li>
                </ul>
            </div>
            <div class="body" id="content">
                <div id="step">

                    @include('errors.error_notification')
                    <h3>General Information</h3>
                    <section>
                        <div class="row clearfix">
                        @if(isset($data))
                            {{ Form::model($data, ['route' => ['tour-activity.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'product_form','enctype' =>'multipart/form-data']) }}
                        @else
                            {{ Form::open(['url'=>'product/tour-activity', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'product_form','enctype' =>'multipart/form-data']) }}
                        @endif
                            {{ Form::hidden('step',1)}}
                            <div class="col-md-12">
                                <div class="row clearfix">
                                    <div class="col-md-12">
                                        <h4 class="dd-title m-t-20">
                                             General information
                                        </h4>
                                        <div class="col-md-4 cover-image">
                                            <label>Cover Product Image*</label>
                                            <div class="dd-main-image">
                                                <img style="width: 100%" src="{{(!empty($data)? cdn($data->cover_path.'/'.$data->cover_filename) : 'http://via.placeholder.com/400x300' )}}" id="cover-img">
                                            </div>
                                            {{ Form::hidden('image_resize', null, ['class' => 'form-control','required'=>'required']) }}
                                            <a href="#" id="c_p_picture" class="btn bg-teal btn-block btn-xs waves-effect">Upload Cover Image</a>
                                            <input name="cover_img" id="c_p_file" type='file' style="display: none" accept="image/x-png,image/gif,image/jpeg">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group m-b-20">
                                                <label>Company(*)</label>
                                                <select name="company_id" class="form-control" id="company_id" required>
                                                    @if(!empty($data->company_id) && !empty($data->company))
                                                    <option value="{{$data->company_id}}">{{$data->company->company_name}}</option>
                                                    @else
                                                    <option value="">--Select Company--</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                        <label>Product Category(*)</label>
                                                         {!! Form::select('product_category',Helpers::productCategory(),null,['class' => 'form-control show-tick']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group" id="div_product_type">
                                                        <label>Product Type(*)</label>
                                                        <div class="input-group dd-group">
                                                                {!! Form::select('product_type',Helpers::productType(),null,['class' => 'form-control']) !!}
                                                            <span class="input-group-addon">
                                                                <a href="#" class="info-type" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="left" title="" data-content="Within a single commencing schedule, customers can book for their own private group. They won't be grouped with another customers." data-original-title="Private Group"><i class="material-icons">info_outline</i></a>
                                                            </span>
                                                         </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group m-b-20">
                                                <label>Product Name(*)</label>
                                                 {{ Form::text('product_name', null, ['class' => 'form-control','id'=>'product_name','required'=>'required']) }}
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                        <label>Min Person(*)</label>
                                                         {{ Form::text('min_person', null, ['class' => 'form-control','id'=>'min_person','required'=>'required']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group m-b-20">
                                                        <label>Max Person(*)</label>
                                                         {{ Form::text('max_person', null, ['class' => 'form-control','id'=>'max_person','required'=>'required']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 m-t-20">
                                    <div class="col-md-6"> 
                                        {{ Form::hidden('meeting_point_latitude', null, ['placeholder'=>'Latitude','id'=>'lat']) }}
                                        {{ Form::hidden('meeting_point_longitude', null, ['placeholder'=>'Longitude','id'=>'lng']) }}
                                        <div class="form-group m-b-20">
                                            <label>Starting Point/Gathering Point(where should your costumer meet you)?*</label> 
                                            {{ Form::text('meeting_point_address', null, ['class' => 'form-control','id'=>'meeting_point_address','required'=>'required']) }}
                                            <div id="infowindow-content">
                                              <img src="" width="16" height="16" id="place-icon">
                                              <span id="place-name"  class="title"></span><br>
                                              <span id="place-address"></span>
                                            </div>
                                        </div>
                                        <div class="form-group m-b-20">
                                            <label>Meeting Point Notes</label>
                                            {{ Form::textArea('meeting_point_note', null, ['class' => 'form-control no-resize','rows'=>"4"]) }}
                                        </div>
                                        <div class="form-group m-b-20">
                                            <label>PIC Name(*)</label>
                                            {{ Form::text('pic_name', null, ['class' => 'form-control','required'=>"required"]) }}
                                        </div>
                                        <div class="form-group m-b-20">
                                            <label>PIC Phone(*)</label>
                                            <input type="hidden" class="form-control" id="PICFormat" name="format_pic_phone">   
                                            <input style="width: 100%;margin-top: 5px" type="text" class="form-control" id="PICPhone" name="pic_phone" data-old="@if(!empty(old('pic_phone'))){{old('pic_phone')}} @elseif(!empty($data)){{$data->pic_phone}}@endif" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="map_canvas" id="map"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group m-b-20">
                                            <label>Term & Condition(*)</label>
                                            {{ Form::textArea('term_condition', null, ['id'=>'tinymce','class' => 'form-control no-resize','rows'=>"4","required" => "required"]) }}
                                        </div>

                                        <div class="form-group m-b-20">
                                            <label>Buyer Remarks</label>
                                            {{ Form::textArea('buyer_remarks', null, ['id'=>'tinymce','class' => 'form-control no-resize','rows'=>"4","required" => "required"]) }}
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-md-4">
                                    <div class="row clearfix">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-block btn-lg btn-success waves-effect">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{Form::close()}}
                        </div>
                    </section>
                    <h3>Activity Details</h3>
                    <section>
                        <div class="row clearfix">
                        @if(isset($data))
                            {{ Form::model($data, ['route' => ['tour-activity.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'step_2','enctype' =>'multipart/form-data']) }}
                        @else
                            {{ Form::open(['url'=>'product/tour-activity', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'step_2','enctype' =>'multipart/form-data']) }}
                        @endif
                        {{ Form::hidden('step',2)}}
                        <div class="col-md-12 activity-details">
                            <div class="col-md-12 dd-cli">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="dd-title m-t-20">
                                             Activity Schedule and Duration
                                        </h4>
                                        <h5>How long is the duration of your tour/activity ?</h5>
                                        <div class="col-md-3">
                                            <input name="schedule_type" type="radio" id="1d" class="radio-col-deep-orange schedule-type" value="1" sel="1" required @if($data->schedule_type == 1) checked @elseif($data->schedule_type == 0) 
                                            checked
                                            @endif @if($data->schedule_type != 0) disabled @endif/>
                                            <label for="1d">Multiple days</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input name="schedule_type" type="radio" id="2d" class="radio-col-deep-orange schedule-type" value="2" sel="2" @if($data->schedule_type == 2) checked @endif @if($data->schedule_type != 0) disabled @endif/>
                                            <label for="2d">A couple of hours</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input name="schedule_type" type="radio" id="3d" class="radio-col-deep-orange schedule-type" value="3" sel="3" @if($data->schedule_type == 3) checked @endif @if($data->schedule_type != 0) disabled @endif/>
                                            <label for="3d">One day full</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-md-12">
                                        <div class="col-md-2 scheduleDays">
                                            <div class="form-group">
                                                <h5>Day?* :</h5>
                                                <select class="form-control" id="day" name="day"  required @if($data->schedule_type != 0) disabled @endif>
                                                    @for($i=2;$i<=24;$i++)
                                                    <option values="{{$i}}" @if($data->schedule_interval == $i) selected @endif>{{$i}}</option>
                                                    <!-- <option values="{{$i}}" @if(old('day') == $i) selected @elseif(count($data->itineraries) == $i) selected @endif>{{$i}}</option> -->
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4 scheduleHours" hidden>
                                            <div class ="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Hours?* :</label>
                                                        <select class="form-control" id="hours" name="hours" required @if($data->schedule_type != 0) disabled @endif>
                                                            @for($i=1;$i<12;$i++)
                                                            <option values="{{$i}}" @if(old('hours') ==$i)selected @elseif($data->schedule_type == 2 &&
                                                            (int)substr($data->schedule_interval,0,2) == $i) selected @endif>{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Minutes?* :</label>
                                                        <select class="form-control" id="minutes" name="minutes" required @if($data->schedule_type != 0) disabled @endif>
                                                            <option values="0" @if(old('minutes') ==0)selected @elseif($data->schedule_type == 2 &&
                                                            (int)substr($data->schedule_interval,3) == 0) selected @endif>0</option>
                                                            <option values="30" @if(old('minutes') ==30)selected @elseif($data->schedule_type == 2 &&
                                                            (int)substr($data->schedule_interval,3) == 30) selected @endif>30</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-12 schedule-activity" style="display: none">
                                        <h5>How would you like to set your activity schedule ?</h5>
                                        <div class="col-md-4">
                                            <input name="always_available_for_sale" type="radio" id="free_sale_1" class="radio-col-deep-orange" value="1" sel="1" @if($data->always_available_for_sale == 1) checked @endif/>
                                            <label for="free_sale_1">Always available for booking</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input name="always_available_for_sale" type="radio" id="free_sale_0" class="radio-col-deep-orange" value="0" sel="0" @if($data->always_available_for_sale == 0) checked @endif/>
                                            <label for="free_sale_0">Only available on specific dates</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h5>Maximum Booking Date / How many days prior the schedule at maximum customer can book your activity?</h5>
                                        <div class="row">
                                            <div class="col-md-2">
                                               {{ Form::number('max_booking_day', null, ['class' => 'form-control','id'=>'max_booking_day','required'=>'required','min'=> 0,'max'=>30]) }}
                                            </div>
                                            <div class="col-md-6">
                                            <p class="form-note">Your activity is available for booking until D-<b>
                                            @if($data->max_booking_day != null)
                                                {{$data->max_booking_day}}
                                            @else
                                                0
                                            @endif
                                            </b> from activity schedule.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="dd-title m-t-20">
                                    Destination Details
                                </h4>
                                <h5>List down all destination related to your tour package / activity.</h5>
                                <h5>The more accurate you list down the destinations,the better your product's peformance in search result.</h5>
                                <div class="master-destinations">
                                    <div class="row clearfix">
                                        <div class="col-md-3 col-province">
                                            <h5>Province*</h5>
                                            <select  class="form-control province-sel" name="place[0][province]" id="0-province" data-id="0" style="width: 100%" required>
                                                <option value="" selected>-- Select Province --</option>
                                                @if(!empty($provinces))
                                                    @foreach($provinces as $province)
                                                        @if(count($data->destinations) !=0)
                                                        <option value="{{$province->id}}" @if($data->destinations[0]->province_id == $province->id) selected="" @endif>{{$province->name}}</option>
                                                        @else
                                                        <option value="{{$province->id}}">{{$province->name}}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-city">
                                            <h5>City*</h5>
                                            <select  class="form-control city-sel" name="place[0][city]" id="0-city" style="width: 100%" data-id="0" required>
                                                @if(count($data->destinations) !=0)
                                                @foreach(Helpers::cities($data->destinations[0]->province_id) as $key => $city)
                                                    <option value="{{$key}}" @if($key == $data->destinations[0]->city_id) selected @endif>{{$city}}</option>
                                                @endforeach
                                                @else
                                                <option value="" selected>-- Select City --</option>
                                                @endif
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-4 col-destination">
                                            <h5>Destination</h5>
                                            <select class="form-control destination-sel" id="0-destination" name="place[0][destination]" style="width: 100%"
                                                @if(count($data->destinations) !=0)
                                                    destid = "{{$data->destinations[0]->destination_id}}"
                                                @else
                                                    destid = ""
                                                @endif
                                            >
                                            </select>
                                            <b style="font-size:10px">Leave empty if you can't find the destination</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="dynamic-destinations">
                                    @if(!empty($data))
                                        @foreach($data->destinations as $index => $destination)
                                        @if($index > 0)
                                    <div class="row clearfix">
                                        <div class="col-md-3 col-province">
                                            <h5>Province*</h5>
                                            <select  class="form-control province-sel" name="place[{{$index}}][province]" id="{{$index}}-province" data-id="{{$index}}" style="width: 100%" required>
                                                <option value="" selected>-- Select Province --</option>
                                                @if(!empty($provinces))
                                                @foreach($provinces as $province)
                                                    @if(!empty($destination))
                                                    <option value="{{$province->id}}" @if($destination->province_id == $province->id) selected="" @endif>{{$province->name}}</option>
                                                    @else
                                                    <option value="{{$province->id}}">{{$province->name}}</option>
                                                    @endif
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-city">
                                            <h5>City*</h5>
                                            <select  class="form-control city-sel" name="place[{{$index}}][city]" id="{{$index}}-city" data-id="{{$index}}" style="width: 100%" required>
                                                @if(!empty($destination))
                                                @foreach(Helpers::cities($destination->province_id) as $key => $city)
                                                    <option value="{{$key}}" @if($key == $destination->city_id) selected @endif>{{$city}}</option>
                                                @endforeach
                                                
                                                @else
                                                <option value="" selected>-- Select City --</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-destination">
                                            <h5>Destination</h5>
                                            <select class="form-control destination-sel" id="{{$index}}-destination" name="place[{{$index}}][destination]" style="width: 100%"
                                                @if(!empty($destination))
                                                    destid = "{{$destination->destination_id}}"
                                                @else
                                                    destid = ""
                                                @endif
                                            >
                                            </select>
                                            <b style="font-size:10px">Leave empty if you can't find the destination</b>
                                        </div>
                                        <button type="button" class="btn btn-xs btn-danger waves-effect btn-delete-des" data-id="{{$index}}"><i class="material-icons">clear</i></button>
                                    </div>
                                        @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-warning waves-effect" id="add_more_destination">
                                            <i class="material-icons icon-align">add</i>
                                            Add Destination
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <h4 class="dd-title m-t-20">
                                    Activity Tag
                                </h4>
                                <h5>How would you describe the activities in this product?</h5>
                                <select class="form-control" id="activity_tag" name="activity_tag[]" multiple="multiple" style="width: 100%">
                                    @if(!empty($data))
                                        @foreach($data->activities as $activity)
                                            <option value="{{$activity->id}}" selected>{{$activity->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class=" col-md-4 m-t-30">
                                <div class="row clearfix">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-block btn-lg btn-success waves-effect">Save</button>
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                        {{Form::close()}}
                        </div>
                    </section>
                    <h3>Itinerary</h3>
                    <section>
                    @if(isset($data))
                        {{ Form::model($data, ['route' => ['tour-activity.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'step_3','enctype' =>'multipart/form-data']) }}
                    @else
                        {{ Form::open(['url'=>'product/tour-activity', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'step_3','enctype' =>'multipart/form-data']) }}
                    @endif   
                        {{ Form::hidden('step',3)}}
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="dd-title m-t-20">
                                    Itinerary Details
                                </h4>
                                @foreach($data->itineraries as $itinerary)
                                <div class="col-md-12">
                                    <h5>Day-{{$itinerary->day}}</h5>
                                    {{Form::hidden('id[]',$itinerary->id)}}
                                    <div class="col-md-2 valid-info" id="field_itinerary_{{$itinerary->id}}">
                                        <h5>Start time*</h5>
                                        {{ Form::text('start_time[]', substr($itinerary->start_time,0,5), ['class' => 'form-control','placeholder'=>"HH:mm",'required'=>'required']) }}
                                    </div>
                                    <div class="col-md-2 valid-info" id="field_itinerary_{{$itinerary->id}}">
                                        <h5>End time*</h5>
                                        {{ Form::text('end_time[]', substr($itinerary->end_time,0,5), ['class' => 'form-control','placeholder'=>"HH:mm",'required'=>'required']) }}
                                    </div>
                                    <div class="col-md-8 valid-info" id="field_itinerary_{{$itinerary->id}}">
                                        <h5>Description</h5>
                                        {{ Form::textArea('description[]', $itinerary->description, ['class' => 'form-control','rows'=>"6"]) }}
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>
                        <div class=" col-md-4 m-t-20 m-b-20">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-block btn-lg btn-success waves-effect">Save</button>
                            </div>
                        </div>
                    {{ Form::close() }}
                    </section>
                    <h3>Pricing</h3>
                    <section>
                        @if(isset($data))
                            {{ Form::model($data, ['route' => ['tour-activity.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'step_4','enctype' =>'multipart/form-data']) }}
                        @else
                            {{ Form::open(['url'=>'product/tour-activity', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'step_4','enctype' =>'multipart/form-data']) }}
                        @endif
                        {{ Form::hidden('step',4)}}
                        <h4 class="dd-title m-t-20">
                            Pricing Details
                        </h4>
                        <div class="col-md-12">
                            <div class="row valid-info">
                                <div class="col-md-4">
                                    @php 
                                        if(count($data->prices) !== 0){
                                            $u1 = json_decode($data->prices); 
                                            $u2 = array_pluck($u1,'price_usd');
                                        }
                                    @endphp
                                    <input name="price_kurs" type="radio" id="1p" class="radio-col-deep-orange" value="1" required
                                    @if(count($data->prices) !== 0)
                                        @if(!array_filter($u2)) 
                                            checked
                                        @endif
                                    @else
                                        @if(empty($data->price_idr))
                                            checked
                                        @endif
                                    @endif 
                                    @if(!empty($data->price_idr) && empty($data->price_usd))
                                        checked 
                                    @endif
                                    />
                                    <label for="1p" style="font-size:15px">I only have pricing in IDR</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="price_kurs" type="radio" id="2p" class="radio-col-deep-orange" value="2" 
                                        @if(count($data->prices) !== 0)
                                            @if(array_filter($u2)) 
                                                checked
                                            @endif
                                        @endif 
                                        @if(!empty($data->price_idr) && !empty($data->price_usd)) 
                                            checked 
                                        @endif/>
                                    <label for="2p" style="font-size:15px">I want to add pricing in USD for international tourist</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <h5>Pricing Option</h5>
                                    <select name="price_type" id="priceType" class="form-control" required>
                                        <option value="1" @if(count($data->prices) == 1)) selected @endif>Fixed Price</option>
                                        <option value="2" @if(count($data->prices) > 1 ) selected @endif>Based on Number of Person</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="price_table_container">
                            <div class="row">
                                <h4 class="dd-title m-t-20">
                                    Pricing Tables
                                </h4>
                                <div class="alert alert-danger alert-price" style="display:none">
                                    <strong>Oh snap!</strong> Please edit price list table.
                                </div>
                                <div class="col-md-10" id="price_list">
                                <!--  -->
                                    <div class="row" id="price_row">
                                        <div class="col-md-1" style="padding: 25px 0px 0px 0px;width:auto">
                                            <h5><i class="material-icons">person</i></h5>
                                        </div>
                                        <div class="col-md-11" style="margin-left:0px">
                                            <div class="row">
                                                <div class="col-md-2 valid-info" id="number_person" style="display:none">
                                                    <h5>Person*</h5>
                                                    <input id="price_list_field1" type="number" class="form-control"
                                                        @if(count($data->prices) > 0) name="price[{{count($data->prices)}}][people]" @else name="price[0][people]" required @endif  />  
                                                </div>
                                                <div class="col-md-4 valid-info" id="price_idr">
                                                    <h5>Price (IDR)*</h5>
                                                    <input id="price_list_field2" type="text" class="form-control"  
                                                        @if(count($data->prices) > 0) name="price[{{count($data->prices)}}][IDR]" @else name="price[0][IDR]"  required @endif />     
                                                </div>
                                                <div class="col-md-4 valid-info" id="price_usd" style="display: none">
                                                    <h5>Price (USD)*</h5>
                                                    <input id="price_list_field3" type="text" class="form-control" 
                                                        @if(count($data->prices) > 0) name="price[{{count($data->prices)}}][USD]" @else name="price[0][USD]" required @endif  />     
                                                </div>
                                                <button id="add_price_button" type="button" class="col-md-2 btn bg-deep-orange waves-effect extended-button">Add Price</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!--  -->
                                @if(count($data->prices) != 0)
                                <div id="price_list_container">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th id="numberOfPerson">Number Of Person</th>
                                                <th id="price_idr">Price IDR</th>
                                                <th id="price_usd">Price USD</th>
                                                <th id="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                            @foreach($data->prices as $index => $val)
                                            <tr id="price_value" data-id="{{$val->id}}">
                                                <td id="numberOfPerson">
                                                    <p><h9 id="price_info" style="display:none">>= </h9>{{$val->number_of_person}}</p>
                                                    <input style="display:none" id="price_list_field1" type="text"  class="form-control" value="{{$val->number_of_person}}"  late-data="{{$val->number_of_person}}" required>  
                                                </td>
                                                <td id="price_idr">
                                                    <p>{{number_format((int)$val->price_idr)}}</p>
                                                    <input style="display:none" id="price_list_field2" type="text" class="form-control"  value="{{(int)$val->price_idr}}" required>     
                                                    
                                                </td>
                                                <td id="price_usd">
                                                    <p>{{number_format((int)$val->price_usd)}}</p>
                                                    <input style="display:none" id="price_list_field3" type="text" class="form-control" value="{{(int)$val->price_usd}}" required/>     
                                                </td>
                                                <td id="action">    
                                                    <a style="display:none" id="price_update"><i class="material-icons">save</i></a>
                                                    <a id="price_edit"><i class="material-icons">mode_edit</i></a>
                                                    <a href="{{ url('product/tour-activity/'.$data->id.'/price/'.$val->id.'/delete') }}" id="price_delete"><i class="material-icons">delete</i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <h4 class="dd-title m-t-20">
                                    Pricing Includes
                                </h4>
                                <div class="col-md-6 valid-info">
                                    <h5>What's already included with pricing you have set?What will you provide?</h5>
                                    <h7 class="font-14">Example: Meal 3 times a day, mineral water, driver as tour guide.</h7>
                                    <select type="text" class="form-control" name="price_includes[]" multiple="multiple" style="width: 100%">
                                        @foreach($data->includes as $include)
                                            <option selected>{{$include->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6" style="padding-top:85px">
                                    <h5>Type a paragraph and press Enter.</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                 <h4 class="dd-title m-t-20">
                                    Pricing Exclude
                                </h4>
                                <div class="col-md-6 valid-info">
                                    <h5>What's not included with pricing you have set?Any extra cost the costumer should be aware of?</h5>
                                    <h7 class="font-14">Example: Entrance fee IDR 200,000, bicycle rental, etc</h7>
                                    <select class="form-control" name="price_excludes[]" multiple="multiple" style="width: 100%">
                                        @foreach($data->excludes as $exclude)
                                            <option value="{{$exclude->name}}" selected="">{{$exclude->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6" style="padding-top:85px">
                                    <h5>Type a paragraph and press Enter.</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <h4 class="dd-title m-t-20">
                                    Cancellation Policy
                                </h4>
                                <div class="col-md-3">
                                    <input name="cancellation_type" type="radio" id="1c" class="radio-col-deep-orange" value="0" required @if($data->cancellation_type == 0) checked @elseif($data->cancellation_type == null) checked @endif />
                                    <label for="1c">No cancellation</label>
                                </div>
                                <div class="col-md-3">
                                    <input name="cancellation_type" type="radio" id="2c" class="radio-col-deep-orange" value="1"  @if($data->cancellation_type == 1) checked @endif />
                                    <label for="2c">Free cancellation</label>
                                </div>
                                <div class="col-md-4">
                                    <input name="cancellation_type" type="radio" id="3c" class="radio-col-deep-orange" value="2"   @if($data->cancellation_type == 2) checked @endif />
                                    <label for="3c">Cancellation policy applies</label>
                                </div>
                            </div>
                            <div class="row" id="cancel_policy" style="display: none;margin:0px">
                                <h5>How is your cancellation policy?</h5>
                                <div class="row" style="font-size: 14px;margin:0px">
                                    <div class="col-md-2" style="margin:5px;padding:0px;width:auto">
                                        <h5>Cancellation less than</h5>
                                    </div>
                                    <div class="col-md-1 valid-info" style="margin:5px;padding:0px">
                                        <input type="text" id="cancellationDay" name="max_cancellation_day" class="form-control" value="{{$data->max_cancellation_day}}" required>
                                    </div>
                                    <div class="col-md-2" style="margin:5px;padding:0px;width:auto">
                                        <h5>days from schedule, cancellation fee is</h5>
                                    </div>
                                    <div class="col-md-1 valid-info" style="margin:5px;padding:0px">
                                        <input type="text" id="cancellationFee" name="cancel_fee" class="form-control" value="{{$data->cancellation_fee}}" required>
                                    </div>
                                    <div class="col-md-2" style="margin:5px;padding:0px;width:auto">
                                        <h5>percent(%)</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-md-4 m-t-20 m-b-20">
                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <button type="submit" id="submit_price" class="btn btn-block btn-lg btn-success waves-effect">Continue</button>
                                </div>
                            </div>
                        </div>
                    {{Form::close()}}
                    </section>
                    <h3>Images</h3>
                    <section>
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            Attention Please! When the image is uploaded. <b>Button delete</b> doesn't work before refresh this page !
                        </div>
                        <h4 class="dd-title m-t-20">
                            Activity Image
                        </h4>
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="file-loading">
                                    <input id="activity_images" name="activity_images[]" type="file" multiple>
                                </div>
                            </div>
                        </div>
                        <h4 class="dd-title m-t-20">
                            Destination Image
                        </h4>
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="file-loading">
                                    <input id="destination_images" name="destination_images[]" type="file" multiple>
                                </div>
                            </div>
                        </div>
                        <h4 class="dd-title m-t-20">
                            Accomodation Image
                        </h4>
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="file-loading">
                                    <input id="accomodation_images" name="accomodation_images[]" type="file" multiple>
                                </div>
                            </div>
                        </div>
                        <h4 class="dd-title m-t-20">
                            Others Image
                        </h4>
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="file-loading">
                                    <input id="other_images" name="other_images[]" type="file" multiple>
                                </div>
                            </div>
                        </div>
                        <h4 class="dd-title m-t-20"><i class="material-icons">perm_media</i> Video URL</h4>
                        <h5>Add your video link to embed the video into your product's gallery.</h5>
                        {{ Form::model($data, ['route' => ['tour-activity.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'step_5','enctype' =>'multipart/form-data']) }}
                        {{ Form::hidden('step',5)}}
                        <div class="row" id="embed" style="display: block;margin-bottom: 10px">
                            <div class="col-md-6" >
                                <input type="url" class="form-control" name="videoUrl[]" value="@if(count($data->videos) !== 0){{$data->videos[0]->url}}@endif" placeholder="https://www.youtube.com/productvideo"/>
                            </div>
                            <div class="col-md-3">                            
                                <button type="button" class="btn btn-warning waves-effect" id="add_more_video">
                                    <i class="material-icons">add</i>
                                    <span>Add link</span>
                                </button>
                                <button type="submit" class="btn btn-success waves-effect" id="add_more_video">
                                    <i class="material-icons">save</i>
                                    <span>Save</span>
                                </button>
                            </div>
                        </div>
                        <div id="clone_dinamic_video">
                            @if(count($data->videos) !== 0)
                                @foreach($data->videos as $index => $vid)
                                @if($index > 0)
                                    <div class="row dinamic_video6" id="embed" style="display: block;margin-bottom: 10px">
                                        <div class="col-md-6">
                                            <input type="url" class="form-control" name="videoUrl[]" value="{{$vid->url}}">
                                        </div>
                                        <div class="col-md-3"><button type="button" id="delete_video" class="btn btn-danger waves-effect"><i class="material-icons">clear</i></button></div>
                                    </div>
                                @endif
                                @endforeach
                            @endif
                        </div>
                        </form>
                    </section>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Advanced Validation -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="modal-title" id="statusModalLabel">Status</h4>
                    </div>    
                </div>
            </div>
            <div class="modal-body">
                <table class="table table-striped log-status-list">
                    <tbody>
                        @if(!empty($data->log_statuses))
                        @foreach($data->log_statuses as $test)
                        
                        <tr>
                            <td>
                                @if($test->status == 0)
                                <span class="badge bg-purple">Draft</span>
                                @elseif($test->status ==1)
                                <span class="badge bg-blue">Awaiting Moderation</span>
                                @elseif($test->status ==2)
                                <span class="badge bg-green">Active</span>
                                @elseif($test->status ==3)
                                <span class="badge bg-pink">Disable</span>
                                @elseif($test->status ==4)
                                <span class="badge bg-orange">Edited</span>
                                @else
                                <span class="badge bg-gray">Expired</span>
                                @endif
                            </td>
                            <td>{{date_format($test->created_at,"d/M/Y H:i:s")}}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Cropper Image</h4>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="crop-image" src="" alt="Picture" class="img-responsive">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect btn-img-save">SAVE CHANGES</button>
                <button type="button" class="btn btn-link waves-effect btn-img-close" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('head-js')
@parent
   <!-- JQuery Steps Plugin Js -->
    <script src="{{asset('plugins/jquery-steps/jquery.steps.js')}}"></script>
    <script src="{{asset('js/pages/ui/tooltips-popovers.js')}}"></script>
    <script src="{{asset('js/pages/geocomplete/jquery.geocomplete.min.js')}}"></script>
    <script src="{{ asset('plugins/cropper/cropper.min.js') }}"></script>

    <script src="{{ asset('plugins/telformat/js/intlTelInput.js') }}"></script>
    <!-- Jquery Validation Plugin Css -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script> 
    <!-- Mask js -->
    <script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script> 

    <!-- Light Gallery Plugin Js -->
    <script src="{{asset('plugins/light-gallery/js/lightgallery-all.js')}}"></script>

    <!-- Bootstrap File-Input-Js -->
    <script src="{{ asset('plugins/bootstrap-file-input/js/fileinput.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{asset('js/pages/medias/image-gallery.js')}}"></script>
    <!-- Moment Plugin Js -->
    <script src="{{ asset('plugins/momentjs/moment.js') }}"></script>
    <!-- Bootstrap date range picker -->
    <script src="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <!-- TinyMCE -->
    <script src="{{ asset('plugins/tinymce/tinymce.js') }}"></script>

    <script type="text/javascript">
        function initMap()
        {
            var myLatlng = new google.maps.LatLng({{isset($data->meeting_point_latitude) ? $data->meeting_point_latitude : 0}},{{isset($data->meeting_point_longitude) ? $data->meeting_point_longitude : 0}});
            var map = new google.maps.Map(document.getElementById('map'), {
              center: {lat:{{isset($data->meeting_point_latitude) ? $data->meeting_point_latitude : 0}} , lng:{{isset($data->meeting_point_longitude) ? $data->meeting_point_longitude : 0}} },
              zoom: 13
            });
            var input = document.getElementById('meeting_point_address');
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);
            autocomplete.setFields(['address_components', 'geometry', 'icon', 'name']);
            var infowindow = new google.maps.InfoWindow();
            var infowindowContent = document.getElementById('infowindow-content');
            infowindow.setContent(infowindowContent);
            var marker = new google.maps.Marker({
              map: map,
              position: myLatlng
            });
            autocomplete.addListener('place_changed', function(e) {
              infowindow.close();
              marker.setVisible(false);
              var place = autocomplete.getPlace();
              if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
              }

              // If the place has a geometry, then present it on a map.
              if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
              } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
              }
              marker.setPosition(place.geometry.location);
              marker.setVisible(true);
              
              $('#lat').val(place.geometry.location.lat());
              $('#lng').val(place.geometry.location.lng());
              var address = '';
              if (place.address_components) {
                address = [
                  (place.address_components[0] && place.address_components[0].short_name || ''),
                  (place.address_components[1] && place.address_components[1].short_name || ''),
                  (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
              }
              infowindowContent.children['place-icon'].src = place.icon;
              infowindowContent.children['place-name'].textContent = place.name;
              infowindowContent.children['place-address'].textContent = address;
              infowindow.open(map, marker);
            });
        }
        $( window ).on( "load", function() {
            @if($data->product_category == "Event" )
                $("select[name='product_type']").prop("readonly", "readonly")
                $("#div_product_type").hide()
            @endif
            $("select[name='product_category']").change(function(){
                if($(this).val().toLowerCase() == "event"){
                    $("select[name='product_type']").prop("readonly", "readonly")
                    $("#div_product_type").hide()
                }
                else{
                    $("select[name='product_type']").removeProp("readonly")
                    $("#div_product_type").show()
                }
            })
            if($('#3d').prop('checked')){
                $(".scheduleDays, .scheduleHours").removeAttr("required").hide();
                $(".schedule-activity").show();
            }else if($('#2d').prop('checked')){
                $(".scheduleHours").show();
                $(".scheduleDays").removeAttr("required").hide();
            }
            var cancel = $("#3c").prop("checked");
            if(cancel){
                $("#cancel_policy").show();
            }
            // console.log(cancel);
            var tpPrice = $('#1p').prop("checked");
            var opPrice = $('#priceType').val();
            
            if(tpPrice){
                $("#price_usd, #price_list_container #price_usd").hide();
            }else{
                $("#price_usd, #price_list_container #price_usd").show();
            }
            if(opPrice == 1){
                $("button#add_price_button").hide();
                @if(count($data->prices) > 0 )
                    $("div#price_list").hide();
                    $("tr#price_value").each(function(){
                        $(this).find("h9#price_info").hide();
                        $(this).find("#price_list_field1").attr("readonly","readonly");
                    });
                @else
                    $("div#price_row").find("#number_person").hide().find("#price_list_field1").val(1).removeAttr("min").removeAttr("max");
                    $("div#price_list").show();
                @endif
            }else{
                @if(count($data->prices) > 0)
                    @if($max_pep == $data->max_person)
                        $("div#price_list").hide();
                    @else
                        $("div#price_row").find("#number_person").show().find("#price_list_field1").attr("max","{{$data->max_person}}").val("{{$data->prices[(count($data->prices)-1)]->number_of_person+1}}");
                    @endif
                @else
                    $("div#price_row").find("#number_person").show().find("#price_list_field1").attr("min","{{$data->min_person}}").attr("max","{{$data->max_person}}").val("{{$data->min_person}}");
                @endif
                $("button#add_price_button").show();
                $("tr#price_value").each(function(){
                    $(this).find("h9#price_info").show();
                });
             }     
            var hash = location.hash;
            if(hash != ""){
                $('#step').steps('setStep',location.hash.substring(location.hash.length-1));
                $('.steps ul li').removeClass('done');
            }
            @if(!empty(old('company_id')))
                $.ajax({
                    type: "GET",
                    data: {"id": {{old('company_id')}} },
                    url: '/json/company',
                    success: function(result) {
                        if(result.code == 200){
                            $("#company_id").select2("trigger", "select", {
                                data: { id: result.data[0].id,name: result.data[0].name }
                            });
                        }
                    }
                });
            @endif
            var image = $('input[name="image_resize"').val();
            if(image != ""){
                $('#cover-img').attr('src',image);
            }
            var dbphone = $('input[name="pic_phone"]').attr('data-old');
            if(dbphone != ""){
                var dbformat = dbphone.split("-");
                var pic_phone = "";
                $(dbformat).each(function(index,value) {
                    if(index == 1){
                        pic_phone = value;
                    }
                    else if(index > 1){
                        pic_phone = pic_phone+'-'+value;
                    }

                });
                $("input[name='format_pic_phone']").val(dbformat[0]);
                $("input[name='pic_phone']").val(pic_phone).intlTelInput({
                    separateDialCode: true,
                });
            }

        });
        $(document).ready(function () {
            $( "#product_form" ).validate({
              rules: {
                min_person: {
                  number: true
                },
                max_person: {
                  number: true
                }
              }
            });
            $( "#step_4" ).validate({
              rules: {
                "max_cancellation_day":{
                    number:true
                },
                "cancel_fee":{
                    number:true
                }
              }
            });
            $( "#step_3" ).validate({
              rules: {
                
              }
            });
            $( "#step_2" ).validate({
              rules: {
                "activity_tag":{
                    required:true
                }
              }
            });
            $('.steps ul li a').click(function(){
                var temp = $(this).attr('href');
                location.hash = temp;
            });
        });
        $("#step").steps({
            headerTag: "h3",
            bodyTag: "section",
            enableAllSteps: true,
            enablePagination: false
        });
        $("input#idr,input#usd").mask('0.000.000.000', {reverse: true});
        $("input#price_list_field2,input#price_list_field3").each(function(){
            $(this).mask('0.000.000.000', {reverse: true});
        });
        $("#PICPhone").mask('000-0000-00000');
        $("#PICFormat").val("+62");
        $("#PICPhone").val("+62").intlTelInput({
            separateDialCode: true,
        });
        $(".country").click(function(){
            $(this).closest(".valid-info").find("#PICFormat").val("+"+$(this).attr( "data-dial-code" ));
        });

        $(function(){
            var activityTag = [];
            $.ajax({
                method: "GET",
                url: "/json/activity",
            }).done(function(response) {
                $.each(response, function (index, value) {
                    var activity = [];
                    activity["id"] = value["id"];
                    activity["text"] = value["name"];
                    activityTag.push(activity);
                });
                
                $("#activity_tag").select2({
                    placeholder: "Start type here.",
                    data: activityTag,
                });
            });
            
            $("#company_id").select2({
                ajax: {
                    url: "/json/company",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        name: params.term, // search term
                        page: params.page,
                      };
                    },
                    processResults: function (data, params) {
                      // parse the results into the format expected by Select2
                      // since we are using custom formatting functions we do not need to
                      // alter the remote JSON data, except to indicate that infinite
                      // scrolling can be used
                      params.page = params.page || 1;
                      return {
                        results: data.data,
                        pagination: {
                          more: (params.page * 30) < data.total_count
                        }
                      };
                    },
                    cache: true
                  },
                  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                  minimumInputLength: 1,
                  templateResult: formatRepo, // omitted for brevity, see the source of this page
                  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
            });
            function formatRepo (repo) {
                  if (repo.loading) return repo.text;

                  var markup = "<div class='select2-result-repository clearfix'>" +

                    "<div class='select2-result-repository__meta'>" +
                      "<div class='select2-result-repository__title'>" + repo.name + "</div>";

                  "</div></div>";

                  return markup;
                }
            function formatRepoSelection (repo) {
              return repo.name || repo.text;
            }
        });
        $(document).ready(function () {

            window.addEventListener('DOMContentLoaded', function () {
                var image = document.getElementById('crop-image');
                var cropBoxData;
                var canvasData;
                var cropper;

                $('#defaultModal').on('shown.bs.modal', function () {
                    cropper = new Cropper(image, {
                        autoCropArea: 1,
                        aspectRatio: 4/3,
                        strict: false,
                        guides: false,
                        highlight: false,
                        dragCrop: false,
                        zoomable: false,
                        scalable: false,
                        rotatable: false,
                        cropBoxMovable: true,
                        cropBoxResizable: false,
                        responsive: true,
                        viewMode: 1,
                        ready: function () {
                            // Strict mode: set crop box data first
                            cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                        }
                    });
                    
                }).on('hidden.bs.modal', function () {
                    cropBoxData = cropper.getCropBoxData();
                    canvasData = cropper.getCanvasData();
                    originalData = cropper.getCroppedCanvas();
                    cropper.destroy();
                });
                $('.btn-img-save').click(function(){
                    data = originalData = cropper.getCroppedCanvas();
                    $('input[name="image_resize"]').val(originalData.toDataURL('image/jpeg'));
                    $('#cover-img').attr('src',originalData.toDataURL('image/jpeg'));
                    $('.btn-img-close').click();
                });
            });

            $('#c_p_picture').click(function(e){
                e.preventDefault();
                $('input[name="cover_img"]').click();

            });
            function readURL(input) {
            if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('img#crop-image').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(document).delegate('#c_p_file', 'change', function(e){
                e.preventDefault();
                $('#defaultModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                readURL(this);
            });
            // ADDMORE
            var destLength = "{{count($data->destinations)}}";
            $("#add_more_destination").click(function(){
                destLength++;
                $('.child-'+destLength+' .btn-delete-des').remove()
                $(".master-destinations").children().clone().appendTo(".dynamic-destinations").addClass("child-"+destLength);
                $('.child-'+destLength+' .col-province select').attr('name','place['+destLength+'][province]').attr('id',destLength+'-province').attr('data-id',destLength);
                $('.child-'+destLength+' .col-city select').attr('name','place['+destLength+'][city]').attr('id',destLength+'-city').attr('data-id',destLength);
                $('.child-'+destLength+' .col-city select').val('');
                $('.child-'+destLength+' .col-destination select').attr('name','place['+destLength+'][destination]').attr('id',destLength+'-destination').attr('data-id',destLength);
                $('#'+destLength+'-city').val(0);
                $('#'+destLength+'-destination').empty().append('<option value="" selected>-- Select Destination --</option>');
                $('.child-'+destLength).append('<button type="button" class="btn btn-xs btn-danger waves-effect btn-delete-des"><i class="material-icons">clear</i></button>').attr('data-id',destLength);
            });
            $('.dynamic-destinations').delegate('.btn-delete-des','click', function(e){
                $(this).parent().remove();
            });

            $('.dd-cli').delegate('.province-sel','change',function(e){
                var id = $(this).attr('data-id');
                $.ajax({
                  method: "GET",
                  url: "{{ url('json/findCity') }}",
                  data: {
                    province_id: $(this).val()
                  }
                }).done(function(response) {
                    $('#'+id+'-destination option').remove().empty();
                    $('#'+id+'-destination').append('<option value="" selected="">-- Select Destination --</option>');
                    $('#'+id+'-city option').remove().empty();
                    $('#'+id+'-city').append('<option value="" selected="">-- Select City --</option>');
                    $.each(response, function (index, value) {
                        $('#'+id+'-city').append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
             });
            //  CITY AJAX
            $('.dd-cli').delegate('.city-sel','change',function(e){
                var id = $(this).attr('data-id');
                $.ajax({
                  method: "GET",
                  url: "{{ url('json/findDestination') }}",
                  data: {
                    city_id: $(this).val()
                  }
                }).done(function(response) {
                    $('#'+id+'-destination option').remove();
                    $('#'+id+'-destination option').empty();
                    $('#'+id+'-destination').append('<option value="" selected="">-- Select Destination --</option>');
                    $.each(response, function (index, value) {  
                        $('#'+id+'-destination').append(
                            "<option value="+value.id+">"+value.destination_name+"</option>"
                        );
                    });
                });
             });
            //  PRICE KURS
            $("input[name='price_kurs']").change(function () {
                var priceKurs = $(this).val();
                // $("#price_row").show();
                if(priceKurs == 1){
                    $("#price_usd, #price_list_container #price_usd").hide();
                    $("#price_usd input, #price_list_container #price_usd input").val(null);
                    @if(count($data->prices) > 0)
                        @if($data->prices[0]->price_usd == null ||$data->prices[0]->price_usd != 0.00)
                            $(this).closest("section").find("#submit_price").removeAttr("disabled");
                            $(".alert-price").hide();
                        @endif
                    @endif
                }else{
                    @if(count($data->prices) > 0)
                        @if($data->prices[0]->price_usd == null)
                            $(this).closest("section").find("#submit_price").attr("disabled","disabled");
                            $(".alert-price").show();
                            $("tr#price_value").each(function(){
                                $(this).find("td#price_usd p,td#action a#price_edit").hide();
                                $(this).find("td#price_usd #price_list_field3,td#action a#price_update").show();
                            });
                        @endif
                    @endif
                    $("#price_usd, #price_list_container #price_usd").show();
                }
            });
            $("#priceType").change(function () {
                var prictType = $(this).val();
                if(prictType == 1){
                    @if(count($data->prices) > 0 )
                        $("div#price_list").hide();
                    @else
                        $("div#price_list").show();
                    @endif
                    $("div#price_row").find("#number_person").hide().find("#price_list_field1").val(1).removeAttr("min").removeAttr("max");
                    $("button#add_price_button").hide();
                    $("div#price_row:not(:eq(0))").remove();
                    $("tr#price_value:not(:eq(0))").hide();
                    $("tr#price_value").find("h9#price_info").hide();
                }else{
                    @if(count($data->prices) > 0)
                        $("div#price_row").find("#number_person").show().find("#price_list_field1").attr("min","{{$data->prices[(count($data->prices)-1)]->number_of_person+1}}").attr("max","{{$data->max_person}}").val("{{$data->prices[(count($data->prices)-1)]->number_of_person+1}}");
                    @else
                        $("div#price_row").find("#number_person").show().find("#price_list_field1").attr("min","{{$data->min_person}}").attr("max","{{$data->max_person}}");
                    @endif
                    
                    $("div#price_list").show();
                    $("tr#price_value").each(function(){
                        $(this).show();
                        $(this).find("h9#price_info").show();
                    });
                    $("button#add_price_button").show();
                    // 
                }
            });
            // VALIDATE INPUT PERSON
            var priceL = [];
            $("#price_list").on('change','input#price_list_field1', function(){
                var prev = $(this).closest("div#price_row").prev().find("input#price_list_field1").val();
                $("tr#price_value").each(function(){
                    var pi = $(this).find("td#numberOfPerson #price_list_field1").val();
                    priceL.push(pi); 
                });
                if(parseInt($(this).val()) <= parseInt(prev) ){
                    $(this).closest("div#number_person").find("small").remove();
                    $(this).closest("div#number_person").append("<small>Please insert higher number</small>");
                    $("button#add_price_button").attr("disabled","disabled");
                    $(this).closest("section").find("button[type='submit']").attr('disabled','disabled');
                }else if(priceL.indexOf($(this).val()) != -1){
                    console.log("ga ada");
                    $(this).closest("div#number_person").find("small").remove();
                    $(this).closest("div#number_person").append("<small>Already have number of person</small>");
                    $("button#add_price_button").attr("disabled","disabled");
                    $(this).closest("section").find("button[type='submit']").attr('disabled','disabled');
                }else if($(this).val() <= Math.max.apply(Math,priceL)){
                    console.log("kekecilan");
                    $(this).closest("div#number_person").find("small").remove();
                    $(this).closest("div#number_person").append("<small>Please insert higher number</small>");
                    $("button#add_price_button").attr("disabled","disabled");
                    $(this).closest("section").find("button[type='submit']").attr('disabled','disabled');
                }else{
                    console.log('bener');
                    $("div#number_person").each(function(){
                        $(this).find("small").remove();
                    });
                    $(this).closest("section").find("button[type='submit']").removeAttr('disabled');
                    $("button#add_price_button").removeAttr("disabled");
                }
            });
            // 
            $("#price_list_container").on('change','input#price_list_field1', function(){
                $("tr#price_value").each(function(){
                    var pi = $(this).find("td#numberOfPerson #price_list_field1").attr("late-data");
                    priceL.push(pi); 
                });
                if(priceL.indexOf($(this).val()) != -1){
                    $(this).closest("td#numberOfPerson").find("small").remove();
                    $(this).closest("td#numberOfPerson").append("<small>Already have number of person</small>");
                    $(this).closest("tr#price_value").find("a#price_update").hide();
                }else if({{ !empty($data->max_person) ? $data->max_person : 0}} !== 0){
                    if($(this).val() > {{!empty($data->max_person )? $data->max_person : 0}}){
                        $(this).closest("td#numberOfPerson").find("small").remove();
                        $(this).closest("td#numberOfPerson").append("<small>Please insert less number</small>");
                        $(this).closest("tr#price_value").find("a#price_update").hide();
                    }else{
                        $(this).closest("td#numberOfPerson").find("small").remove();
                        $(this).closest("tr#price_value").find("a#price_update").show();
                    }
                // }else if($(this).val() <= Math.max.apply(Math,priceL)){
                //     $(this).closest("td#numberOfPerson").find("small").remove();
                //     $(this).closest("td#numberOfPerson").append("<small>Please insert higher number</small>");
                //     $(this).closest("tr#price_value").find("a#price_update").hide();
                }else{
                    $(this).closest("td#numberOfPerson").find("small").remove();
                    $(this).closest("tr#price_value").find("a#price_update").show();
                }
            });
            // PRICE ADD MORE
            var priceC = {{count($data->prices)}};
            $("#add_price_button").click(function(){
                priceC++;
                var me = $(this).closest("div#price_list").find("div#price_row:last").clone().insertAfter("div#price_row:last");
                me.find("input#price_list_field1").removeAttr("name").attr("name","price["+priceC+"][people]").mask('0000', {reverse: true});
                me.find("input#price_list_field2").removeAttr("name").attr("name","price["+priceC+"][IDR]").val(null).mask('0.000.000.000', {reverse: true});
                me.find("input#price_list_field3").removeAttr("name").attr("name","price["+priceC+"][USD]").val(null).mask('0.000.000.000', {reverse: true});;
                me.find("button#add_price_button").removeAttr("id").attr("id","price_delete").text("Del").removeAttr("class").addClass("col-md-2 btn bg-red waves-effect extended-button");
            });
            // FIRST ROW COSTUME
            $("tr#price_value").eq(0).find("a#price_delete").remove();
            // EDIT MODE
            $("tr#price_value").each(function(){
                $(this).find("a#price_edit").click(function(){
                    $(this).hide();
                    $(this).siblings("a#price_update").show();
                    $(this).closest("tr#price_value").find("p").hide();
                    $(this).closest("tr#price_value").find("input").show();
                });
            });
            // UPDATE PRICE
            $(document).on("click","a#price_update",function(){
                var me = $(this).closest("td#action");
                var id = $(this).closest("tr#price_value").attr('data-id');
                var number_of_person = $(this).closest("tr#price_value").find("input#price_list_field1").val();
                var price_idr =  $(this).closest("tr#price_value").find("input#price_list_field2").val();
                var price_usd = $(this).closest("tr#price_value").find("input#price_list_field3").val();
                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                $.ajax({
                    method: "POST",
                    url: "{{ url('product/tour-activity/price/update') }}",
                    data: { 
                        id: id,
                        number_of_person: number_of_person,
                        price_idr: price_idr,
                        price_usd: price_usd, 
                    }
                }).done(function(response) {
                    me.closest("tr#price_value").attr('data-id',response.data.price.id);
                    me.closest("tr#price_value").find("input#price_list_field1").attr('late-data',response.data.price.number_of_person);
                    me.closest("tr#price_value").find("input#price_list_field1").attr('value',response.data.price.number_of_person);
                    if($("#priceType").val() == 1){
                        me.closest("tr#price_value").find("td#numberOfPerson p").text(response.data.price.number_of_person);
                    }else{
                        me.closest("tr#price_value").find("td#numberOfPerson p").text('>= '+response.data.price.number_of_person);
                    }
                    
                    me.closest("tr#price_value").find("td#price_idr p").text(parseInt(response.data.price.price_idr));
                    if(response.data.price.price_usd == null){
                        me.closest("tr#price_value").find("td#price_usd p").text(0);
                    }else{
                        me.closest("tr#price_value").find("td#price_usd p").text(parseInt(response.data.price.price_usd));
                    }
                    var max_pep = 0;
                    if("{{$data->max_person}}" != 0){
                        max_pep = "{{$data->max_person}}";
                    };
                    if(response.data.max_pep == max_pep){
                        $("#price_list").hide();
                    }else{
                        if($("#priceType").val() != 1){
                            $("#price_list").show();
                        }
                    }
                    priceL =[];
                    me.find("a#price_edit").show();
                    me.find("a#price_update").hide();
                    me.closest("tr#price_value").find("p").show();
                    me.closest("tr#price_value").find("input").hide();
                });
                // validate price_usd
                var priceU = [];
                $("tr#price_value").each(function(){
                    var pU = $(this).find("td#price_usd #price_list_field3").val();
                    priceU.push(pU); 
                });
                if(priceU.indexOf("0") == -1){
                    $("#submit_price").removeAttr("disabled");
                    $(".alert-price").hide();
                }
            });
            // DEL PRICE
            $(document).on("click", "button#price_delete", function() {
                $(this).closest("div#price_row").remove();
                $("button#submit_price").removeAttr('disabled');
                $("button#add_price_button").removeAttr("disabled");
            });
            // PRICE INCLUDE
            $("select[name='price_includes[]']").select2({
                tags:true
            });
        // PRICE EXCLUDE
            $("select[name='price_excludes[]']").select2({
                tags:true
            });
        // CANCELLATION 
            $("input[name='cancellation_type']").change(function () {
                var cancelType = $(this).val();
                if(cancelType == 2){
                    $("#cancel_policy").show();
                }else{
                    $("#cancel_policy").hide();
                }
            });
            
            
            // 
            
        });
        $(document).on('ready', function() {
            var url_activity = [];var url_accommodation = [];var url_other = [];var url_destination = [];
            var config_activity = [];var config_accommodation = [];var config_other = [];var config_destination = [];
            @if(count($data->image_activity) != 0)
            @foreach($data->image_activity as $index => $img)
                url_activity[{{$index}}] = "{{cdn($img->path.'/'.$img->filename)}}";
                config_activity[{{$index}}] = {caption:'{{$img->filename}}', filename:"{{$img->filename}}",downloadUrl:"{{cdn($img->path.'/'.$img->filename)}}",width:'120px', key:{{$img->id}} };
            @endforeach
            @endif
            @if(count($data->image_destination) != 0)
            @foreach($data->image_destination as $index => $img)
                url_destination[{{$index}}] = "{{cdn($img->path.'/'.$img->filename)}}";
                config_destination[{{$index}}] = {caption:'{{$img->filename}}', filename:"{{$img->filename}}",downloadUrl:"{{cdn($img->path.'/'.$img->filename)}}",width:'120px', key:{{$img->id}} };
            @endforeach
            @endif
            @if(count($data->image_accommodation) != 0)
            @foreach($data->image_accommodation as $index => $img)
                url_accommodation[{{$index}}] = "{{cdn($img->path.'/'.$img->filename)}}";
                config_accommodation[{{$index}}] = {caption:'{{$img->filename}}', filename:"{{$img->filename}}",downloadUrl:"{{cdn($img->path.'/'.$img->filename)}}",width:'120px', key:{{$img->id}} };
            @endforeach
            @endif
            @if(count($data->image_other) != 0)
            @foreach($data->image_other as $index => $img)
                url_other[{{$index}}] = "{{cdn($img->path.'/'.$img->filename)}}";
                config_other[{{$index}}] = {caption:'{{$img->filename}}', filename:"{{$img->filename}}",downloadUrl:"{{cdn($img->path.'/'.$img->filename)}}",width:'120px', key:{{$img->id}} };
            @endforeach
            @endif

            $("#activity_images").fileinput({
                initialPreviewAsData: true,
                initialPreviewConfig:config_activity,
                initialPreview: url_activity,
                maxFileSize: 2000,
                showCaption: false,
                showRemove: false,
                showUpload: true,
                overwriteInitial: false,
                uploadUrl: '/product/upload/image',
                uploadExtraData:{id:{{$data->id}},type:'activity',"_token": "{{ csrf_token() }}"},
                deleteUrl:'/product/delete/image',
                deleteExtraData:{type:'activity',"_token": "{{ csrf_token() }}"},
                maxFileCount: 10
            });
            $("#accomodation_images").fileinput({
                initialPreviewAsData: true,
                initialPreviewConfig:config_accommodation,
                initialPreview: url_accommodation,
                maxFileSize: 2000,
                showCaption: false,
                showRemove: false,
                showUpload: true,
                overwriteInitial: false,
                uploadUrl: '/product/upload/image',
                uploadExtraData:{id:{{$data->id}},type:'accommodation',"_token": "{{ csrf_token() }}"},
                deleteUrl:'/product/delete/image',
                deleteExtraData:{type:'accommodation',"_token": "{{ csrf_token() }}"},
                maxFileCount: 10
            });
            $("#other_images").fileinput({
                initialPreviewAsData: true,
                initialPreviewConfig:config_other,
                initialPreview: url_other,
                maxFileSize: 2000,
                showCaption: false,
                showRemove: false,
                showUpload: true,
                overwriteInitial: false,
                uploadUrl: '/product/upload/image',
                uploadExtraData:{id:{{$data->id}},type:'other',"_token": "{{ csrf_token() }}"},
                deleteUrl:'/product/delete/image',
                deleteExtraData:{type:'other',"_token": "{{ csrf_token() }}"},
                maxFileCount: 10
            });
            $("#destination_images").fileinput({
                initialPreviewAsData: true,
                initialPreviewConfig:config_destination,
                initialPreview: url_destination,
                maxFileSize: 2000,
                showCaption: false,
                showRemove: false,
                showUpload: true,
                overwriteInitial: false,
                uploadUrl: '/product/upload/image',
                uploadExtraData:{id:{{$data->id}},type:'destination',"_token": "{{ csrf_token() }}"},
                deleteUrl:'/product/delete/image',
                deleteExtraData:{type:'destination',"_token": "{{ csrf_token() }}"},
                maxFileCount: 10
            });
            var i = {{count($data->videos)}};
            $("#add_more_video").click(function(){
                i++
                $(this).closest("#embed").clone().appendTo("#clone_dinamic_video").addClass("row dinamic_video"+i).find("input").val(null);
                $(".dinamic_video"+i+" .col-md-3").empty().append('<button type="button" id="delete_video" class="btn btn-danger waves-effect"><i class="material-icons">clear</i></button>');
            });
            $(document).on("click", "#delete_video", function() {
                $(this).closest("#embed").remove();
            });
            $("input[name='end_time[]']").mask('00:00');
            $("input[name='start_time[]']").mask('00:00');
            $("input[name='schedule_type']").change(function () {
                $("#schedule_body").show(200);
                $("input[name='schedule_type']").each(function(){
                    $(this).removeAttr('sel');
                });
                scheduleType = $(this).val();
                if(scheduleType == 1){
                    $(this).attr('sel',scheduleType);
                    $(".scheduleDays").show();
                    $(".scheduleHours").removeAttr("required").hide();
                    $(".schedule-activity").slideUp();
                    
                }else if(scheduleType == 2){
                    $(this).attr('sel',scheduleType);
                    $(".scheduleHours").show();
                    $(".scheduleDays").removeAttr("required").hide();
                    $(".schedule-activity").slideUp();
                    
                }else{
                    $(this).attr('sel',scheduleType);
                    $(".scheduleDays, .scheduleHours").removeAttr("required").hide();;
                    $(".schedule-activity").slideDown();
                }
            });
        });
         // EDIT BUTTON
         $('.modal-header').delegate('.btn-back','click', function(e){
            $(this).text('Change Status');
            $(this).removeClass('btn-back');
            $(this).addClass('btn-change-status');
            $('.change-status').hide();
            $('.btn-save').hide();
            $('.log-status-list').show();
        });
        
        $('.modal-header').delegate('.btn-change-status','click', function(e){
            $(this).text('Back');
            $(this).removeClass('btn-change-status');
            $(this).addClass('btn-back');
            $('.change-status').show();
            $('.btn-save').show();
            $('.log-status-list').hide();
        });
        $("button#edit").click(function(){
            $(this).hide()
            $("#sample").show();
            $("#change-status").show();
            $("#submit").show();
            $("input").removeAttr("disabled");
            $("select").removeAttr("disabled");
            $("textarea").removeAttr("disabled");
            $("button#change").closest(".caption").show();
        });
        $("select[name='product_type']").change(function(){
            console.log($(this).val());
            if($(this).val() == 'private'){
                $(this).closest("div").find("a").attr("data-original-title","Private Group");
                $(this).closest("div").find("a").attr("data-content","Within a single commencing schedule, customers can book for their own private group. They won't be grouped with another customers.");
            }else{
                $(this).closest("div").find("a").attr("data-original-title","Open Group");
                $(this).closest("div").find("a").attr("data-content","Within a single commencing schedule, customers will be grouped into one group");
            }
        });
        $("input[name='max_booking_day']").change(function(){
            $("p.form-note").find("b").text($(this).val());
        });
        //TinyMCE
        tinymce.init({
            selector: "textarea#tinymce",
            height: 300,
            plugins: [
                'advlist autolink lists link image charmap preview anchor textcolor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor',
            image_advtab: true
        });
        tinymce.suffix = ".min";
        tinyMCE.baseURL = "{{ asset('plugins/tinymce') }}";


        // UNTUK SELECT DETINATION SAAT UDAH KEPILIH
        $('.city-sel').each(function(){
            var id = $(this).attr('data-id');
            var c = $(this)
            $.ajax({
                method: "GET",
                url: "{{ url('json/findDestination') }}",
                data: {
                city_id: $(this).val()
                }
            }).done(function(response) {
                
                var d = c.closest(".col-city").siblings(".col-destination").find(".destination-sel").attr("destid");
                // $('#'+id+'-destination option').remove();
                // $('#'+id+'-destination option').empty();
                $('#'+id+'-destination').append('<option value="" selected="">-- Select Destination --</option>');
                $.each(response, function (index, value) {  
                    if(d == value.id){
                        $('#'+id+'-destination').append(
                            "<option value="+value.id+" selected>"+value.destination_name+"</option>"
                        );
                    }else{
                        $('#'+id+'-destination').append(
                            "<option value="+value.id+">"+value.destination_name+"</option>"
                        );
                    }
                });
            });
        })
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('API_GOOGLE_MAPS','AIzaSyCs3DPAN9pcNR6CBFXolpNNrE7PIxpbiGA')}}&libraries=places&callback=initMap"
        async defer></script>
    <!-- <script src="{{asset('js/pages/forms/form-wizard.js')}}"></script> -->
@stop
