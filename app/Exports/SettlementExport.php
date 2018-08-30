<?php

namespace App\Exports;

use App\Models\SettlementGroup;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class SettlementExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public function __construct(int $id)
    {
        $this->id = $id;
    }
    public function view(): View
    {
        $id = $this->id;
        return view('settlement.excel', [
            'data' => SettlementGroup::query()->where('id',$id)->with(['settlement' => function($query) use($id){
                $query->where('settlement_group_id',$id);
            }])->first()
        ]);
    }
}
