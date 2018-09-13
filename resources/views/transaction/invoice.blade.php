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
    <div style="margin:5mm">
        <table width="100%">
            <tr>
                <td width="60%">
                    <h3 style="font-weight:bold">Customer Details</h3>
                </td>
                <td width="40%" style="text-align: right;">
                    <h3 style="font-weight:bold">Booking Receipt</h3>
                </td>
            </tr>
            <tr>
                <td width="60%">
                    <span style="width:50px">Name</span>
                    <span>: {{$data->customer->firstname.' '.$data->customer->lastname}}</span><br>
                    <span style="width:50px">Email Address</span>
                    <span>: {{$data->customer->email}}</span><br>
                    <span style="width:50px">Phone Number</span>
                    <span>: {{$data->customer->phone}}</span><br>
                </td>
                <td width="40%" style="text-align: right;">
                    <span>Transaction No. {{$data->transaction_number}}</span><br>
                    <span>Date: {{date('d M Y',strtotime($data->paid_at))}}</span>
                </td>
            </tr>
        </table>
    </div>
    <div id="main" style="margin-left:5mm">
        <section>
            <h3 style="font-weight:bold">Booking Summary</h3>
            <table width="100%" style="margin-right:20px;" height="100px">
                <tr style="background-color:#DDD;">
                    <td class="td-m-l-10" width="15%">Item</td>
                    <td class="td-m-l-10" width="40%">Item Description</td>
                    <td class="td-m-l-10" width="15%">Qty</td>
                    <td class="td-m-l-10" width="15%">Unit Price (Rp.)</td>
                    <td class="td-m-l-10" width="15%">Total Price (Rp.)</td>
                </tr>
                <tbody >
                @if(count($data->booking_tours))
                    @foreach($data->booking_tours as $key => $tour)
                    <tr class="tr-detail">
                        <td>Activity</td>
                        <td>
                            {{$tour->tour_name}}<br>
                            {{date('D, d M Y',strtotime($tour->start_date))}}<br>
                            {{'('.$tour->tours->destinations[0]->city->name.')'}}
                        </td>
                        <td>{{$tour->number_of_person.' person(s)'}}</td>
                        <td>{{'Rp '.number_format($tour->price_per_person)}}</td>
                        <td>{{'Rp '.number_format($tour->total_price)}}</td>
                    </tr>
                    @endforeach
                @endif
                @if(count($data->booking_hotels))
                    @foreach($data->booking_hotels as $hotel)
                    <tr class="tr-detail">
                        <td>Accomodation</td>
                        <td>
                            {{$hotel->room_name}}<br>
                            {{$hotel->hotel_name}}<br>
                            {{date('D, d M Y',strtotime($hotel->start_date)).' - '.date('D, d M Y',strtotime($hotel->end_date))}}<br>
                        </td>
                        <td>{{((strtotime($hotel->end_date)-strtotime($hotel->start_date))/86400).' nights(s)'}}</td>
                        <td>{{'Rp '.number_format($hotel->price_per_night)}}</td>
                        <td>{{'Rp '.number_format($hotel->total_price)}}</td>
                    </tr>
                    @endforeach
                @endif
                @if(count($data->booking_activities))
                    @foreach($data->booking_activities as $activities)
                    <tr class="tr-detail">
                        <td>Places</td>
                        <td>
                            {{$activities->tour_name}}<br>
                            {{date('D, d M Y',strtotime($activities->start_date))}}<br>
                            {{'('.$activities->activities->cities->name.')'}}
                        </td>
                        <td>{{$activities->number_of_person.' person(s)'}}</td>
                        <td>{{'Rp '.number_format($activities->price_per_person)}}</td>
                        <td>{{'Rp '.number_format($hotel->total_price)}}</td>
                    </tr>
                    @endforeach
                @endif
                @if(count($data->booking_rent_cars))
                    @foreach($data->booking_rent_cars as $rent_car)
                    <tr class="tr-detail">
                        <td>Rent Car</td>
                        <td>
                            {{$rent_car->vehicle_brand}} {{$rent_car->vehicle_name}}<br>
                            {{$rent_car->agency_name}}<br>
                            @if($rent_car->price_per_day!=1)
                                {{date('D, d M Y',strtotime($rent_car->start_date))}}
                            @else
                                {{date('D, d M Y',strtotime($rent_car->start_date)).' - '.date('D, d M Y',strtotime($rent_car->end_date))}}<br>
                            @endif
                        </td>
                        <td>{{$rent_car->number_of_day.' days(s)'}}</td>
                        <td>{{'Rp '.number_format($rent_car->price_per_day)}}</td>
                        <td>{{'Rp '.number_format($rent_car->total_price)}}</td>
                    </tr>
                    @endforeach
                @endif
                    <tr>
                        <td colspan="2"></td>
                        <td class="td-total"  colspan="2">Total Price</td>
                        <td class="td-total">{{'Rp '.number_format($data->total_price)}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="td-payment"  colspan="2">Payment Amount</td>
                        <td class="td-payment">{{'Rp '.number_format((int)$data->total_paid ? $data->total_paid : $data->total_price)}}</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</body>