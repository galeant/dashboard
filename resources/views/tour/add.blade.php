@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <!-- Bootstrap File-Input -->
	<link href="{{ asset('plugins/bootstrap-file-input/css/fileinput.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('plugins/bootstrap-file-input/themes/explorer-fa/theme.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <!-- Tel Input -->
    <link href="{{ asset('plugins/telformat/css/intlTelInput.css') }}" rel="stylesheet">
    <!-- Bootstrap Select2 -->
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <!-- Date range picker -->
    <link href="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/cropper/cropper.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap-file-input/css/fileinput.css') }}" rel="stylesheet">
    <style>
        .row{
            margin:0px;
        }
        .input-group input[type="text"], .input-group .form-control {
            border: solid #555 1px;
            padding-left: 15px;
        }
        .intl-tel-input{
            width: 100%;
        }
        #step{
            margin: 15px;
        }
        #step .col-md-3{
            color: white;
            background-color:#676C56;
            border: solid white 10px;
            padding: 15px;
        }
        #wizard>.col-md-12,#prev{
            display: none;
        }
        fieldset .card{
            box-shadow: none;
            margin-bottom: 0px;
        }
        fieldset .card .header{
            padding: 10px 10px 0px 10px;
        }
        #nav .col-md-3 button{
            width: 100%;
        }
        .error{
            color:red;
            font-size:12px;
        }
    </style>

@stop
@section('main-content')
    <div class="block-header">
        <h2>
            Add New Tour
            <small>Master Data / Tour</small>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Add Tour</h2>
                </div>
                <div class="body">
                @include('errors.error_notification')
                <form id="form-1" method="POST" action="{{ url('product/tour-activity') }}" enctype="multipart/form-data">
                @csrf
                    <div class="row" >
                        <div class="col-md-12" id="general_information">
                            <fieldset>
                                <div class="card">
                                    <div class="header">
                                        <h4>Product Information</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info" >
                                                <h5>Company</h5>
                                                <select name="company_id" id="company" class="form-control">
                                                    @foreach($companies as $company)
                                                    <option value="{{$company->id}}">{{$company->company_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-3 valid-info" >
                                                <h5>Product Category</h5>
                                                <select name="product_category" id="productCategory" class="form-control">
                                                    <option>Activity</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 valid-info">
                                                <h5>Type</h5>
                                                 {!! Form::select('product_type',Helpers::productType(),null,['class' => 'form-control','id'=>'productType']) !!}
                                            </div>
                                            <div class="col-md-6" style="" id="productTypeOpen" hidden>
                                                <h5><b><u>Open Group</u></b><br></h5>
                                                <small>Within a single commencing schedule, customers will be grouped into one group.</small>
                                            </div>
                                            <div class="col-md-6" style="padding:5px" id="productTypePrivate" >
                                                <h5><b><u>Private Group</u></b><br></h5>
                                                <small>Within a single commencing schedule, customers can book for their own private group. They won't be grouped with another customers.</small>
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>Product Name*</h5>
                                                <input type="text" class="form-control" name="product_name" required />
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 10px 3px;">
                                            <div class="col-md-6 cover-image">
                                                <h5>Cover Product Image*</h5>
                                                <div class="dd-main-image">
                                                    <img style="width: 100%" src="http://via.placeholder.com/400x300" id="cover-img">
                                                </div>
                                                <input name="image_resize" type="text" value="" hidden>
                                                <a href="#" id="c_p_picture" class="btn bg-teal btn-block btn-xs waves-effect">Upload Cover Image</a>
                                                <input name="cover_img" id="c_p_file" type='file' style="display: none" accept="image/x-png,image/gif,image/jpeg">
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-3 valid-info">
                                                <h5>Minimum Person*</h5>
                                                <input type="text" class="form-control" name="min_person" id="minPerson" required />
                                            </div>
                                            <div class="col-md-3 valid-info">
                                                <h5>Maximum Person*</h5>
                                                <input type="text" class="form-control" name="max_person" id="maxPerson" required />    
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>Starting Point/Gathering Point(where should your costumer meet you)?*</h5>
                                                <input type="text" id="pac-input" class="form-control" name="meeting_point_address" placeholder="Start typing an address" required />
                                                <input type="hidden" id="geo-lat" class="form-control" name="meeting_point_latitude" />    
                                                <input type="hidden" id="geo-long" class="form-control" name="meeting_point_longitude" />   
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>Meeting Point Notes</h5>
                                                <textarea rows="4" name="meeting_point_note" class="form-control no-resize"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>PIC Name*</h5>
                                                <input type="text" class="form-control" name="pic_name" required>
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>PIC Phone*</h5>
                                                <input type="hidden" class="form-control" id="PICFormat" name="format_pic_phone">	
                                                <input type="text" class="form-control" id="PICPhone" name="pic_phone" required>	
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>Terms & Condition*</h5>
                                                <textarea rows="4" name="term_condition" class="form-control no-resize" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="header">
                                        <h4>Duration & Schedule</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row valid-info" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-12">
                                                <h5>How long is the duration of your tour/activity ?:</h5>
                                            </div>
                                            <div class="col-md-3" style="margin: 5px 3px 0px 3px;">
                                                <input name="schedule_type" type="radio" id="1d" class="radio-col-deep-orange" value="1" sel="1" required checked/>
                                                <label for="1d">Multiple days</label>
                                            </div>
                                            <div class="col-md-3" style="margin: 5px 3px 0px 3px;">
                                                <input name="schedule_type" type="radio" id="2d" class="radio-col-deep-orange" value="2" sel="2"/>
                                                <label for="2d">A couple of hours</label>
                                            </div>
                                            <div class="col-md-3" style="margin: 5px 3px 0px 3px;">
                                                <input name="schedule_type" type="radio" id="3d" class="radio-col-deep-orange" value="3" sel="3"/>
                                                <label for="3d">One day full</label>
                                            </div>
                                        </div>
                                        <div class="row valid-info" style="margin: 0px 3px 0px 3px;">
                                            <div class="scheduleDays">
                                                <div class="col-md-2 valid-info">
                                                    <h5>Day?* :</h5>
                                                    <select class="form-control" id="day" name="day"  required>
                                                        <option values="" selected>-- Days --</option>
                                                        @for($i=2;$i<24;$i++)
                                                        <option values="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="scheduleHours" hidden>
                                                <div class="col-md-2 valid-info">
                                                    <h5>Hours?* :</h5>
                                                    <select class="form-control" id="hours" name="hours" required>
                                                        <option values="" selected>-- Minutes --</option>
                                                        @for($i=1;$i<12;$i++)
                                                        <option values="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-2 valid-info">
                                                    <h5>Minutes?* :</h5>
                                                    <select class="form-control" id="minutes" name="minutes" required>
                                                        <option values="" selected>-- Minutes --</option>
                                                        @for($i=1;$i<60;$i++)
                                                        <option values="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="schedule_body">
                                            <div class="row" id="dinamic_schedule" style="margin: 0px 3px 0px 3px;">
                                                <div class="col-md-3 valid-info" id="scheduleCol1">
                                                    <h5>Start date*</h5>
                                                    <input type="text" id="scheduleField1" class="form-control" name="schedule[0][startDate]" placeholder="DD-MM-YYYY" required/>
                                                </div>
                                                <div class="col-md-3 valid-info" id="scheduleCol2">
                                                    <h5>End date*</h5>
                                                    <input type="text" id="scheduleField2" class="form-control" name="schedule[0][endDate]" placeholder="DD-MM-YYYY" required readonly/>
                                                </div>
                                                <div class="col-md-2 valid-info" id="scheduleCol3" style="display: none">
                                                    <h5>Start hours *</h5>
                                                    <input type="text" id="scheduleField3" class="form-control" name="schedule[0][startHours]" placeholder="HH:mm:ss" required/>
                                                </div>
                                                <div class="col-md-2 valid-info" id="scheduleCol4" style="display: none">
                                                    <h5>End hours*</h5>
                                                    <input type="text" id="scheduleField4" class="form-control" name="schedule[0][endHours]" placeholder="HH:mm:ss" required readonly/>
                                                </div>
                                                <div class="col-md-3 valid-info" id="scheduleCol5">
                                                    <h5>Max.Booking Date*</h5>
                                                    <input type="text" id="scheduleField5" class="form-control" name="schedule[0][maxBookingDate]" placeholder="DD-MM-YYYY" required/>
                                                </div>
                                                <div class="col-md-2 valid-info" id="scheduleCol6">
                                                    <h5>Max.Booking*</h5>
                                                    <input type="text" id="scheduleField6" class="form-control" name="schedule[0][maximumGroup]" required/>
                                                </div>
                                                <div class="col-md-1" style="padding-top:25px "></div>
                                            </div>
                                            <div id="clone_dinamic_schedule"></div>
                                            <div class="row" style="margin: 20px 3px 0px 3px;">
                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-warning"  id="add_more_schedule" style="outline:none;">
                                                        <i class="fa fa-plus"></i>
                                                        &nbsp;Add Schedule
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="header">
                                        <h4>Tour / Activity Location</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-12">
                                                <h5>List down all destination related to your tour package / activity.</h5>
                                                <h5>The more accurate you list down the destinations, better your product's peformance in search result.</h5>
                                            </div>
                                        </div>
                                        <div class="row" id="dinamic_destination" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-3 valid-info">
                                                <h5>Province*</h5>
                                                <select id="destinationField1" class="form-control" name="place[0][province]" style="width: 100%" required>
                                                    <option value="" selected>-- Select Province --</option>
                                                    @foreach($provinces as $province)
                                                        <option value="{{$province->id}}">{{$province->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 valid-info">
                                                <h5>City*</h5>
                                                <select id="destinationField2" class="form-control" name="place[0][city]" style="width: 100%" required>
                                                    <option value="" selected>-- Select City --</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 valid-info">
                                                <h5>Destination</h5>
                                                <select id="destinationField3" class="form-control" name="place[0][destination]" style="width: 100%">
                                                    <option value="" selected>-- Select Destination --</option>
                                                </select>
                                                <b style="font-size:10px">Leave empty if you can't find the destination</b>
                                            </div>
                                            <div class="col-md-1" style="padding-top:25px "></div>
                                        </div>
                                        <div id="clone_dinamic_destination"></div>
                                            <div class="row" style="margin: 0px 3px 0px 3px;">
                                                <div class="col-md-3">
                                                <button type="button" class="btn btn-warning waves-effect" id="add_more_destination">
                                                    <i class="material-icons icon-align">add</i>
                                                    Add Destination
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="card">
                                    <div class="header">
                                        <h4>Activity Tag</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-md-6 valid-info">
                                                <h5>How would you describe the activities in this product?</h5>
                                                <select class="form-control" name="activity_tag[]" multiple="multiple" style="width: 100%" required>
                                                    @foreach($activities as $activity)
                                                    <option value="{{$activity->id}}">{{$activity->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="header">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h4>Itinerary Detail</h4>
                                            </div>
                                            <div class="col-md-1 col-md-offset-7">
                                                <button type="button" id="generate" class="btn btn-primary waves-effect">
                                                    <i class="material-icons">book</i>
                                                    <span>Generate</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="body" id="itinerary_list" style="display: none">
                                        <h5></h5>
                                        <input id="field_itinerary_input1" type="hidden" />
                                        <div class="row" id="itinerary_row">
                                            <div class="col-md-2 valid-info" id="field_itinerary1">
                                                <h5>Start time*</h5>
                                                <input id="field_itinerary_input2" type="text" class="form-control" placeholder="HH:mm" required />
                                            </div>
                                            <div class="col-md-2 valid-info" id="field_itinerary2">
                                                <h5>End time*</h5>
                                                <input id="field_itinerary_input3" type="text" class="form-control" placeholder="HH:mm" required />
                                            </div>
                                            <div class="col-md-6 valid-info" id="field_itinerary7">
                                                <h5>Description</h5>
                                                <textarea id="field_itinerary_input7" rows="6" class="form-control" placeholder="Description" required></textarea>
                                            </div>
                                            <div class="col-md-1" style="padding-top:35px"></div>
                                        </div>
                                        <div id="clone_dinamic_itinerary"></div>   
                                    </div>
                                    <div id="itineraryGenerate"></div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="card">
                                    <div class="header">
                                        <h4>Pricing Details</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row valid-info">
                                            <div class="col-md-4">
                                                <input name="price_kurs" type="radio" id="1p" class="radio-col-deep-orange" value="1" checked required/>
                                                <label for="1p" style="font-size:15px">I only have pricing in IDR</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input name="price_kurs" type="radio" id="2p" class="radio-col-deep-orange" value="2" />
                                                <label for="2p" style="font-size:15px">I want to add pricing in USD for international tourist</label>
                                            </div>
                                        </div>
                                        <div class="row" id="price_row">
                                            <div class="col-md-3 valid-info">
                                                <h5>Pricing Option</h5>
                                                <select name="price_type" id="priceType" class="form-control" required>
                                                    <option value="1">Fixed Price</option>
                                                    <option value="2">Based on Number of Person</option>
                                                </select>
                                            </div>
                                            <div id="price_fix">
                                                <div class="col-md-3 valid-info" id="price_idr">
                                                    <h5>Price (IDR)*:</h5>
                                                    <input type="hidden" name="price[0][people]" value="fixed"> 
                                                    <input type="text" id="idr" name="price[0][IDR]" class="form-control" required />     
                                                </div>
                                                <div class="col-md-3 valid-info" id="price_usd" style="display: none">
                                                    <h5>Price (USD)*</h5>
                                                    <input type="text" id="usd" name="price[0][USD]" class="form-control" required />     
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card" style="display: none" id="price_table_container">
                                    <div class="header">
                                        <h4>Pricing Tables</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-md-12" id="price_list" style="display: none">
                                                <div class="row">
                                                    <div class="col-md-1" style="padding: 20px 0px 0px 0px;">
                                                        <h5><i class="material-icons">person</i></h5>
                                                    </div>
                                                    <div class="col-md-11">
                                                        <div class="row">
                                                            <div class="col-md-6 valid-info" id="price_idr">
                                                                <h5>Price (IDR)*</h5>
                                                                <input id="price_list_field1" type="hidden" required>  
                                                                <input id="price_list_field2" type="text" class="form-control" required>     
                                                            </div>
                                                            <div class="col-md-6 valid-info" id="price_usd" style="display: none">
                                                                <h5>Price (USD)*</h5>
                                                                <input id="price_list_field3" type="text" class="form-control" required />     
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="price_list_container">
                                                <div class"row">
                                                    <div class="col-md-6" id="price_list_container_left"></div>
                                                    <div class="col-md-6" id="price_list_container_right"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="header"> 
                                        <h4>Price Includes</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-md-6 valid-info">
                                                <h5>What's already included with pricing you have set?What will you provide?</h5>
                                                <h5 style="font-size: 18px">Example: Meal 3 times a day, mineral water, driver as tour guide.</h5>
                                                <select type="text" class="form-control" name="price_includes[]" multiple="multiple" style="width: 100%" required></select>
                                            </div>
                                            <div class="col-md-6" style="padding-top:85px">
                                                <h5>Type a paragraph and press Enter.</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="header"> 
                                        <h4>Price Excludes</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-md-6 valid-info">
                                                <h5>What's not included with pricing you have set?Any extra cost the costumer should be awere of?</h5>
                                                <h5 style="font-size: 18px">Example: Entrance fee IDR 200,000, bicycle rental, etc</h5>
                                                <select class="form-control" name="price_excludes[]" multiple="multiple" style="width: 100%" required></select>
                                            </div>
                                            <div class="col-md-6" style="padding-top:85px">
                                                <h5>Type a paragraph and press Enter.</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="header">
                                        <h4>Cancellation Policy</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row valid-info">
                                            <div class="col-md-3">
                                                <input name="cancellation_type" type="radio" id="1c" class="radio-col-deep-orange" value="1" checked required/>
                                                <label for="1c">No cancellation</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input name="cancellation_type" type="radio" id="2c" class="radio-col-deep-orange" value="2" />
                                                <label for="2c">Free cancellation</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input name="cancellation_type" type="radio" id="3c" class="radio-col-deep-orange" value="3" />
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
                                                    <input type="text" id="cancellationDay" name="min_cancel_day" class="form-control" placeholder="Day" required>
                                                </div>
                                                <div class="col-md-2" style="margin:5px;padding:0px;width:auto">
                                                    <h5>days from shcedule, cancellation fee is</h5>
                                                </div>
                                                <div class="col-md-1 valid-info" style="margin:5px;padding:0px">
                                                    <input type="text" id="cancellationFee" name="cancel_fee" class="form-control" placeholder="Percent" required>
                                                </div>
                                                <div class="col-md-2" style="margin:5px;padding:0px;width:auto">
                                                    <h5>percent(%)</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="card">
                                    <div class="header">
                                        <h4>Image for Destination</h4>
                                    </div>
                                    <div class="body">
                                        <div class="file-loading">
                                            <input id="destination_images" name="destination_images[]" type="file" multiple>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="header">
                                        <h4>Image for Activities</h4>
                                    </div>
                                    <div class="body">
                                        <div class="file-loading">
                                            <input id="activity_images" name="activity_images[]" type="file" multiple>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="header">
                                        <h4>Image for Acommodation</h4>
                                    </div>
                                    <div class="body">
                                        <div class="file-loading">
                                            <input id="accomodation_images" name="accomodation_images[]" type="file" multiple>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="header">
                                        <h4>Others Image</h4>
                                    </div>
                                    <div class="body">
                                        <div class="file-loading">
                                            <input id="other_images" name="other_images[]" type="file" multiple>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-3 col-md-offset-9">
                            <button type="submit" class="btn bg-teal btn-block waves-effect" id="next">
                                <i class="material-icons">save</i>
                                <span>Save</span>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Advanced Validation -->
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
<!-- GMAP -->
    <script>
		var lat,lng,address,city,marker;
		function initAutocomplete() {
			
			var input = document.getElementById('pac-input');
			var searchBox = new google.maps.places.SearchBox(input);
			
			searchBox.addListener('places_changed', function() {
				var places = searchBox.getPlaces();
				console.log(places)
				lat = places[0]["geometry"]["viewport"]["f"]["f"];
				lng = places[0]["geometry"]["viewport"]["b"]["f"];
				address = places[0]["formatted_address"];
				var url =  places[0]["url"]
				document.getElementById('geo-lat').value = lat;
				document.getElementById('geo-long').value = lng;
				if (places.length == 0) {
					return;
				}
				var bounds = new google.maps.LatLngBounds();
				places.forEach(function(place) {
				if (!place.geometry) {
					console.log("Returned place contains no geometry");
					return;
				}
				var icon = {
					url: place.icon,
					size: new google.maps.Size(71, 71),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(17, 34),
					scaledSize: new google.maps.Size(25, 25)
				};
				
				marker = new google.maps.Marker({
					map: map,
					icon: icon,
					title: place.name,
					draggable:true,
					position: place.geometry.location
				});
				google.maps.event.addListener(map, 'click', function(event) {
					lat = event.latLng.lat();
					lng = event.latLng.lng();
					placeMarker(event.latLng);
				});
				if (place.geometry.viewport) {
					bounds.union(place.geometry.viewport);
				} else {
					bounds.extend(place.geometry.location);
				}
				});
				map.fitBounds(bounds);
			});   
		}
		function getLatLng(){
			document.getElementById('geo-lat').value = lat;
			document.getElementById('geo-long').value = lng;
			document.getElementById('geo-address').value = address;
			document.getElementById('geo-city').value = address;
			$('#mapModal').modal('toggle');
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXELYNJkxo43slp8y_FFng0KL4YXSsOo4&libraries=places&callback=initAutocomplete" async defer></script>

    <script src="{{ asset('plugins/cropper/cropper.min.js') }}"></script>  

    <!-- Bootstrap File-Input-Js -->
    <script src="{{ asset('plugins/bootstrap-file-input/js/fileinput.js') }}"></script>
    <!-- Tel format -->
    <script src="{{ asset('plugins/telformat/js/intlTelInput.js') }}"></script>
    <!-- Mask js -->
	<script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script>
    <!-- Jquery Validation Plugin Css -->
	<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <!-- Moment Plugin Js -->
    <script src="{{ asset('plugins/momentjs/moment.js') }}"></script>
    <!-- Bootstrap date range picker -->
    <script src="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- Select2 Plugin Js -->
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("input[name='destination_photo']").fileinput({
            browseClass: "btn btn-primary btn-block",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            maxFileSize: 100,
            initialCaption: "Product Destination Image"
        });
        $("form *").removeAttr("required");
        // VALIDATION
        $('#form-1').validate({
            highlight: function (input) {
                $(input).parents('.valid-info').addClass('error');
            },
            unhighlight: function (input) {
                $(input).parents('.valid-info').removeClass('error');
            },
            errorPlacement: function (error, element) {
                $(element).parents('.valid-info').append(error);
            },
            rules: {
                'confirm': {
                    equalTo: '#password'
                }
            }
        });
        // WIZARD
            
        // MASK
            $("#PICPhone").mask('000-0000-00000');
            $("#minPerson,#maxPerson,#scheduleField6,#cancellationDay,#cancellationFee").mask('0000');
            $("input#idr,input#usd").mask('0.000.000.000', {reverse: true});
            $("#scheduleField3,#scheduleField4,#field_itinerary_input2,#field_itinerary_input3").mask('00:00');
        // DATE PICKER
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
		    $("#scheduleField1").daterangepicker(options).on('apply.daterangepicker', function(ev, picker) {
			  $(this).val(picker.startDate.format('DD-MM-YYYY'));

				var newdate = new Date(picker.startDate.format('YYYY-MM-DD'));
				var scheduledays = day-1;
				newdate.setDate(newdate.getDate()+parseInt(scheduledays))
				if(scheduledays==""){
					$(this).closest("#dinamic_schedule").find("#scheduleField2").val("");
				}
				else{
					var datee = (newdate.getDate() < 10 ? '0' : '') + newdate.getDate();
					var month = ((newdate.getMonth() + 1) < 10 ? '0' : '') + (newdate.getMonth() + 1);
					var year = newdate.getFullYear();
					console.log(datee+"-"+month+"-"+year);
				}
			  $(this).closest("#dinamic_schedule").find("#scheduleField2").val(datee+"-"+month+"-"+year);
			});
			// 
			$("#scheduleField5").daterangepicker({
				autoUpdateInput: false,
			    singleDatePicker: true,
			    autoApply: true,
			    opens: "left",
			    locale: {
		            format: 'DD-MM-YYYY',
		        },
		        minDate: moment().add(0, 'days'),
		        maxDate: moment().add(359, 'days')
			}).on('apply.daterangepicker', function(ev, picker) {
			      $(this).val(picker.startDate.format('DD-MM-YYYY'));
			});
        // TOUR TYPE
            $("#productType").change(function(){
                if($(this).val()=="open"){
                    $("#productTypeOpen").show();
                    $("#productTypePrivate").hide();
                    $("#schedule_body #scheduleCol6").find("h5").text("Max.Person*");
                    $("#schedule_body #scheduleField6")
                        .attr("readonly","readonly")
                        .val($("#maxPerson").val());
                    $("#maxPerson").change(function(){
                        $("#schedule_body").find("input#scheduleField6").each(function(){
                            $(this).val($("#maxPerson").val());
                        });
                    });	
                }else{
                    $("#productTypeOpen").hide();
                    $("#productTypePrivate").show();
                    $("#schedule_body #scheduleCol6").find("h5").text("Max.Booking*");
                    $("#schedule_body #scheduleField6")
                        .removeAttr("readonly")
                        .val(null);
                }
            });
        // PHONE
			$("#PICFormat").val("+62");
			$("#PICPhone").val("+62").intlTelInput({
				separateDialCode: true,
			});
			$(".country").click(function(){
				$(this).closest(".valid-info").find("#PICFormat").val("+"+$(this).attr( "data-dial-code" ));
			});
        // SCHEDULE
            var day = 1,hours= 1,minute=1,maxBooked;
            var scheduleType = $("input[name='schedule_type']").val();
            var dateFormat = 'DD-MM-YYYY';
            var scheduleLength = 0;
            // GET VALUE DAY, HOURS,MINUTES
                $("#day").change(function(){
                    day = $(this).val();
                    $("#dinamic_schedule input:not(#scheduleField6)").val(null);
                    $("#clone_dinamic_schedule").empty();
                });
                $("#hours").change(function(){
                    hours = $(this).val();
                    $("#dinamic_schedule input:not(#scheduleField6)").val(null);
                    $("#clone_dinamic_schedule").empty();
                });
                $("#minutes").change(function(){
                    minutes = $(this).val();
                    $("#dinamic_schedule input:not(#scheduleField6)").val(null);
                    $("#clone_dinamic_schedule").empty();
                });
                $("#dinamic_schedule").find("#scheduleField3").change(function(){
                    var choose = $(this).val();
                    var newtime = new Date(moment(choose,['h:m:a','H:m']));
                    newtime.setHours(newtime.getHours()+parseInt(hours));
                    newtime.setMinutes(newtime.getMinutes()+parseInt(minutes));
                    if(hours=="" || minutes==""){
                        $(this).closest("#dinamic_schedule").find("input#scheduleField4").val("");
                    }
                    else{
                        var hour = (newtime.getHours() < 10 ? '0' : '') + newtime.getHours();
                        var minute = (newtime.getMinutes() < 10 ? '0' : '') + newtime.getMinutes();
                        $(this).closest("#dinamic_schedule").find("input#scheduleField4").val(hour+":"+minute);
                    }
                });
            // SCHEDULE TYPE
            $("input[name='schedule_type']").change(function () {
                $("#schedule_body").show(200);
                $("input[name='schedule_type']").each(function(){
                    $(this).removeAttr('sel');
                });
                scheduleType = $(this).val();
                if(scheduleType == 1){
                    $(this).attr('sel',scheduleType);
                    $("#scheduleCol1").find("h5").text("Start Date*");
                    $("#scheduleCol1, #scheduleCol2, #scheduleCol5, #scheduleCol6, .scheduleDays").show();
                    $("#scheduleCol3, #scheduleCol4, .scheduleHours").removeAttr("required").hide();
                    $("#clone_dinamic_schedule").empty();
                    $("#dinamic_schedule input").val(null);
                    // 
                    $("#dinamic_schedule").find("#scheduleField1").daterangepicker({
                        autoUpdateInput: false,
                        singleDatePicker: true,
                        autoApply: true,
                        locale: {
                            format: dateFormat,
                        },
                        minDate: moment().add(1, 'days'),
                        maxDate: moment().add(359, 'days'),
                        opens: "right"
                    }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD-MM-YYYY'));
                        var newdate = new Date(picker.startDate.format('YYYY-MM-DD'));
                        var scheduledays = day-1;
                        newdate.setDate(newdate.getDate()+parseInt(scheduledays))
                        if(scheduledays==""){
                            $(this).closest("#dinamic_schedule").find("#scheduleField2").val("");
                        }
                        else{
                            var datee = (newdate.getDate() < 10 ? '0' : '') + newdate.getDate();
                            var month = ((newdate.getMonth() + 1) < 10 ? '0' : '') + (newdate.getMonth() + 1);
                            var year = newdate.getFullYear();
                            $(this).closest("#dinamic_schedule").find("#scheduleField2").val(datee+"-"+month+"-"+year);
                        }
                        
                    });
                    // 
                    if($("#productType").val() == "open"){
                        $("#schedule_body").find("input#scheduleField6").each(function(){
                            $(this).val($("#maxPerson").val());
                        });
                    }
                }else if(scheduleType == 2){
                    $(this).attr('sel',scheduleType);
                    $("#scheduleCol1").find("h5").text("Date*");
                    $("#scheduleCol1, #scheduleCol3, #scheduleCol4, #scheduleCol6, .scheduleHours").show();
                    $("#scheduleCol2, #scheduleCol5, .scheduleDays").removeAttr("required").hide();
                    $("#clone_dinamic_schedule").empty();
                    $("#dinamic_schedule input").val(null);
                    // 
                    $("#dinamic_schedule").find("#scheduleField1").daterangepicker({
                        autoUpdateInput: false,
                        singleDatePicker: true,
                        autoApply: true,
                        locale: {
                            format: dateFormat,
                        },
                        minDate: moment().add(1, 'days'),
                        maxDate: moment().add(359, 'days'),
                        opens: "right"
                    }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD-MM-YYYY'));
                    });
                    // 
                    if($("#productType").val() == "open"){
                        $("#schedule_body").find("input#scheduleField6").each(function(){
                            $(this).val($("#maxPerson").val());
                        });
                    }
                }else{
                    $(this).attr('sel',scheduleType);
                    $("#scheduleCol1").find("h5").text("Date*");  
                    $("#scheduleCol2, #scheduleCol3, #scheduleCol4, .scheduleDays, .scheduleHours").removeAttr("required").hide();;
                    $("#scheduleCol1, #scheduleCol5").show();
                    $("#clone_dinamic_schedule").empty();
                    $("#dinamic_schedule input").val(null);
                    // 
                    $("#dinamic_schedule").find("#scheduleField1").daterangepicker({
                        autoUpdateInput: false,
                        singleDatePicker: true,
                        autoApply: true,
                        locale: {
                            format: dateFormat,
                        },
                        minDate: moment().add(1, 'days'),
                        maxDate: moment().add(359, 'days'),
                        opens: "right"
                    }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD-MM-YYYY'));
                    });
                    // 
                    if($("#productType").val() == "open"){
                        $("#schedule_body").find("input#scheduleField6").each(function(){
                            $(this).val($("#maxPerson").val());
                        });
                    }
                }
            });
            // ADD MORE
            $("#add_more_schedule").click(function(){
                scheduleLength++;
                var length = $("#clone_dinamic_schedule").find("#scheduleField2").length;
                if(scheduleType == 1){
                    if(length != 0){
                        var minDate = $("#clone_dinamic_schedule").find("#scheduleField2").last().val();
                        var minDate = minDate.split("-").reverse().join("-");
                    }else{
                        var minDate = $("#dinamic_schedule").find("#scheduleField2").last().val();	
                        var minDate = minDate.split("-").reverse().join("-");
                    }
                }else{
                    if(length != 0){
                        var minDate = $("#clone_dinamic_schedule").find("#scheduleField1").last().val();
                        var minDate = minDate.split("-").reverse().join("-");
                    }else{
                        var minDate = $("#dinamic_schedule").find("#scheduleField1").last().val();	
                        var minDate = minDate.split("-").reverse().join("-");
                    }
                }
                $("#dinamic_schedule").clone().appendTo("#clone_dinamic_schedule").addClass("row dinamic_schedule"+scheduleLength);
                $(".dinamic_schedule"+scheduleLength+" .col-md-1").append('<button type="button" id="delete_schedule" class="btn btn-danger waves-effect"><i class="material-icons">clear</i></button>');
                $(".dinamic_schedule"+scheduleLength+" #scheduleField1").attr("name","schedule["+scheduleLength+"][startDate]")
                    .daterangepicker({
                        autoUpdateInput: false,
                        singleDatePicker: true,
                        autoApply: true,
                        locale: {
                            format: dateFormat,
                        },
                        minDate: moment(minDate),
                        maxDate: moment().add(359, 'days'),
                        opens: "right"
                    }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD-MM-YYYY'));
                        var newdate = new Date(picker.startDate.format('YYYY-MM-DD'));
                    var scheduledays = day-1;
                    newdate.setDate(newdate.getDate()+parseInt(scheduledays))
                    if(scheduledays==""){
                        $(this).closest("#dinamic_schedule").find("#scheduleField2").val("");
                    }
                    else{
                        var datee = (newdate.getDate() < 10 ? '0' : '') + newdate.getDate();
                        var month = ((newdate.getMonth() + 1) < 10 ? '0' : '') + (newdate.getMonth() + 1);
                        var year = newdate.getFullYear();
                        console.log(datee+"-"+month+"-"+year);
                    }
                    $(this).closest("#dinamic_schedule").find("#scheduleField2").val(datee+"-"+month+"-"+year);
                    });
                $(".dinamic_schedule"+scheduleLength+" #scheduleField2").attr("name","schedule["+scheduleLength+"][endDate]");
                $(".dinamic_schedule"+scheduleLength+" #scheduleField3").attr("name","schedule["+scheduleLength+"][startHours]").mask('00:00').change(function(){
                    var choose = $(this).val();
                    var newtime = new Date(moment(choose,['h:m:a','H:m']));
                    newtime.setHours(newtime.getHours()+parseInt(hours));
                    newtime.setMinutes(newtime.getMinutes()+parseInt(minutes));
                    if(hours=="" || minutes==""){   
                        $(this).closest("#dinamic_schedule").find("input#scheduleField4").val("");
                    }
                    else{
                        var hour = (newtime.getHours() < 10 ? '0' : '') + newtime.getHours();
                        var minute = (newtime.getMinutes() < 10 ? '0' : '') + newtime.getMinutes();
                        $(this).closest("#dinamic_schedule").find("input#scheduleField4").val(hour+":"+minute);
                    }
                });
                $(".dinamic_schedule"+scheduleLength+" #scheduleField4").attr("name","schedule["+scheduleLength+"][endHours]").mask('00:00');
                $(".dinamic_schedule"+scheduleLength+" #scheduleField5").attr("name","schedule["+scheduleLength+"][maxBookingDate]").daterangepicker({
                    autoUpdateInput: false,
                    singleDatePicker: true,
                    autoApply: true,
                    opens: "left",
                    locale: {
                        format: 'DD-MM-YYYY',
                    },
                    minDate: moment().add(0, 'days'),
                    maxDate: moment().add(359, 'days')
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD-MM-YYYY'));
                });
                $(".dinamic_schedule"+scheduleLength+" #scheduleField6").attr("name","schedule["+scheduleLength+"][maximumGroup]");
                $(".dinamic_schedule"+scheduleLength+" input:not(#scheduleField6)").val(null);
            });
            $(document).on("click", "#delete_schedule", function() {
                $(this).closest("#dinamic_schedule").remove();
            }); 
        // DESTINATION
            var destLength = 0;
            // CITY APPEND
            $("#destinationField1").change(function(){
				$(this).closest("#dinamic_destination").find("#destinationField2").empty();
				var me = $(this);
				$.ajax({
				  method: "GET",
				  url: "{{ url('json/findCity') }}",
				  data: {
                    id: $(this).val()
				  }
				}).done(function(response) {
					$.each(response, function (index, value) {
						me.closest("#dinamic_destination").find("#destinationField2").append(
							"<option value="+value.id+">"+value.name+"</option>"
						);
					});
				});
			});
            // DESTINATION APPEND
            $("#destinationField2").change(function(){
				$(this).closest("#dinamic_destination").find("#destinationField3").empty();
				var me2 = $(this);
				var province = $(this).closest("#dinamic_destination").find("#destinationField1").val();
				$.ajax({
				  method: "GET",
				  url: "{{ url('/destination') }}",
				  data: {
				  	city: $(this).val(),
				  	province: province
				  }
				}).done(function(response) {
					me2.closest("#dinamic_destination").find("#destinationField3").append(
						"<option value=''></option>"
					);
					$.each(response, function (index, value) {
						me2.closest("#dinamic_destination").find("#destinationField3").append(
							"<option value="+value.destinationId+">"+value.destination+"</option>"
						);
					});
				});
			});
            // ADD MORE DESTIANTION
            $("#add_more_destination").click(function(){
                destLength++;
                $("#dinamic_destination").clone().appendTo("#clone_dinamic_destination").addClass("row dinamic_destination"+destLength);
                $(".dinamic_destination"+destLength).find("#destinationField1").removeAttr("name").attr("name","place["+destLength+"][province]").change(function(){
                    $(this).closest("#dinamic_destination").find("#destinationField2").empty();
                    var me = $(this);
                    $.ajax({
                        method: "GET",
                        url: "{{ url('json/findCity') }}",
                        data: { province_id: $(this).val() }
                    }).done(function(response) {
                        $.each(response, function (index, value) {
                            me.closest("#dinamic_destination").find("#destinationField2").append(
                                "<option value="+value.id+">"+value.name+"</option>"
                            );
                        });
                    });
                });
                $(".dinamic_destination"+destLength).find("#destinationField2").removeAttr("name").attr("name","place["+destLength+"][city]").change(function(){
                    $(this).closest("#dinamic_destination").find("#destinationField3").empty();
                    var me2 = $(this);
                    var province = $(this).closest("#dinamic_destination").find("#destinationField1").val();
                    $.ajax({
                        method: "GET",
                        url: "{{ url('/destination') }}",
                        data: {
                        city: $(this).val(),
                        province: province
                        }
                    }).done(function(response) {
                        me2.closest("#dinamic_destination").find("#destinationField3").append(
                            "<option value=''></option>"
                        );
                        $.each(response, function (index, value) {
                            me2.closest("#dinamic_destination").find("#destinationField3").append(
                                "<option value="+value.destinationId+">"+value.destination+"</option>"
                            );
                        });
                    });
                });
                $(".dinamic_destination"+destLength).find("#destinationField3").removeAttr("name").attr("name","place["+destLength+"][destination]");
                $(".dinamic_destination"+destLength+" .col-md-1").append('<button type="button" id="delete_destination" class="btn btn-danger waves-effect"><i class="material-icons">clear</i></button>');
                $(".dinamic_destination"+destLength+" input").val(null);
            });
            $(document).on("click", "#delete_destination", function() {
                $(this).closest("#dinamic_destination").remove();
            });
        // ACTIVITY TAG
            $('select[name="activity_tag[]"]').select2();
        // GENERATE ITINERARY
            $("#generate").click(function(){
                $("#itineraryGenerate").empty();
                $("#itinerary_list").show();
                var scheType = $("input[name='schedule_type']").attr("sel");
                var days, itic;
                if(scheType == 1){
                    days = $("#day").val();
                }else{
                    days = 1;
                }
                for(itic=0;itic<days;itic++){ 
                    $("#itinerary_list").clone().appendTo("#itineraryGenerate").addClass("body itinerary_list"+itic);   
                    $(".itinerary_list"+itic+" h5:first").append("<b>Days "+(itic+1)+"</b>"); 
                    $(".itinerary_list"+itic+" #field_itinerary_input1").attr("name","itinerary["+itic+"][day]").val((itic+1));
                    $(".itinerary_list"+itic+" #field_itinerary_input2").attr("name","itinerary["+itic+"][startTime]").mask("00:00");
                    $(".itinerary_list"+itic+" #field_itinerary_input3").attr("name","itinerary["+itic+"][endTime]").mask("00:00");
                    $(".itinerary_list"+itic+" #field_itinerary_input7").attr("name","itinerary["+itic+"][description]");
                    $(".itinerary_list"+itic+" .row .col-md-3 button").attr("onclick","addItineraryRow("+itic+")");
                }
                $("#itinerary_list").hide();			
            });
        // PRICE
            $("input[name='price_kurs']").change(function () {
                var priceKurs = $(this).val();
                $("#price_row").show();
                if(priceKurs == 1){
                    $("#price_usd, #price_list_container #price_usd").hide();
                    $("#price_usd input, #price_list_container #price_usd input").val(null);
                }else{
                    $("#price_usd, #price_list_container #price_usd").show();
                }
            });
            $("#priceType").change(function () {
            	var maxPerson = $("#maxPerson").val();
                var dif = Math.round(maxPerson/2)-1;
                var prictType = $(this).val();
                if(prictType == 1){
                    $("#price_fix").show();
                    $("#price_table_container").hide();
                    $("#price_list_container_left,#price_list_container_right").empty();
                }else{
                    $("#price_fix").hide();
                    $("#price_table_container, #price_list").show();    
                    // 
                    for(var pric=0;pric<=dif;pric++){ 
                        $("#price_list").clone().appendTo("#price_list_container_left").attr("id","price_list"+pric);
                        $("#price_list"+pric+" .col-md-1 h5").append((pric+1));
                        $("#price_list"+pric+" #price_list_field1").val((pric+1));
                        $("#price_list"+pric+" #price_list_field1").attr("name","price["+pric+"][people]");
                        $("#price_list"+pric+" #price_list_field2").attr("name","price["+pric+"][IDR]").mask('0.000.000.000', {reverse: true});
                        $("#price_list"+pric+" #price_list_field3").attr("name","price["+pric+"][USD]").mask('0.000.000.000', {reverse: true});
                    }
                    // 
                    for(var prik=(dif+1);prik<maxPerson;prik++){ 
                        $("#price_list").clone().appendTo("#price_list_container_right").attr("id","price_list"+prik);
                        $("#price_list"+prik+" .col-md-1 h5").append((prik+1));
                        $("#price_list"+prik+" #price_list_field1").val((prik+1));
                        $("#price_list"+prik+" #price_list_field1").attr("name","price["+prik+"][people]");
                        $("#price_list"+prik+" #price_list_field2").attr("name","price["+prik+"][IDR]").mask('0.000.000.000', {reverse: true});
                        $("#price_list"+prik+" #price_list_field3").attr("name","price["+prik+"][USD]").mask('0.000.000.000', {reverse: true});
                    }
                    $("#price_list").hide();
                }
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
                if(cancelType == 3){
                    $("#cancel_policy").show();
                }else{
                    $("#cancel_policy").hide();
                }
            });
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
        
    });
    </script>
    <script>
        $(document).on('ready', function() {
            $("#destination_images").fileinput({
                initialPreviewAsData: true,
                maxFileSize: 2000,
                showCaption: false,
                showRemove: false,
                showUpload: false
            });
            $("#activity_images").fileinput({
                initialPreviewAsData: true,
                maxFileSize: 2000,
                showCaption: false,
                showRemove: false,
                showUpload: false
            });
            $("#accomodation_images").fileinput({
                initialPreviewAsData: true,
                maxFileSize: 2000,
                showCaption: false,
                showRemove: false,
                showUpload: false
            });
            $("#other_images").fileinput({
                initialPreviewAsData: true,
                maxFileSize: 2000,
                showCaption: false,
                showRemove: false,
                showUpload: false
            });
        });
    </script>

@stop