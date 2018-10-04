<table>
    <thead>
        <tr>
            <td colspan="8">Tanggal: {{ date('d-m-Y') }}</td>
        </tr>
        <tr></tr>
        <tr>
            @foreach($head as $h)
                <th>{{$h}}</th>    
            @endforeach
            
        </tr>
    </thead>
    <tbody>
    @if(count($data) > 0)
        @foreach($data as $d)
            <tr>
                @foreach($head as $h)
                    <td>{{ $d[$h] }}</td>
                @endforeach
            </tr>
        @endforeach
    @endif
    </tbody>
</table>