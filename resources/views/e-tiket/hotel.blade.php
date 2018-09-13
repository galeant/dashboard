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
        <h3>Accomodation Detail</h3>
        <table width="100%" style="font-size:11px;">
            <tr >
                <td width="30%" class="main-td">Homestay</td>
                <td width="70%" class="main-td">: {{$data->hotel_name}}</td>
            </tr>
            <tr >
                <td width="30%" class="main-td">Room Type</td>
                <td width="70%" class="main-td">: {{$data->room_name}}</td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Total Room / Number of Person</td>
                <td width="70%" class="main-td">: {{$data->number_of_rooms}} room(s) / {{$data->adult}} person(s)</td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Check-in Date</td>
                <td width="70%" class="main-td">: {{date('d F Y'), strtotime($data->start_date)}}</td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Night</td>
                <td width="70%" class="main-td">: {{$data->night}} night(s)</td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Check-out Date</td>
                <td width="70%" class="main-td">: {{date('d F Y'), strtotime($data->end_date)}}</td>
            </tr>
            <tr>
                <td width="30%" class="main-td">Address</td>
                <td width="70%" class="main-td">:
                </td>
            </tr>
            <tr>
                <td colspan="2" class="main-td">
                    <hr width="100%">
                </td>
            </tr>
        </table>
    </div>
</body>