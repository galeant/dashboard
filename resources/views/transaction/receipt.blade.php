<body>
    {{-- <!--mpdf --}}
    <htmlpageheader name="myheader">
    <hr class="hr-header">
    <div style="height:10px;">

    </div>
    <table width="100%" class="header">
        <tr >
            <td width="50%" style="color:#E17306; ">
                <div>
                    <img class="logo" src="{{url('img/logo.png')}}">
                </div>
                Trip on a whim.<br/>
            </td>
            <td width="50%" style="text-align: right;color:#E17306;">Your Travel Plan Name.<br/>
                <span style="font-weight: bold; font-size: 12pt;">Bersenang-Senang</span>
            </td>
        </tr>
    </table>
    </htmlpageheader>
    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
    
    <htmlpagefooter name="myfooter">
    <hr class="hr-footer">
    </htmlpagefooter>
    <sethtmlpagefooter name="myfooter" value="on" />
    {{-- mpdf--> --}}
    <div class="booked-detail" style="margin:5mm">
        <h3 class=".b-d-header">Itinerary Information</h3>
        <table width="100%">
            <tr >
                <td width="15%">
                    <span class="b-d-detail-header">Booked Number</span>
                </td>
                <td width="35%">
                    <span class="b-d-detail-content">: {{$data->transaction_number}}</span>
                </td>
                <td width="15%">
                    <span class="b-d-detail-header">Email Address</span>
                </td>
                <td width="35%">
                    <span class="b-d-detail-content">: {{$data->customer->email}}</span>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td width="15%">
                    <span class="b-d-detail-header">Contact Person</span>
                </td>
                <td width="35%">
                    <span class="b-d-detail-content">: {{$data->customer->firstname.' '.$data->customer->lastname}}</span>
                </td>
                <td width="15%">
                    <span class="b-d-detail-header">Phone Number</span>
                </td>
                <td width="35%">
                    <span class="b-d-detail-content">: {{$data->customer->phone}}</span>
                </td>
            </tr>
        </table>
    </div>
    <div id="main" style="margin-left:5mm; margin-right:10mm">
    @for($day_at=1; $day_at<= count(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)); $day_at++)
        <img class="circle-start" src="{{url('img/circle-start.png')}}">
        <div class="act-explore">
            <ul class="list-unstyled list-activities" >
                <li class="day-line-sidebar">
                    <div class="m-l-10 m-mt-10">
                        <div class="col-xs-12">
                            <h4>Day {{$day_at}} - Bandung</h4>
                            <h5>{{date('D, d F Y',strtotime(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)[$day_at-1]['date']))}}</h5>
                        </div>
                    </div>
                </li>
                <table width="100%" style="border-left:2px solid #e17306;">
                @if(count($data->booking_tours))
                    @foreach($data->booking_tours as $tour)
                        @if(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)[$day_at-1]['date'] == date('Y-m-d',strtotime($tour->start_date)))
                        <tr>
                            <td rowspan=2 style="border-left:2px solid #e17306; 
                            border-bottom:0; 
                            border-top:0; 
                            margin-right: 40px;
                            padding-right: 40px;
                            padding-bottom: 20px;
                            margin-bottom: 20px;
                            border-right: 0;"  valign="top">
                                <img class="circle-activity" src="{{url('img/circle-activity.png')}}">
                            </td>
                            <td style="
                            padding-bottom: 20px;
                            margin-bottom: 20px;
                                margin-left: -10px;
                                padding-left: -10px;">
                                <span class="activity-start-hour">{{date('H:i', strtotime($tour->start_time))}}</span>
                                <span class="activity-space">|</span>
                                <span class="activity-duration">{{Helpers::diff_in_hours($tour->start_hours, $tour->end_hours)}} hour(s)</span>
                                <span class="activity-space">|</span>
                                <span class="activity-name">{{$tour->tour_name}}</span>
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-desc tr-pb-30" style="margin-right:20px;padding-right:20px;">
                                <span class="a-d-title">Get in touch with:</span>
                                <table width="100%">
                                    <tr>
                                        <td width="40%">
                                            <span>{{$tour->tours->pic_name.' ('.$tour->tours->pic_phone.') '}}</span>
                                        </td>
                                        <td width="60%">
                                            <p class="a-d-get-detail"><i>this is the person in charge who will be meeting you at the location and reach you within 24 hours prior the schedule.</i></p>
                                        </td>
                                    </tr>
                                </table>
                                <span class="a-d-title">Assemble at:</span>
                                <p>{{$tour->tours->meeting_point_address ."\n".$tour->tours->meeting_point_note}}</p>
                                <br>
                                <span class="a-d-title">Activity participant:</span>
                                @if($tour->transaction_id!=1)
                                    <ol class="a-d-participant">
                                    @foreach($data->contact_list as $contact)
                                    <li>{{$contact->firstname.' '.$contact->lastname}}</li>
                                    @endforeach
                                    </ol>
                                @endif
                                <br>
                                <span class="a-d-title">Booking reference number:</span>
                                <p>{{$tour->booking_number}}</p>
                                <br>
                                <br>
                                <br>
                                <br>
                            </td>
                        </tr>
                        <tr height="100%" style="height:50px;">
                            <td colspan=2 width="2%" style="border-left:2px solid #e17306; 
                            border-bottom:0; 
                            border-top:0; 
                            padding-bottom: 40px;
                            border-right: 0;"  valign="top">
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endif
                @if(count($data->booking_hotels))
                    @foreach($data->booking_hotels as $hotel)
                        @if(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)[$day_at-1]['date'] == date('Y-m-d',strtotime($hotel->start_date)))
                        <tr>
                            <td rowspan=2 style="border-left:2px solid #e17306; 
                            border-bottom:0; 
                            border-top:0; 
                            margin-right: 40px;
                            padding-right: 40px;
                            padding-bottom: 20px;
                            margin-bottom: 20px;
                            border-right: 0;"  valign="top">
                                <img class="circle-activity" src="{{url('img/circle-activity.png')}}">
                            </td>
                            <td style="
                            padding-bottom: 20px;
                            margin-bottom: 20px;
                                margin-left: -10px;
                                padding-left: -10px;">
                                <span>Stay the night at </span>
                                <span class="trip-name">{{$hotel->hotel_name}}</span>
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-desc tr-pb-30" style="margin-right:20px;padding-right:20px;">
                                <span class="a-d-title">Room Type:</span>
                                <span class="activity-start-hour">{{$hotel->number_of_rooms}} room(s)</span>
                                <span class="activity-space">|</span>
                                <span class="activity-duration">{{$hotel->room_name}}</span>
                                <br>
                                <span class="a-d-title">Check In \ Check Out Date:</span>
                                <p>{{date('d M Y', strtotime($hotel->start_date))}} \ {{date('d M Y', strtotime($hotel->end_date))}}</p>
                                <br>
                                <span class="a-d-title">Location:</span>
                                <p>{{$hotel->locations}}</p>
                                <br>
                                <span class="a-d-title">Accomodation Contact Number::</span>
                                <p>{{$hotel->hotel_contact_number}}</p>
                                <br>
                                <span class="a-d-title">Booking reference number:</span>
                                <p>{{$hotel->booking_number}}</p>
                            </td>
                        </tr>
                        <tr height="100%" style="height:50px;">
                            <td colspan=2 width="2%" style="border-left:2px solid #e17306; 
                            border-bottom:0; 
                            border-top:0; 
                            padding-bottom: 40px;
                            border-right: 0;"  valign="top">
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endif
                @if(count($data->booking_activities))
                    @foreach($data->booking_activities as $activities)
                        @if(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)[$day_at-1]['date'] == date('Y-m-d',strtotime($activities->start_date)))
                        <tr>
                            <td rowspan=2 style="border-left:2px solid #e17306; 
                            border-bottom:0; 
                            border-top:0; 
                            margin-right: 40px;
                            padding-right: 40px;
                            padding-bottom: 20px;
                            margin-bottom: 20px;
                            border-right: 0;"  valign="top">
                                <img class="circle-activity" src="{{url('img/circle-activity.png')}}">
                            </td>
                            <td style="
                            padding-bottom: 20px;
                            margin-bottom: 20px;
                                margin-left: -10px;
                                padding-left: -10px;">
                                <span class="activity-start-hour">{{date('H:i', strtotime($tour->start_time))}}</span>
                                <span class="activity-space">|</span>
                                <span class="activity-duration">{{Helpers::diff_in_hours($tour->start_hours, $tour->end_hours)}} hour(s)</span>
                                <span class="activity-space">|</span>
                                <span class="activity-name">{{$tour->tour_name}}</span>
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-desc tr-pb-30" style="margin-right:20px;padding-right:20px;">
                                <span class="a-d-title">About This Place:</span>
                                <p>{{$activities->activities->description}}</p>
                                <br>
                                <span class="a-d-title">Location:</span>
                                <p>{{$activities->activities->cities->name .','.$activities->activities->provinces->name}}</p>
                                <p>{{$activities->activities->address}}</p>
                                <br>
                                @if( ($activities->activities->phone_number != NULL || $activities->activities->phone_number != "") && strlen($activities->activities->phone_number) >4)
                                <span class="a-d-title">Phone Number:</span>
                                <p>{{$activities->activities->phone_number}}</p>
                                @endif
                                @foreach($activities->activities->destination_tips as $tips)
                                    <span class="a-d-title">[Tips] {{$tips->question}}:</span>
                                    <p>[Tips] {{$tips->pivot->answer}}</p>
                                    <br>
                                @endforeach
                            </td>
                        </tr>
                        <tr height="100%" style="height:50px;">
                            <td colspan=2 width="2%" style="border-left:2px solid #e17306; 
                            border-bottom:0; 
                            border-top:0; 
                            padding-bottom: 40px;
                            border-right: 0;"  valign="top">
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endif
                @if(count($data->booking_rent_cars))
                    @foreach($data->booking_rent_cars as $rent_car)
                        @if(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)[$day_at-1]['date'] == date('Y-m-d',strtotime($rent_car->start_date)))
                        <tr>
                            <td rowspan=2 style="border-left:2px solid #e17306; 
                            border-bottom:0; 
                            border-top:0; 
                            margin-right: 40px;
                            padding-right: 40px;
                            padding-bottom: 20px;
                            margin-bottom: 20px;
                            border-right: 0;"  valign="top">
                                <img class="circle-activity" src="{{url('img/circle-activity.png')}}">
                            </td>
                            <td style="
                            padding-bottom: 20px;
                            margin-bottom: 20px;
                                margin-left: -10px;
                                padding-left: -10px;">
                                <span class="activity-start-hour">{{date('H:i', strtotime($rent_car->time_from))}}</span>
                                <span class="activity-space">|</span>
                                <span class="activity-duration">{{Helpers::diff_in_hours($rent_car->time_from, $rent_car->time_to)}} hour(s)</span>
                                <span class="activity-space">|</span>
                                <span class="activity-name">{{$rent_car->vehicle_brand}} {{$rent_car->vehicle_name}}</span>
                                <span>({{$rent_car->agency_name}})</span>
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-desc tr-pb-30" style="margin-right:20px;padding-right:20px;">
                                <span class="a-d-title">Reservation Description:</span>
                                @php
                                    $desc = $rent_car->reservation_description;
                                    $array = explode(",", $desc);
                                    $contact_number = current($array);
                                    $location = str_replace($contact_number.",","",$desc);
                                @endphp
                                <p>{{$contact_number}}</p>
                                <div>{{$location}}</div>
                                <br>
                                <span class="a-d-title">Booking reference number:</span>
                                <p>{{$rent_car->booking_number}}</p>
                            </td>
                        </tr>
                        <tr height="100%" style="height:50px;">
                            <td colspan=2 width="2%" style="border-left:2px solid #e17306; 
                            border-bottom:0; 
                            border-top:0; 
                            padding-bottom: 40px;
                            border-right: 0;"  valign="top">
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endif
                </table>
            </ul>
        </div>
    @endfor
    </div>
</body>