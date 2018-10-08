<?php

namespace App\Exports;

use App\Models\Province;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class CityReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public function __construct(array $ar)
    {
        $this->ar = $ar;
    }
    public function view(): View
    {
        $ar = $this->ar;
        $head = [];
        // dd($ar);
        foreach($ar as $key=>$b){
            foreach($b as $k=>$c){
                if(!in_array($k,$head)){
                    $head[] = $k;
                }
            }
        }
        // dd($head);
        return view('report.export.excel_city', [
            'head' => $head,
            'data' => $ar
        ]);
    }
}

