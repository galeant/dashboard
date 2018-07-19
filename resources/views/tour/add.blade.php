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
                <form method="POST" action="{{ url('master/tour') }}" enctype="multipart/form-data">
                @csrf
                    <div class="row" id="wizard">
                        <div class="col-md-12" id="general_information">
                            <fieldset>
                                <div class="card">
                                    <div class="header">
                                        <h4>Product Information</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-3 valid-info" >
                                                <h5>Product Category</h5>
                                                <select name="productCategory" class="form-control">
                                                    <option>Activity</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 valid-info">
                                                <h5>Type</h5>
                                                <select name="productType" id="productType" class="form-control">
                                                    <option >Open Group</option>
                                                    <option >Private Group</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" style="" id="productTypeOpen">
                                                <h5><b><u>Open Trip</u></b><br></h5>
                                                <small>Within a single commencing schedule, customers will be grouped into one group.</small>
                                            </div>
                                            <div class="col-md-6" style="padding:5px" id="productTypePrivate" hidden>
                                                <h5><b><u>Private Trip</u></b><br></h5>
                                                <small>Within a single commencing schedule, customers can book for their own private group. They won't be grouped with another customers.</small>
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>Product Name*</h5>
                                                <input type="text" class="form-control" name="productName" required />
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-3 valid-info">
                                                <h5>Minimum Person*</h5>
                                                <input type="text" class="form-control" name="minPerson" required />
                                            </div>
                                            <div class="col-md-3 valid-info">
                                                <h5>Maximum Person*</h5>
                                                <input type="text" class="form-control" name="maxPerson" required />    
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>Starting Point/Gathering Point(where should your costumer meet you)?*</h5>
                                                <input type="text" id="pac-input" class="form-control" name="meetingPoint" placeholder="Start typing an address" required />
                                                <input type="hidden" id="geo-lat" class="form-control" name="meetingPointLatitude" />    
                                                <input type="hidden" id="geo-long" class="form-control" name="meetingPointLongitude" />   
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>Meeting Point Notes</h5>
                                                <textarea rows="4" name="meetingPointNotes" class="form-control no-resize"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>PIC Name*</h5>
                                                <input type="text" class="form-control" name="PICName" required>
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>PIC Phone*</h5>
                                                <input type="hidden" class="form-control" name="formatPIC">	
                                                <input type="tel" class="form-control" name="PICPhone" required>	
                                            </div>
                                        </div>
                                        <div class="row" style="margin: 0px 3px 0px 3px;">
                                            <div class="col-md-6 valid-info">
                                                <h5>Terms & Condition*</h5>
                                                <textarea rows="4" name="termCondition" class="form-control no-resize" required></textarea>
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
                                                <input name="scheduleType" type="radio" id="1d" class="radio-col-deep-orange" value="1" sel="1" required checked/>
                                                <label for="1d">Multiple days</label>
                                            </div>
                                            <div class="col-md-3" style="margin: 5px 3px 0px 3px;">
                                                <input name="scheduleType" type="radio" id="2d" class="radio-col-deep-orange" value="2" sel="2"/>
                                                <label for="2d">A couple of hours</label>
                                            </div>
                                            <div class="col-md-3" style="margin: 5px 3px 0px 3px;">
                                                <input name="scheduleType" type="radio" id="3d" class="radio-col-deep-orange" value="3" sel="3"/>
                                                <label for="3d">One day full</label>
                                            </div>
                                        </div>
                                        <div class="row valid-info" style="margin: 0px 3px 0px 3px;">
                                            <div class="scheduleDays">
                                                <div class="col-md-2 valid-info">
                                                    <h5>Day?* :</h5>
                                                    <select class="form-control" name="day" required>
                                                        <option values="">-- Days --</option>
                                                        @for($i=1;$i<24;$i++)
                                                        <option values="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="scheduleHours" hidden>
                                                <div class="col-md-2 valid-info">
                                                    <h5>Hours?* :</h5>
                                                    <select class="form-control" name="hours" required>
                                                        <option values="">-- Minutes --</option>
                                                        @for($i=1;$i<12;$i++)
                                                        <option values="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-2 valid-info">
                                                    <h5>Minutes?* :</h5>
                                                    <select class="form-control" name="minutes" required>
                                                        <option values="">-- Hours --</option>
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
                        </div>
                        
                        <div class="col-md-12" id="activity_detail">
                            <fieldset>
                                <div class="card">
                                    <div class="header">
                                        <h4>Activity Tag</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-md-6 valid-info">
                                                <h5>How would you describe the activities in this product?</h5>
                                                <select class="form-control" name="activityTag[]" multiple="multiple" style="width: 100%" required></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="header">
                                        <h4>Itinerary Detail</h4>
                                    </div>
                                    <div class="body" id="itinerary_list" style="display: none">
                                        <h5></h5>
                                        <input id="field_itinerary_input1" type="hidden" />
                                        <div class="row" id="itinerary_row">
                                            <div class="col-md-2 valid-info" id="field_itinerary1">
                                                <h5>Start time*</h5>
                                                <input id="field_itinerary_input2" type="text" class="form-control" placeholder="HH:mm:ss" required />
                                            </div>
                                            <div class="col-md-2 valid-info" id="field_itinerary2">
                                                <h5>End time*</h5>
                                                <input id="field_itinerary_input3" type="text" class="form-control" placeholder="HH:mm:ss" required />
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
                        </div>
                        
                        <div class="col-md-12" id="price">
                            <fieldset>
                                <div class="card">
                                    <div class="header">
                                        <h4>Pricing Details</h4>
                                    </div>
                                    <div class="body">
                                        <div class="row valid-info">
                                            <div class="col-md-4">
                                                <input name="priceKurs" type="radio" id="1p" class="radio-col-deep-orange" value="1" checked required/>
                                                <label for="1p" style="font-size:15px">I only have pricing in IDR</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input name="priceKurs" type="radio" id="2p" class="radio-col-deep-orange" value="2" />
                                                <label for="2p" style="font-size:15px">I want to add pricing in USD for international tourist</label>
                                            </div>
                                        </div>
                                        <div class="row" id="price_row">
                                            <div class="col-md-3 valid-info">
                                                <h5>Pricing Option</h5>
                                                <select name="priceType" id="price_type" class="form-control" required>
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
                                            <div class="col-md-6" id="price_list" style="display: none">
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
                                                <select type="text" class="form-control" name="priceIncludes[]" multiple="multiple" style="width: 100%" required></select>
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
                                                <select class="form-control" name="priceExcludes[]" multiple="multiple" style="width: 100%" required></select>
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
                                                <input name="cancellationType" type="radio" id="1c" class="radio-col-deep-orange" value="1" checked required/>
                                                <label for="1c">No cancellation</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input name="cancellationType" type="radio" id="2c" class="radio-col-deep-orange" value="2" />
                                                <label for="2c">Free cancellation</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input name="cancellationType" type="radio" id="3c" class="radio-col-deep-orange" value="3" />
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
                                                    <input type="text" name="minCancellationDay" class="form-control" placeholder="Day" required>
                                                </div>
                                                <div class="col-md-2" style="margin:5px;padding:0px;width:auto">
                                                    <h5>days from shcedule, cancellation fee is</h5>
                                                </div>
                                                <div class="col-md-1 valid-info" style="margin:5px;padding:0px">
                                                    <input type="text" name="cancellationFee" class="form-control" placeholder="Percent" required>
                                                </div>
                                                <div class="col-md-2" style="margin:5px;padding:0px;width:auto">
                                                    <h5>percent(%)</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row" style="margin: 20px" id="nav">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary waves-effect" id="prev">
                                <i class="material-icons">add</i>
                                <span>Prev</span>
                            </button>
                        </div>
                        <div class="col-md-3 col-md-offset-6">
                            <button type="button" class="btn btn-primary waves-effect" id="next">
                                <i class="material-icons">add</i>
                                <span>Next</span>
                            </button>
                        </div>
                    </div>
                    
                </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('head-js')
@parent
    <script src="{{asset('plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}"></script>
    <!-- <script src="{{asset('js/pages/tables/jquery-datatable.js')}}"></script> -->

    <!-- Bootstrap File-Input-Js -->
    <script src="{{ asset('plugins/bootstrap-file-input/js/fileinput.js') }}" type="text/javascript"></script>
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
            $("form *").removeAttr("required");
        // WIZARD
            var s = 0;
            var maxStep = $("#wizard>.col-md-12").length-1;
            $("#wizard>.col-md-12").eq(0).show().addClass("active");
            $("#step>.col-md-3").eq(0).css("background-color","#E5730D");
            $("#nav").find("#next").click(function(){
                // if($('#wizard_with_validation').valid()){
                    s++;
                    $("#wizard>.col-md-12").eq(s).show().addClass("active").prev().hide().removeClass("active");
                    $("#step>.col-md-3").eq(s).css("background-color","#E5730D").prevAll().css("background-color","#E5730D");
                    $("#nav").find("#prev").show();
                    if(s== maxStep){
                        $(this).find("span").text("Submit");
                        $(this).find("i").text("clear");
                    }
                    if(s>maxStep){
                        s = maxStep+1;
                        $(this).attr("type","submit");
                    }
                // }
            });
            $("#nav").find("#prev").click(function(){
                s--;
                $("#wizard>.col-md-12").eq(s).show().addClass("active").next().hide().removeClass("active");
                $("#step>.col-md-3").eq(s+1).css("background-color","#676C56").nextAll().css("background-color","#676C56");
                if(s== 0){
                    $(this).hide();
                }
                if(s<maxStep){
                    $("#nav").find("#next").find("span").text("Next");
                    $("#nav").find("#next").find("i").text("add");
                    $("#nav").find("#next").attr("type","button");
                }
            });
        // PROVINCE
            $("select[name='companyProvince']").change(function(){
                var idProvince = $(this).val();
                $("select[name='companyCity']").empty();
                $.ajax({
                    method: "GET",
                    url: "{{ url('/city') }}",
                    data: { id: idProvince  }
                }).done(function(response) {
                    $.each(response, function (index, value) {
                        $("select[name='companyCity']").append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
            });
        // MASK
            $("input[name='companyPhone'],input[name='phone'],input[name='PICPhone']").mask('000-0000-00000');
            $("input[name='bankAccountNumb']").mask('0000000000000000');
            $("input[name='companyPostal']").mask('00000');
            $("input[name='minPerson'],input[name='maxPerson'],#scheduleField6,input[name='minCancellationDay'],input[name='cancellationFee']").mask('0000');
            $("input#idr,input#usd").mask('00000000000000000000000000000');
            $("#scheduleField3,#scheduleField4,#field_itinerary_input2,#field_itinerary_input3").attr("min","00:00:00").attr("max","23:59:59").mask('00:00:00');
        // TOUR TYPE
            $("#productType").change(function(){
                if($(this).val()=="Open Group"){
                    $("#productTypeOpen").show();
                    $("#productTypePrivate").hide();
                    $("#schedule_body #scheduleCol6").find("h5").text("Max.Person*");
                    $("#schedule_body #scheduleField6")
                        .attr("readonly","readonly")
                        .val($("input[name='maxPerson']").val());
                    $("input[name='maxPerson']").change(function(){
                        $("#schedule_body").find("input#scheduleField6").each(function(){
                            $(this).val($("input[name='maxPerson']").val());
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
			$("input[name='formatPIC']").val("+62");
			$("input[name='PICPhone']").val("+62").intlTelInput({
				separateDialCode: true,
			});
			$("input[name='formatPIC']").val("+62");
			$(".country").click(function(){
				$(this).closest(".valid-info").find("input[name='formatPICs']").val("+"+$(this).attr( "data-dial-code" ));
			});
        // DATE PICKER
            var day = 1,hours= 1,minute=1,maxBooked;
			$("select[name='day']").change(function(){
				day = $(this).val();
			});
			$("select[name='day']").change(function(){
				day = $(this).val();
			});
			$("select[name='day']").change(function(){
				day = $(this).val();
			});
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
        // SCHEDULE
            $("select[name='day']").change(function(){
                day = $(this).val();
                $("#dinamic_schedule input").val(null);
                $("#clone_dinamic_schedule").empty();
            });

            $("select[name='hours']").change(function(){
                hours = $(this).val();
                $("#dinamic_schedule input").val(null);
                $("#clone_dinamic_schedule").empty();
            });
            $("select[name='minutes']").change(function(){
                minutes = $(this).val();
                $("#dinamic_schedule input").val(null);
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
            var dateFormat = 'DD-MM-YYYY';
            var type = $("input[name='scheduleType']:radio").val();
            $("input[name='scheduleType']:radio").change(function () {
            $("#schedule_body").show(200);
            $("input[name='scheduleType']").each(function(){
                $(this).removeAttr('sel');
            });
            type = this.value;
            if(type == 1){
                $(this).attr('sel',type);
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
                if($("#productType").val() == "Open Group"){
                    $("#schedule_body").find("input#scheduleField6").each(function(){
                        $(this).val($("input[name='maxPerson']").val());
                    });
                }
            }else if(type == 2){
                $(this).attr('sel',type);
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
                if($("#productType").val() == "Open Group"){
                    $("#schedule_body").find("input#scheduleField6").each(function(){
                        $(this).val($("input[name='maxPerson']").val());
                    });
                }
            }else{
                $(this).attr('sel',type);
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
                if($("#productType").val() == "Open Group"){
                    $("#schedule_body").find("input#scheduleField6").each(function(){
                        $(this).val($("input[name='maxPerson']").val());
                    });
                }
            }
            });
            // ADD MORE
            var sche = 0;
                $("#add_more_schedule").click(function(){
                    sche++;
                    var length = $("#clone_dinamic_schedule").find("#scheduleField2").length;
                    if(type == 1){
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
                    $("#dinamic_schedule").clone().appendTo("#clone_dinamic_schedule").addClass("row dinamic_schedule"+sche);
                    $(".dinamic_schedule"+sche+" .col-md-1").append('<button type="button" id="delete_schedule" class="btn btn-danger waves-effect"><i class="material-icons">clear</i></button>');
                    $(".dinamic_schedule"+sche+" #scheduleField1").attr("name","schedule["+sche+"][startDate]")
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
                        }
                        $(this).closest("#dinamic_schedule").find("#scheduleField2").val(datee+"-"+month+"-"+year);
                        });
                    $(".dinamic_schedule"+sche+" #scheduleField2").attr("name","schedule["+sche+"][endDate]");
                    $(".dinamic_schedule"+sche+" #scheduleField3").attr("name","schedule["+sche+"][startHours]").mask('00:00:00').change(function(){
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
                    $(".dinamic_schedule"+sche+" #scheduleField4").attr("name","schedule["+sche+"][endHours]").mask('00:00:00');
                    $(".dinamic_schedule"+sche+" #scheduleField5").attr("name","schedule["+sche+"][maxBookingDate]").daterangepicker({
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
                    $(".dinamic_schedule"+sche+" #scheduleField6").attr("name","schedule["+sche+"][maximumGroup]");
                    $(".dinamic_schedule"+sche+" #scheduleField1").val(null)
                    $(".dinamic_schedule"+sche+" #scheduleField2").val(null)
                    $(".dinamic_schedule"+sche+" #scheduleField3").val(null)
                    $(".dinamic_schedule"+sche+" #scheduleField4").val(null)
                    $(".dinamic_schedule"+sche+" #scheduleField5").val(null)

                });
                $(document).on("click", "#delete_schedule", function() {
                    $(this).closest("#dinamic_schedule").remove();
                });
        // DESTINATION
            $.ajax({
			  method: "GET",
			  url: "{{ url('/json/province') }}",
			}).done(function(response) {
				$.each(response.data, function (index, value) {
					$("#destinationField1").each(function(){
                        $(this).append("<option value="+value.id+">"+value.name+"</option>");
                    });
				});
			});
			$("#destinationField1").change(function(){
				$(this).closest("#dinamic_destination").find("#destinationField2").empty();
				var me = $(this);
				$.ajax({
				  method: "GET",
				  url: "{{ url('/cities') }}",
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
            var destIt = 0;
            $("#add_more_destination").click(function(){
                destIt++;
                $("#dinamic_destination").clone().appendTo("#clone_dinamic_destination").addClass("row dinamic_destination"+destIt);
                $(".dinamic_destination"+destIt).find("#destinationField1").removeAttr("name").attr("name","place["+destIt+"][province]").change(function(){
	                	$(this).closest("#dinamic_destination").find("#destinationField2").empty();
						var me = $(this);
						$.ajax({
						  method: "GET",
						  url: "{{ url('/cities') }}",
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
                $(".dinamic_destination"+destIt).find("#destinationField2").removeAttr("name").attr("name","place["+destIt+"][city]").change(function(){
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
                $(".dinamic_destination"+destIt).find("#destinationField3").removeAttr("name").attr("name","place["+destIt+"][destination]");
                $(".dinamic_destination"+destIt+" .col-md-1").append('<button type="button" id="delete_destination" class="btn btn-danger waves-effect"><i class="material-icons">clear</i></button>');
                $(".dinamic_destination"+destIt+" select").val(null);
            });
            $(document).on("click", "#delete_destination", function() {
                $(this).closest("#dinamic_destination").remove();
            });
        // ACTIVITY TAG
            $('select[name="activityTag[]"]').select2({
				placeholder: 'Cari...',
				ajax: {
				url: "{{ url('/activity')}}",
				dataType: 'json',
				delay: 0,
				processResults: function (data) {
					console.log(data)
					return {
						results:  $.map(data, function (item) {
						return {
							text: item.name,
							id: item.activityId
							}
						})
					};
				},
				cache: true
				}
			});
        // GENERATE ITINERARY
            $("#nav").find("#next").click(function(){
				$("#itineraryGenerate").empty();
				$("#itinerary_list").show();
				var val = $("input[name='scheduleType']:radio").attr("sel");
				console.log(val);
				if(val == 1){
					$("#itineraryGenerate").empty();
					$("#itinerary_list").show();
					var days = $("select[name='day']").val();
					console.log(days);
					var j = 0;
					var i;
					for(i=0;i<days;i++)
					{ 
						$("#itinerary_list").clone().appendTo("#itineraryGenerate").addClass("body itinerary_list"+i);   
						$(".itinerary_list"+i+" h5:first").append("<b>Days "+(i+1)+"</b>"); 
						$(".itinerary_list"+i+" #field_itinerary_input1").attr("name","itinerary["+i+"][day]").val((i+1));
						$(".itinerary_list"+i+" #field_itinerary_input2").attr("name","itinerary["+i+"][list][0][startTime]").mask('00:00:00');
						$(".itinerary_list"+i+" #field_itinerary_input3").attr("name","itinerary["+i+"][list][0][endTime]").mask("00:00:00");
						$(".itinerary_list"+i+" #field_itinerary_input7").attr("name","itinerary["+i+"][list][0][description]");
						$(".itinerary_list"+i+" .row .col-md-3 button").attr("onclick","addItineraryRow("+i+")");
					}
					$("#itinerary_list").hide();
				}else{
					var days = 1;
					var j = 0;
					var i;
					for(i=0;i<days;i++)
					{ 
						$("#itinerary_list").clone().appendTo("#itineraryGenerate").addClass("body itinerary_list"+i);   
						$(".itinerary_list"+i+" h5:first").append("<b>Days "+(i+1)+"</b>"); 
						$(".itinerary_list"+i+" #field_itinerary_input1").attr("name","itinerary["+i+"][day]").val((i+1));
						$(".itinerary_list"+i+" #field_itinerary_input2").attr("name","itinerary["+i+"][list][0][startTime]").mask('00:00:00');
						$(".itinerary_list"+i+" #field_itinerary_input3").attr("name","itinerary["+i+"][list][0][endTime]").mask('00:00:00');
						$(".itinerary_list"+i+" #field_itinerary_input7").attr("name","itinerary["+i+"][list][0][description]");
						$(".itinerary_list"+i+" .row .col-md-3 button").attr("onclick","addItineraryRow("+i+")");
					}
				}
				$("#itinerary_list").hide();
			});
        // PRICE
            $("input[name='priceKurs']:radio").change(function () {
                $("#price_row").show();
                var val = this.value;
                if(val == 1){
                    $("#price_usd, #price_list_container #price_usd").hide();
                    $("#price_usd input, #price_list_container #price_usd input").val(null);
                }else{
                    $("#price_usd, #price_list_container #price_usd").show();
                }
            });
            $("#price_type").change(function () {
            	var maxPerson = $("input[name='maxPerson']:text").val();
                var dif = Math.round(maxPerson/2)-1;
                var val = $(this).val();
                if(val == 1)
                {
                    $("#price_fix").show();
                    $("#price_table_container").hide();
                    $("#price_list_container_left,#price_list_container_right").empty();
                }else{
                    $("#price_fix").hide();
                    $("#price_table_container, #price_list").show();    
                    for(var i=0;i<=dif;i++){ 
                        $("#price_list").clone().appendTo("#price_list_container_left").attr("id","price_list"+i);
                        $("#price_list"+i+" .col-md-1 h5").append((i+1));
                        $("#price_list"+i+" #price_list_field1").val((i+1));
                        $("#price_list"+i+" #price_list_field1").attr("name","price["+i+"][people]");
                        $("#price_list"+i+" #price_list_field2").attr("name","price["+i+"][IDR]").mask('0.000.000.000', {reverse: true});
                        $("#price_list"+i+" #price_list_field3").attr("name","price["+i+"][USD]").mask('0.000.000.000', {reverse: true});
                    }

                    for(var i=(dif+1);i<maxPerson;i++){ 
                        $("#price_list").clone().appendTo("#price_list_container_right").attr("id","price_list"+i);
                        $("#price_list"+i+" .col-md-1 h5").append((i+1));
                        $("#price_list"+i+" #price_list_field1").val((i+1));
                        $("#price_list"+i+" #price_list_field1").attr("name","price["+i+"][people]");
                        $("#price_list"+i+" #price_list_field2").attr("name","price["+i+"][IDR]").mask('0.000.000.000', {reverse: true});
                        $("#price_list"+i+" #price_list_field3").attr("name","price["+i+"][USD]").mask('0.000.000.000', {reverse: true});
                    }
                    $("#price_list").hide();
                }
            });
            $("input[name='cancellationType']:radio").change(function () {
            var val = this.value;
            if(val == 3)
            {
                $("#cancel_policy").show(100);
            }else{
                $("#cancel_policy").hide(100);
            }
            });
            $("select[name='priceIncludes[]']").select2({
                tags:true
            });
            $("select[name='priceExcludes[]']").select2({
                tags:true
            });
        });
    </script>

@stop