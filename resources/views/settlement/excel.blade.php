<table>
    <thead>
        <tr>
            <td colspan="8">Period: {{ date('d/m/Y',strtotime($data->period_start)) }} - {{ date('d/m/Y',strtotime($data->period_end)) }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th>Booking Number</th>
            <th>Product Type</th>
            <th>Product Name</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total Paid</th>
            <th>Total Commssion</th>
            <th>Account Bank</th>
        </tr>
    </thead>
    <tbody>
    @if(count($data->settlement) > 0)
        @foreach($data->settlement as $set)
        <tr>
            <td>{{$set->booking_number}}</td>
            <td>{{$set->product_type}}</td>
            <td>{{$set->product_name}}</td>
            <td>{{$set->qty}}</td>
            <td>{{Helpers::idr($set->unit_price)}}</td>
            <td>{{Helpers::idr($set->total_price - $set->commission)}}</td>
            <td>{{Helpers::idr($set->total_commission)}}</td>
            
            <td>
                @if($set->bank_account_number != null)
                    {{$set->bank_account_number}}
                @else
                    Bank account not inserted
                @endif     
            </td>
        </tr>
        @endforeach
    @endif
    </tbody>
</table>