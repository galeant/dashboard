<body>
    {{-- <!--mpdf --}}
    <htmlpageheader name="myheader">
    <hr class="hr-header">
    <div style="height:10px;">

    </div>
    <table width="100%" class="header">
        <tr >
            <td width="50%" style="color:#E17306;" valign="top">
                <div>
                    <img class="logo" src="{{url('img/logo.png')}}">
                </div>
            </td>
            <td width="50%" style="text-align: right;">
                <h1>E-TIKET</h1>
                <span style="font-size: 8pt;">Booking No. {{$data->booking_number}}</span><br>
                <span style="font-size: 8pt;">Generate on:  {{date('d F Y, h:i')}}</span>
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
    <div style="padding-top:1mm;">

    </div>
    <div class="booked-detail" style="margin:10mm;">

        <table width="100%">
            <tr >
                <td width="50%">
                    <span class="b-d-detail-header">Booked Reference Number:</span>
                </td>
                <td width="50%" style="text-align: right;">
                    <h3>{{$data->booking_number}}</h3>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr width="100%">
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <span class="b-d-detail-header">Contact Detail:</span>
                </td>
                <td width="50%" style="text-align: right;">
                    <span class="b-d-detail-content">
                        @if($data->user_contact_id == 0)
                            {{$data->transactions->customer->salutation}}. {{$data->transactions->customer->firstname}} {{$data->transactions->customer->lastname}}
                        @else
                            {{$data->transactions->contact->salutation}}. {{$data->transactions->contact->firstname}} {{$data->transactions->contact->lastname}}
                        @endif
                    </span>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    &nbsp;
                </td>
                <td width="50%" style="text-align: right;">
                    <span class="b-d-detail-content">
                        @if($data->user_contact_id == 0)
                            {{$data->transactions->customer->email}}
                        @else
                            {{$data->transactions->contact->email}}
                        @endif
                    </span>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    &nbsp;
                </td>
                <td width="50%" style="text-align: right;">
                    <span class="b-d-detail-content">
                        @if($data->user_contact_id == 0)
                            {{$data->transactions->customer->phone}}
                        @else
                            {{$data->transactions->contact->phone}}
                        @endif
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <div id="main" style="margin-left:15mm; margin-right:15mm">
        <h3>Activity Detail</h3>
        <table width="100%" style="font-size:11px;">
            <tr >
                <td width="30%" class="main-td">Activity</td>
                <td width="70%" class="main-td">: {{$data->tour_name}}</td>
            </tr>
            <tr >
                <td width="30%" class="main-td">Schedule</td>
                <td width="70%" class="main-td">: 
                    @if($data->start_date == $data->end_date)
                        @if($data->tours->schedule_type == 3)
                            {{date('d M Y', strtotime($data->start_date))}} (1 day)
                        @else
                            {{date('d M Y', strtotime($data->start_date))}}, {{date('h:i', strtotime($data->start_hours))}} - {{date('h:i', strtotime($data->end_hours))}} ({{Helpers::diff_in_hours($data->start_hours, $data->end_hours)}} hour(s))
                        @endif
                    @else
                    {{date('d M Y', strtotime($data->start_date))}} - {{date('d M Y', strtotime($data->end_date))}} ({{Helpers::diff_in_days($data->start_date, $data->end_date)}} day(s))
                    @endif
                </td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Total Person</td>
                <td width="70%" class="main-td">: {{$data->number_of_person}} person(s)</td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Participant List</td>
                @if($data->transaction_id!=0)
                    @foreach($data->transactions->contact_list as $key => $contact)
                        @if($key==0)
                <td width="70%" class="main-td">: {{$contact->firstname.' '.$contact->lastname}}</td>
                        @else
            </tr>
            <tr>
                <td width="30%" class="main-td">&nbsp;</td>
                <td width="70%" class="main-td">&nbsp; {{$contact->firstname.' '.$contact->lastname}}</td>
            </tr>
            @endif
            @endforeach
            @endif
            <tr>
                <td width="30%" class="main-td">Meeeting Point</td>
                <td width="70%" class="main-td">: {{$data->tours->meeting_point_address}}
                </td>
            </tr>
            <tr>
                <td width="30%" class="main-td">&nbsp;</td>
                <td width="70%" class="main-td">&nbsp; {{$data->tours->meeting_point_note}}</td>
            </tr>
        </table>
        <h3>Provider Details</h3>
        <table width="100%" style="font-size:11px;">
            <tr >
                <td width="30%" class="main-td">PIC Name</td>
                <td width="70%" class="main-td">: {{$data->tours->pic_name}}</td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Phone Number</td>
                <td width="70%" class="main-td">: {{$data->tours->pic_phone}}</td>
            </tr>
            <tr>
                <td width="30%" class="main-td" colspan="2"  style="color:#E17306;"><i>this is the person in charge who will be meeting you at the location and reach you within 24 hours prior the schedule.</i></td>
            </tr>
            <tr>
                <td colspan="2" class="main-td">
                    <hr width="100%">
                </td>
            </tr>
        </table>
    </div>
</body>