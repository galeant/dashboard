<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('planning/main.css')}}">
    <link rel="stylesheet" href="{{url('planning/style.css')}}">
</head>
<body>
        <div id="main">
                <section class="content-wrap">
                    <div class="col-lg-8 col-md-8 col-sm-8" style="border-left:1px solid #e17306; padding-top:10px; 
                    z-index: -1;">
                        <div>
                            <span>Trip Itinerary</span>
                            <h3>Bandung → Bali</h3>
                            <span>17 April 2018 - 21 April 2018</span>
                        </div>
                        <br>
                        <div style="border-left:1px solid #e17306; padding-top:10px; 
                        z-index: -1;">
                                <img src="{{url('img/circle-start.png')}}"
                                    style="height:20px; width:20px;
                                    display: inline-block;
                                    margin-top: -10px;
                                    margin-left: -10px;">
                            <div class="act-explore" style="margin-top:-25px; padding-left:20px;">
                                <ul class="list-unstyled list-activities">
                                    {{-- <li class="list-activities-before"></li> --}}
                                    <span class="list-activities-li"></span>
                                    <li class="">
                                        <span class="review-explore-before list-activities-li-before"></span>
                                        <div class="row no-gutter">
                                            <div class="col-xs-12">
                                                <div class="text-wrapper mb2">
                                                    <div class="act-time">Day 1 - Bandung</div>
                                                    <div class="text-sm">Wed, 18 April 2018</div>
                                                </div>
                                            </div>
                                        </div><!-- row -->
                                    </li>
                                    <span class="list-activities-li"></span>
                                    <li>
                                        <img src="{{url('img/circle-activity.png')}}"
                                        style="height:16px; width:16px;
                                        display: inline-block;
                                        margin-bottom: -16px;
                                        z-index: 999;
                                        display: inline-block;
                                        margin-left: -28px;">
                                        <div class="row no-gutter" style="margin-top:-15px">
                                            <div class="col-lg-2 col-md-2 col-sm-2"><div class="act-time">12:30</div></div>
                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                <div class="panel-trip panel">
                                                    <div class="trip-destination">
                                                        <a class="flex-row flex-center" href="#c1" data-toggle="collapse">
                                                            <div class="box-img">
                                                                <div class="thumb" style="background-image: url(&quot;images/tp01.png&quot;); background-size: cover; background-position: center center;">
                                                                    <img src="images/tp01.png" style="display: none;">
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="info">
                                                                    <div class="text-label text-primary">Arrived In Bandung</div>
                                                                    <h4 class="text-title">Binus - Dipatiukur</h4>
                                                                </div><!-- info -->
                                                                <div class="footer">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                                            <div class="text-label">Departure</div>
                                                                            <div class="text-dark">07:30</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                                            <div class="text-label">Arrival</div>
                                                                            <div class="text-dark">12:30</div>
                                                                        </div><!-- col -->
                                                                    </div><!-- row -->
                                                                </div><!-- footer -->
                                                            </div><!-- panel-body -->
                                                        </a>							
                                                    </div><!-- trip-destination -->
                                                    <div class="collapse collapse-wrapper" id="c1">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-4 col-sm-6">
                                                                    <div class="text-label">Departure</div>
                                                                    <div class="text-dark">07:30<br>Jl. Binus No. 8</div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-6">
                                                                    <div class="text-label">Arrival</div>
                                                                    <div class="text-dark">12:30<br>Jl. Dipatiukur No. 11</div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-6">
                                                                    <div class="text-label">Total Spending</div>
                                                                    <div class="text-dark">2 persons x Rp 170,000<br><b class="text-primary">Rp 340,000</b></div>
                                                                </div>
                                                            </div>
                                                        </div><!-- panel-body -->
                                                        <div class="panel-footer">
                                                            <ul class="footer-nav">
                                                                <li><a href="#!">See Nearby Places</a></li>
                                                                <li><a href="#!">Activity Deals</a></li>
                                                                <li><a href="#!">View on Map</a></li>
                                                                <li><a href="#!">Remove</a></li>
                                                            </ul>
                                                        </div><!-- panel-footer -->
                                                    </div><!-- collapse -->
                                                </div><!-- panel-trip panel -->
                                            </div><!-- col -->
                                        </div><!-- row -->
                                    </li>
                                    <li>
                                        <img src="{{url('img/circle-activity.png')}}"
                                        style="height:16px; width:16px;
                                        display: inline-block;
                                        margin-bottom: -16px;
                                        z-index: 999;
                                        display: inline-block;
                                        margin-left: -28px;">
                                        <div class="row no-gutter" style="margin-top:-15px">
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="act-time">16:00</div>
                                                <div class="text-sm">2hr</div>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                <div class="panel-trip panel">
                                                    <div class="trip-destination">
                                                        <a class="flex-row flex-center" href="#c2" data-toggle="collapse">
                                                            <div class="box-img">
                                                                <div class="thumb" style="background-image: url(&quot;images/img12.jpg&quot;); background-size: cover; background-position: center center;">
                                                                    <img src="images/img12.jpg" style="display: none;">
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="info">
                                                                    <div class="text-label text-primary">Activity</div>
                                                                    <h4 class="text-title">Wild Horse Back Riding</h4>
                                                                    <div class="text-label">Outdoor/Sport, Wildlife</div>
                                                                </div><!-- info -->
                                                                <div class="footer">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                                            <div class="text-label">Location</div>
                                                                            <div class="text-dark">Lembang, Bandung</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                                            <div class="text-label">Person</div>
                                                                            <div class="text-dark">2 persons</div>
                                                                        </div><!-- col -->
                                                                    </div><!-- row -->
                                                                </div><!-- footer -->
                                                            </div><!-- panel-body -->
                                                        </a>								
                                                    </div><!-- trip-destination -->
                                                    <div class="collapse collapse-wrapper" id="c2">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                                    <div class="text-label">Meeting Point</div>
                                                                    <div class="text-dark">Borobudur Hotel - Jl. Borobudur No. 27 Lembangm Bandung</div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                                    <div class="text-label">Total Spending</div>
                                                                    <div class="text-dark">2 persons x Rp 170,000<br><b class="text-primary">Rp 340,000</b></div>
                                                                </div>
                                                            </div>
                                                        </div><!-- panel-body -->
                                                        <div class="panel-footer">
                                                            <ul class="footer-nav">
                                                                <li><a href="#!">See Nearby Places</a></li>
                                                                <li><a href="#!">Activity Deals</a></li>
                                                                <li><a href="#!">View on Map</a></li>
                                                                <li><a href="#!">Remove</a></li>
                                                            </ul>
                                                        </div><!-- panel-footer -->
                                                    </div><!-- collapse -->
                                                </div><!-- panel-trip panel -->
                                            </div><!-- col -->
                                        </div><!-- row -->
                                    </li>
                                    <li>
                                        <img src="{{url('img/circle-activity.png')}}"
                                            style="height:16px; width:16px;
                                            display: inline-block;
                                            margin-bottom: -16px;
                                            z-index: 999;
                                            display: inline-block;
                                            margin-left: -28px;">
                                            <div class="row no-gutter" style="margin-top:-15px">
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="act-time">18:30</div>
                                                <div class="text-sm">2hr</div>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                <div class="panel-trip panel">
                                                    <div class="trip-destination">
                                                        <a class="flex-row flex-center" href="#c3" data-toggle="collapse">
                                                            <div class="box-img">
                                                                <div class="thumb" style="background-image: url(&quot;images/img13.jpg&quot;); background-size: cover; background-position: center center;">
                                                                    <img src="images/img13.jpg" style="display: none;">
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="info">
                                                                    <div class="text-label text-primary">Place</div>
                                                                    <h4 class="text-title">Amazing Art World</h4>
                                                                    <div class="text-label">Museums/Historical Site</div>
                                                                </div><!-- info -->
                                                                <div class="footer">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                                            <div class="text-label">Location</div>
                                                                            <div class="text-dark">Sukasari, Bandung</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                                            <div class="text-label">Open Hour</div>
                                                                            <div class="text-dark">9:00 - 21:00</div>
                                                                        </div><!-- col -->
                                                                    </div><!-- row -->
                                                                </div><!-- footer -->
                                                            </div><!-- panel-body -->
                                                        </a>							
                                                    </div><!-- trip-destination -->
                                                    <div class="collapse collapse-wrapper" id="c3">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                                    <div class="text-label">Address</div>
                                                                    <div class="text-dark">Jl. Setiabudi No. 293, Isola - Sukasari, Bandung 40154</div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                                    <div class="text-label">Ticket Price</div>
                                                                    <div class="text-dark"><b class="text-primary">Rp 340,000</b> for adult<br>
                                                                    <b class="text-primary">Rp 15,000</b> for children</div>
                                                                </div>
                                                            </div>
                                                        </div><!-- panel-body -->
                                                        <div class="panel-footer">
                                                            <ul class="footer-nav">
                                                                <li><a href="#!">See Nearby Places</a></li>
                                                                <li><a href="#!">Activity Deals</a></li>
                                                                <li><a href="#!">View on Map</a></li>
                                                                <li><a href="#!">Remove</a></li>
                                                            </ul>
                                                        </div><!-- panel-footer -->
                                                    </div><!-- collapse -->
                                                </div><!-- panel-trip panel -->
                                            </div><!-- col -->
                                        </div><!-- row -->
                                    </li>
                                    <li>
                                        <img src="{{url('img/circle-activity.png')}}"
                                            style="height:16px; width:16px;
                                            display: inline-block;
                                            margin-bottom: -16px;
                                            margin-left: -28px;">
                                        <div class="row no-gutter" style="margin-top:-15px">
                                            <div class="col-xs-12">
                                                <div class="text-wrapper mb1">
                                                    <div class="act-time">Stay the night at</div>
                                                </div>
                                            </div><!-- col -->
                                            <div class="col-xs-12">
                                                <div class="panel-trip panel">
                                                    <div class="trip-destination">
                                                        <a class="flex-row flex-center" href="#c4" data-toggle="collapse">
                                                            <div class="box-img">
                                                                <div class="thumb" style="background-image: url(&quot;images/img14.jpg&quot;); background-size: cover; background-position: center center;">
                                                                    <img src="images/img14.jpg" style="display: none;">
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="info">
                                                                    <div class="rating">
                                                                        <span class="rated"></span>
                                                                        <span class="rated"></span>
                                                                        <span class="rated"></span>
                                                                        <span></span>
                                                                        <span></span>
                                                                    </div>
                                                                    <h4 class="text-title">Rumah Tawa Hotel</h4>
                                                                    <div class="text-sm">Superior Room (Include. Breakfast + Free wifi)</div>
                                                                </div><!-- info -->
                                                                <div class="footer">
                                                                    <div class="row">
                                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                            <div class="text-label">Check In</div>
                                                                            <div class="text-dark">Tue, 17 Apr 2018</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                            <div class="text-label">Check Out</div>
                                                                            <div class="text-dark">Tue, 19 Apr 2018</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                            <div class="text-label">Person</div>
                                                                            <div class="text-dark">2 person(s)</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                            <div class="text-label">Room</div>
                                                                            <div class="text-dark">1 room(s)</div>
                                                                        </div><!-- col -->
                                                                    </div><!-- row -->
                                                                </div><!-- footer -->
                                                            </div><!-- panel-body -->
                                                        </a>								
                                                    </div><!-- trip-destination -->
                                                    <div class="collapse collapse-wrapper" id="c4">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                                    <div class="text-label">Address</div>
                                                                    <div class="text-dark">Taman Cibunut Selatan No. 6 - Sumur Bandung, Bandung 40266</div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                                    <div class="text-label">Total Spending</div>
                                                                    <div class="text-dark">1 room x Rp 1,070,000 (2 nights)<br><b class="text-primary">Rp 1,070,000</b></div>
                                                                </div>
                                                            </div>
                                                        </div><!-- panel-body -->
                                                        <div class="panel-footer">
                                                            <ul class="footer-nav">
                                                                <li><a href="#!">See Nearby Places</a></li>
                                                                <li><a href="#!">Activity Deals</a></li>
                                                                <li><a href="#!">View on Map</a></li>
                                                                <li><a href="#!">Remove</a></li>
                                                            </ul>
                                                        </div><!-- panel-footer -->
                                                    </div><!-- collapse -->
                                                </div><!-- panel-trip panel -->
                                            </div><!-- col -->
                                        </div><!-- row -->
                                    </li>
                                    <li>
                                        <img src="{{url('img/circle-activity.png')}}"
                                            style="height:16px; width:16px;
                                            display: inline-block;
                                            margin-bottom: -16px;
                                            z-index: 999;
                                            display: inline-block;
                                            margin-left: -28px;">
                                            <div class="row no-gutter" style="margin-top:-15px">
                                            <div class="col-xs-12">
                                                <div class="text-wrapper mb1">
                                                    <div class="act-time">Getting arround with</div>
                                                </div>
                                            </div><!-- col -->
                                            <div class="col-xs-12">
                                                <div class="panel-trip panel">
                                                    <div class="trip-destination">
                                                        <a class="flex-row flex-center" href="#c5" data-toggle="collapse">
                                                            <div class="box-img">
                                                                <div class="thumb" style="background-image: url(&quot;images/avanza.jpg&quot;); background-size: cover; background-position: center center;">
                                                                    <img src="images/avanza.jpg" style="display: none;">
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="info">
                                                                    <div class="text-label">Mini MPV</div>
                                                                    <h4 class="text-title">Toyota All New Avanza</h4>
                                                                    <div class="text-sm">With Driver</div>
                                                                </div><!-- info -->
                                                                <div class="footer">
                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                                                            <div class="text-label">Rental Start</div>
                                                                            <div class="text-dark">Tue, 17 Apr 2018</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                                                            <div class="text-label">Rental Start</div>
                                                                            <div class="text-dark">Tue, 19 Apr 2018</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                                                            <div class="text-label">Duration</div>
                                                                            <div class="text-dark">3 day(s)</div>
                                                                        </div><!-- col -->
                                                                    </div><!-- row -->
                                                                </div><!-- footer -->
                                                            </div><!-- panel-body -->
                                                        </a>								
                                                    </div><!-- trip-destination -->
                                                    <div class="collapse collapse-wrapper" id="c5">
                                                        <div class="panel-body">
                                                            <div class="row text-wrapper">
                                                                <div class="col-lg-3 col-md-3 col-sm-3">
                                                                    <h5>Service Detail</h5>
                                                                </div>
                                                                <div class="col-lg-9 col-md-9 col-sm-9">
                                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas feugiat sem purus. Quisque mattis sollicitudin mauris, vel eleifend nisi imperdiet ut. Ut tortor enim, pretium ut suscipit sit amet, interdum at magna. Cras vulputate ut mi hendrerit commodo. Pellentesque vel quam 
                                                                </div>
                                                            </div><!-- row -->
                                                            <div class="space-1"></div>
                                                            <div class="row text-wrapper">
                                                                <div class="col-lg-3 col-md-3 col-sm-3">
                                                                    <h5>Vehicle Detail</h5>
                                                                </div>
                                                                <div class="col-lg-9 col-md-9 col-sm-9">
                                                                    <div class="facility-icon list">
                                                                        <span><img src="images/ic_user_24.png">Seat capacity up to 5 persons (including driver)</span>
                                                                        <span><img src="images/ic_briefcase_24.png">Fits up to baggage</span>
                                                                        <span><img src="images/ic_car.png">Toyota All New Avanza - year 2014</span>
                                                                    </div>	
                                                                </div>
                                                            </div><!-- row -->
                                                        </div>
                                                        <div class="panel-footer">
                                                            <ul class="footer-nav">
                                                                <li><a href="#!">See Nearby Places</a></li>
                                                                <li><a href="#!">Activity Deals</a></li>
                                                                <li><a href="#!">View on Map</a></li>
                                                                <li><a href="#!">Remove</a></li>
                                                            </ul>
                                                        </div><!-- panel-footer -->
                                                    </div><!-- collapse -->
                                                </div><!-- panel-trip panel -->
                                            </div><!-- col -->
                                        </div><!-- row -->
                                    </li>
                                </ul>
                            </div><!-- act-explore -->
                            <img src="{{url('img/circle-start.png')}}"
                                style="height:20px; width:20px;
                                display: inline-block;
                                margin-top: -10px;
                                margin-left: -10px;">
                            <div class="act-explore" style="margin-top:-25px; padding-left:20px;">
                                <ul class="list-unstyled list-activities">
                                    <li class="review-explore">
                                        <div class="row no-gutter" style="margin-top:-15px">
                                            <div class="col-xs-12">
                                                <div class="text-wrapper mb2">
                                                    <div class="act-time">Day 2 - Bandung</div>
                                                    <div class="text-sm">Thu, 19 April 2018</div>
                                                </div>
                                            </div>
                                        </div><!-- row -->
                                    </li>
                                    <li>
                                        <img src="{{url('img/circle-activity.png')}}"
                                            style="height:16px; width:16px;
                                            display: inline-block;
                                            margin-bottom: -16px;
                                            z-index: 999;
                                            display: inline-block;
                                            margin-left: -28px;">
                                        <div class="row no-gutter" style="margin-top:-15px">
                                            <div class="col-xs-12">
                                                <div class="text-wrapper mb2">
                                                    <div class="act-time">No activities added</div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row no-gutter" style="margin-top:-15px">
                                            <div class="col-xs-12">
                                                <div class="text-wrapper mb1">
                                                    <div class="act-time">Stay the night at</div>
                                                </div>
                                            </div><!-- col -->
                                            <div class="col-xs-12">
                                                <div class="panel-trip panel">
                                                    <div class="trip-destination">
                                                        <a class="flex-row flex-center" href="#c6" data-toggle="collapse">
                                                            <div class="box-img">
                                                                <div class="thumb" style="background-image: url(&quot;images/img14.jpg&quot;); background-size: cover; background-position: center center;">
                                                                    <img src="images/img14.jpg" style="display: none;">
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="info">
                                                                    <div class="rating">
                                                                        <span class="rated"></span>
                                                                        <span class="rated"></span>
                                                                        <span class="rated"></span>
                                                                        <span></span>
                                                                        <span></span>
                                                                    </div>
                                                                    <h4 class="text-title">Rumah Tawa Hotel</h4>
                                                                    <div class="text-sm">Superior Room (Include. Breakfast + Free wifi)</div>
                                                                </div><!-- info -->
                                                                <div class="footer">
                                                                    <div class="row">
                                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                            <div class="text-label">Check In</div>
                                                                            <div class="text-dark">Tue, 17 Apr 2018</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                            <div class="text-label">Check Out</div>
                                                                            <div class="text-dark">Tue, 19 Apr 2018</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                            <div class="text-label">Person</div>
                                                                            <div class="text-dark">2 person(s)</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                            <div class="text-label">Room</div>
                                                                            <div class="text-dark">1 room(s)</div>
                                                                        </div><!-- col -->
                                                                    </div><!-- row -->
                                                                </div><!-- footer -->
                                                            </div><!-- panel-body -->
                                                        </a>								
                                                    </div><!-- trip-destination -->
                                                    <div class="collapse collapse-wrapper" id="c6">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                                    <div class="text-label">Address</div>
                                                                    <div class="text-dark">Taman Cibunut Selatan No. 6 - Sumur Bandung, Bandung 40266</div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                                    <div class="text-label">Total Spending</div>
                                                                    <div class="text-dark">1 room x Rp 1,070,000 (2 nights)<br><b class="text-primary">Rp 1,070,000</b></div>
                                                                </div>
                                                            </div>
                                                        </div><!-- panel-body -->
                                                        <div class="panel-footer">
                                                            <ul class="footer-nav">
                                                                <li><a href="#!">See Nearby Places</a></li>
                                                                <li><a href="#!">Activity Deals</a></li>
                                                                <li><a href="#!">View on Map</a></li>
                                                                <li><a href="#!">Remove</a></li>
                                                            </ul>
                                                        </div><!-- panel-footer -->
                                                    </div><!-- collapse -->
                                                </div><!-- panel-trip panel -->
                                            </div><!-- col -->
                                        </div><!-- row -->
                                    </li>
                                    <li>
                                        <div class="row no-gutter" style="margin-top:-15px">
                                            <div class="col-xs-12">
                                                <div class="text-wrapper mb1">
                                                    <div class="act-time">Getting arround with</div>
                                                </div>
                                            </div><!-- col -->
                                            <div class="col-xs-12">
                                                <div class="panel-trip panel">
                                                    <div class="trip-destination">
                                                        <a class="flex-row flex-center" href="#c7" data-toggle="collapse">
                                                            <div class="box-img">
                                                                <div class="thumb" style="background-image: url(&quot;images/avanza.jpg&quot;); background-size: cover; background-position: center center;">
                                                                    <img src="images/avanza.jpg" style="display: none;">
                                                                </div>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="info">
                                                                    <div class="text-label">Mini MPV</div>
                                                                    <h4 class="text-title">Toyota All New Avanza</h4>
                                                                    <div class="text-sm">With Driver</div>
                                                                </div><!-- info -->
                                                                <div class="footer">
                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                                                            <div class="text-label">Rental Start</div>
                                                                            <div class="text-dark">Tue, 17 Apr 2018</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                                                            <div class="text-label">Rental Start</div>
                                                                            <div class="text-dark">Tue, 19 Apr 2018</div>
                                                                        </div><!-- col -->
                                                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                                                            <div class="text-label">Duration</div>
                                                                            <div class="text-dark">3 day(s)</div>
                                                                        </div><!-- col -->
                                                                    </div><!-- row -->
                                                                </div><!-- footer -->
                                                            </div><!-- panel-body -->
                                                        </a>								
                                                    </div><!-- trip-destination -->
                                                    <div class="collapse collapse-wrapper" id="c7">
                                                        <div class="panel-body">
                                                            <div class="row text-wrapper">
                                                                <div class="col-lg-3 col-md-3 col-sm-3">
                                                                    <h5>Service Detail</h5>
                                                                </div>
                                                                <div class="col-lg-9 col-md-9 col-sm-9">
                                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas feugiat sem purus. Quisque mattis sollicitudin mauris, vel eleifend nisi imperdiet ut. Ut tortor enim, pretium ut suscipit sit amet, interdum at magna. Cras vulputate ut mi hendrerit commodo. Pellentesque vel quam 
                                                                </div>
                                                            </div><!-- row -->
                                                            <div class="space-1"></div>
                                                            <div class="row text-wrapper">
                                                                <div class="col-lg-3 col-md-3 col-sm-3">
                                                                    <h5>Vehicle Detail</h5>
                                                                </div>
                                                                <div class="col-lg-9 col-md-9 col-sm-9">
                                                                    <div class="facility-icon list">
                                                                        <span><img src="images/ic_user_24.png">Seat capacity up to 5 persons (including driver)</span>
                                                                        <span><img src="images/ic_briefcase_24.png">Fits up to baggage</span>
                                                                        <span><img src="images/ic_car.png">Toyota All New Avanza - year 2014</span>
                                                                    </div>	
                                                                </div>
                                                            </div><!-- row -->
                                                        </div>
                                                        <div class="panel-footer">
                                                            <ul class="footer-nav">
                                                                <li><a href="#!">See Nearby Places</a></li>
                                                                <li><a href="#!">Activity Deals</a></li>
                                                                <li><a href="#!">View on Map</a></li>
                                                                <li><a href="#!">Remove</a></li>
                                                            </ul>
                                                        </div><!-- panel-footer -->
                                                    </div><!-- collapse -->
                                                </div><!-- panel-trip panel -->
                                            </div><!-- col -->
                                        </div><!-- row -->
                                    </li>
                                </ul>
                            </div><!-- act-explore -->
                        </div>
                        
                        <img src="{{url('img/circle-start.png')}}"
                        style="height:20px; width:20px;
                        display: inline-block;
                        margin-top: -10px;
                        margin-left: -10px;">
                <div class="act-explore" style="margin-top:-25px; padding-left:20px;">
                            <ul class="list-unstyled list-activities">
                                <li class="review-explore">
                                    <img src="{{url('img/circle-activity.png')}}"
                                        style="height:16px; width:16px;
                                        display: inline-block;
                                        margin-bottom: -16px;
                                        z-index: 999;
                                        display: inline-block;
                                        margin-left: -28px;">
                                    <div class="row no-gutter" style="margin-top:-15px">
                                        <div class="col-xs-12">
                                            <div class="text-wrapper mb2">
                                                <div class="text-label text-primary"></div>
                                                <div class="act-time">Day 3 - Bandung/Bali</div>
                                                <div class="text-sm">Fri, 20 April 2018</div>
                                            </div>
                                        </div>
                                    </div><!-- row -->
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="text-wrapper mb2">
                                                <div class="act-time">No activities added</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row no-gutter" style="margin-top:-15px">
                                        <div class="col-xs-12">
                                            <div class="text-wrapper mb1">
                                                <div class="act-time">Stay the night at</div>
                                            </div>
                                        </div><!-- col -->
                                        <div class="col-xs-12">
                                            <div class="panel-trip panel">
                                                <div class="trip-destination">
                                                    <a class="flex-row flex-center" href="#c8" data-toggle="collapse">
                                                        <div class="box-img">
                                                            <div class="thumb" style="background-image: url(&quot;images/img14.jpg&quot;); background-size: cover; background-position: center center;">
                                                                <img src="images/img14.jpg" style="display: none;">
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="info">
                                                                <div class="rating">
                                                                    <span class="rated"></span>
                                                                    <span class="rated"></span>
                                                                    <span class="rated"></span>
                                                                    <span></span>
                                                                    <span></span>
                                                                </div>
                                                                <h4 class="text-title">Rumah Tawa Hotel</h4>
                                                                <div class="text-sm">Superior Room (Include. Breakfast + Free wifi)</div>
                                                            </div><!-- info -->
                                                            <div class="footer">
                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                        <div class="text-label">Check In</div>
                                                                        <div class="text-dark">Tue, 17 Apr 2018</div>
                                                                    </div><!-- col -->
                                                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                        <div class="text-label">Check Out</div>
                                                                        <div class="text-dark">Tue, 19 Apr 2018</div>
                                                                    </div><!-- col -->
                                                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                        <div class="text-label">Person</div>
                                                                        <div class="text-dark">2 person(s)</div>
                                                                    </div><!-- col -->
                                                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                                        <div class="text-label">Room</div>
                                                                        <div class="text-dark">1 room(s)</div>
                                                                    </div><!-- col -->
                                                                </div><!-- row -->
                                                            </div><!-- footer -->
                                                        </div><!-- panel-body -->
                                                    </a>								
                                                </div><!-- trip-destination -->
                                                <div class="collapse collapse-wrapper" id="c8">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                                <div class="text-label">Address</div>
                                                                <div class="text-dark">Taman Cibunut Selatan No. 6 - Sumur Bandung, Bandung 40266</div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                                <div class="text-label">Total Spending</div>
                                                                <div class="text-dark">1 room x Rp 1,070,000 (2 nights)<br><b class="text-primary">Rp 1,070,000</b></div>
                                                            </div>
                                                        </div>
                                                    </div><!-- panel-body -->
                                                    <div class="panel-footer">
                                                        <ul class="footer-nav">
                                                            <li><a href="#!">See Nearby Places</a></li>
                                                            <li><a href="#!">Activity Deals</a></li>
                                                            <li><a href="#!">View on Map</a></li>
                                                            <li><a href="#!">Remove</a></li>
                                                        </ul>
                                                    </div><!-- panel-footer -->
                                                </div><!-- collapse -->
                                            </div><!-- panel-trip panel -->
                                        </div><!-- col -->
                                    </div><!-- row -->
                                </li>
                                <li>
                                    <div class="row no-gutter">
                                        <div class="col-xs-12">
                                            <div class="text-wrapper mb1">
                                                <div class="act-time">Getting arround with</div>
                                            </div>
                                        </div><!-- col -->
                                        <div class="col-xs-12">
                                            <div class="panel-trip panel">
                                                <div class="trip-destination">
                                                    <a class="flex-row flex-center" href="#c9" data-toggle="collapse">
                                                        <div class="box-img">
                                                            <div class="thumb" style="background-image: url(&quot;images/avanza.jpg&quot;); background-size: cover; background-position: center center;">
                                                                <img src="images/avanza.jpg" style="display: none;">
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="info">
                                                                <div class="text-label">Mini MPV</div>
                                                                <h4 class="text-title">Toyota All New Avanza</h4>
                                                                <div class="text-sm">With Driver</div>
                                                            </div><!-- info -->
                                                            <div class="footer">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="text-label">Rental Start</div>
                                                                        <div class="text-dark">Tue, 17 Apr 2018</div>
                                                                    </div><!-- col -->
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="text-label">Rental Start</div>
                                                                        <div class="text-dark">Tue, 19 Apr 2018</div>
                                                                    </div><!-- col -->
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="text-label">Duration</div>
                                                                        <div class="text-dark">3 day(s)</div>
                                                                    </div><!-- col -->
                                                                </div><!-- row -->
                                                            </div><!-- footer -->
                                                        </div><!-- panel-body -->
                                                    </a>								
                                                </div><!-- trip-destination -->
                                                <div class="collapse collapse-wrapper" id="c9">
                                                    <div class="panel-body">
                                                        <div class="row text-wrapper">
                                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                                <h5>Service Detail</h5>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9">
                                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas feugiat sem purus. Quisque mattis sollicitudin mauris, vel eleifend nisi imperdiet ut. Ut tortor enim, pretium ut suscipit sit amet, interdum at magna. Cras vulputate ut mi hendrerit commodo. Pellentesque vel quam 
                                                            </div>
                                                        </div><!-- row -->
                                                        <div class="space-1"></div>
                                                        <div class="row text-wrapper">
                                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                                <h5>Vehicle Detail</h5>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9">
                                                                <div class="facility-icon list">
                                                                    <span><img src="images/ic_user_24.png">Seat capacity up to 5 persons (including driver)</span>
                                                                    <span><img src="images/ic_briefcase_24.png">Fits up to baggage</span>
                                                                    <span><img src="images/ic_car.png">Toyota All New Avanza - year 2014</span>
                                                                </div>	
                                                            </div>
                                                        </div><!-- row -->
                                                    </div>
                                                    <div class="panel-footer">
                                                        <ul class="footer-nav">
                                                            <li><a href="#!">See Nearby Places</a></li>
                                                            <li><a href="#!">Activity Deals</a></li>
                                                            <li><a href="#!">View on Map</a></li>
                                                            <li><a href="#!">Remove</a></li>
                                                        </ul>
                                                    </div><!-- panel-footer -->
                                                </div><!-- collapse -->
                                            </div><!-- panel-trip panel -->
                                        </div><!-- col -->
                                    </div><!-- row -->
                                </li>
                            </ul>
                        </div><!-- act-explore -->
                        <img src="{{url('img/circle-start.png')}}"
                            style="height:20px; width:20px;
                            display: inline-block;
                            margin-top: -10px;
                            margin-left: -10px;">
                    <div class="act-explore" style="margin-top:-25px; padding-left:20px;">
                            <ul class="list-unstyled list-activities">
                                <li class="review-explore">
                                    <img src="{{url('img/circle-activity.png')}}"
                                        style="height:16px; width:16px;
                                        display: inline-block;
                                        margin-bottom: -16px;
                                        z-index: 999;
                                        margin-left: -28px;">
                                    <div class="row no-gutter" style="margin-top:-15px">
                                        <div class="col-xs-12">
                                            <div class="text-wrapper mb2">
                                                <div class="act-time">Day 4 - Bali</div>
                                                <div class="text-sm">Sat, 21 April 2018</div>
                                            </div>
                                        </div>
                                    </div><!-- row -->
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="text-wrapper">
                                                <div class="act-time">No activities added</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div><!-- act-explore -->
                        <img src="{{url('img/circle-start.png')}}"
                            style="height:20px; width:20px;
                            display: inline-block;
                            margin-top: -10px;
                            margin-left: -10px;">
                    <div class="act-explore" style="margin-top:-25px; padding-left:20px;">
                            <ul class="list-unstyled list-activities">
                                <li class="review-explore">
                                    <img src="{{url('img/circle-activity.png')}}"
                                        style="height:16px; width:16px;
                                        display: inline-block;
                                        margin-bottom: -16px;
                                        z-index: 999;
                                        display: inline-block;
                                        margin-left: -28px;">
                                    <div class="row no-gutter" style="margin-top:-15px">
                                        <div class="col-xs-12">
                                            <div class="text-wrapper mb2">
                                                <div class="act-time">Day 5 - Bali</div>
                                                <div class="text-sm">Sun, 22 April 2018</div>
                                            </div>
                                        </div>
                                    </div><!-- row -->
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="text-wrapper">
                                                <div class="act-time">No activities added</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div><!-- act-explore -->
                    </div><!-- col -->
                </section>
            </div>
</body>
</html>

