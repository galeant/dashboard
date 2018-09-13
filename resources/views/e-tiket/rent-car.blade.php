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
        <h3>Rental Service Details</h3>
        <table width="100%" style="font-size:11px;">
            <tr >
                <td width="30%" class="main-td">Car Model</td>
                <td width="70%" class="main-td">: {{$data->vehicle_brand}} {{$data->vehicle_name}}</td>
            </tr>
            <tr >
                <td width="30%" class="main-td">Service Description</td>
                <td width="70%" class="main-td">: 

                </td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Rent Date</td>
                <td width="70%" class="main-td">: {{date('d M Y', strtotime($data->start_date))}} - {{date('d M Y', strtotime($data->end_date))}}</td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Rent Duration</td>
                <td width="70%" class="main-td">: {{Helpers::diff_in_days($data->start_date, $data->end_date)}} day(s) ({{Helpers::diff_in_hours($data->time_from, $data->time_to)}} hours max per day) 
                </td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Pick Up Time</td>
                <td width="70%" class="main-td">: {{date('d M Y', strtotime($data->time_from))}} 
                </td>
            </tr>
            <tr>
                <td width="30%" class="main-td" colspan="2"  style="color:#E17306;"><i>Please confirm again your pick up time and your pickup address to the agency.</i></td>
            </tr>
        </table>
        <h3>Agency Information</h3>
        <table width="100%" style="font-size:11px;">
            <tr >
                <td width="30%" class="main-td">Agency Name</td>
                <td width="70%" class="main-td">: {{$data->agency_name}}</td>
            </tr>
            <tr>
            @php
                $desc = $data->reservation_description;
                $array = explode(",", $desc);
                $contact_number = current($array);
                $location = str_replace($contact_number.",","",$desc);
            @endphp
                <td width="30%" class="main-td">Phone Number</td>
                <td width="70%" class="main-td">: {{$contact_number}}</td>
            </tr>
            <tr >
                <td width="30%" class="main-td">Agency Info</td>
                <td width="70%" class="main-td">: {{$location}}</td>
            </tr>
            <tr>
                <td colspan="2" class="main-td">
                    <hr width="100%">
                </td>
            </tr>
        </table>
    </div>
</body>