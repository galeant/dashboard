<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\TransactionLogStatus;
use Illuminate\Http\Request;
use DB;
use Datatables;
use App\Http\Libraries\PDF\PDF;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data = Transaction::whereIn('status_id',[2,3,4,5,6]);
        if($request->ajax())
        {
            $transaction = Transaction::with(
                'transaction_status')
            ->whereNotNull('transaction_number')
            ->orderBy('paid_at','DESC');
            return Datatables::of($transaction)
            ->addColumn('status',function(Transaction $transaction){
                return '<span class="badge" style="background-color:'.$transaction->transaction_status->color.'">'.$transaction->transaction_status->name.'</span>';
            })
            ->addColumn('user',function(Transaction $transaction){
                if(!empty($transaction->customer)){
                    return $transaction->customer->firstname.' '.$transaction->customer->lastname;
                }
            })
            ->editColumn('transaction_number', function(Transaction $transaction) {
                    return '<a href="/transaction/'.$transaction->transaction_number.'" class="btn btn-primary">'.$transaction->transaction_number.'</a>';
                })
            ->editColumn('total_price', function(Transaction $transaction) {
                    return number_format($transaction->total_price);
                })
            ->rawColumns(['status','user','total_price','transaction_number'])
            ->make(true);        
        }
        return view('transaction.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $code)
    {
        //
        $data = Transaction::where('transaction_number', $code)->first();
        return view('transaction.detail',['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $data = Transaction::find($id);
        $listStat = array_pluck($data->transaction_log_status, 'transaction_status_id');
        if(!in_array($request->status, $listStat)){
            if($request->status == 2){
                if(in_array(1, $listStat)){
                    DB::beginTransaction();
                    try{
                        Transaction::where('id',$id)->update([
                            'status_id' => $request->status
                        ]);
                        TransactionLogStatus::create([
                            'transaction_status_id' => $request->status,
                            'transaction_id' => $id
                        ]);
                        DB::commit();
                        return redirect('transaction/'.$data->transaction_number)->with('message','Change Status Successfully');
                    }catch (\Exception $exception){
                        dd($exception);
                        DB::rollBack();
                        \Log::info($exception->getMessage());
                        return redirect('transaction/'.$data->transaction_number)->with('error',$exception->getMessage());
                    }
                }else{
                    return redirect()->back()->with('error','Can`t change status because status not right ordered' );
                }
            }else if($request->status == 5){
                if(in_array(2, $listStat)){
                    DB::beginTransaction();
                    try{
                        Transaction::where('id',$id)->update([
                            'status_id' => $request->status
                        ]);
                        TransactionLogStatus::create([
                            'transaction_status_id' => $request->status,
                            'transaction_id' => $data->id
                        ]);
                        DB::commit();
                        return redirect('transaction/'.$data->transaction_number)->with('message','Change Status Successfully');
                    }catch (\Exception $exception){
                        dd($exception);
                        DB::rollBack();
                        \Log::info($exception->getMessage());
                        return redirect('transaction/'.$data->transaction_number)->with('error',$exception->getMessage());
                    }
                }else{
                    return redirect()->back()->with('error','Can`t change status because status not right ordered' );
                }
            }else if($request->status == 6){
                if(in_array(5, $listStat)){
                    DB::beginTransaction();
                    try{
                        Transaction::where('id',$id)->update([
                            'status_id' => $request->status
                        ]);
                        TransactionLogStatus::create([
                            'transaction_status_id' => $request->status,
                            'transaction_id' => $id
                        ]);
                        DB::commit();
                        return redirect('transaction/'.$data->transaction_number)->with('message','Change Status Successfully');
                    }catch (\Exception $exception){
                        dd($exception);
                        DB::rollBack();
                        \Log::info($exception->getMessage());
                        return redirect('transaction/'.$data->transaction_number)->with('error',$exception->getMessage());
                    }
                }else{
                    return redirect()->back()->with('error','Can`t change status because status not right ordered' );
                }
            }
        }else{
            return redirect()->back()->with('error','Can`t change status because status is duplicated' );
        }
        // foreach($data->transaction_log_status as $log){
        //     if($log->transaction_status_id == $request->input('status')){
        //         return redirect()->back()->with('error','Can`t change status because status is duplicated' );
        //     }
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function print(Request $request, $tr_number,$type = 'PDF')
    {

        // return view('print.pdf.invoice');
        $data = Transaction::where('transaction_number',$tr_number)->first();
        $pdf = new PDF;
        $pdf->AddPage();
        $addpage = 1;
        $pdf->Code128($pdf->GetPageWidth()-60,55,$data->transaction_number,50,15);
        $pdf->Header($data->transaction_number,$data->paid_at,$data->customer);
        for($i = 1;$i < 60 ; $i++)
        {
            $pdf->Cell($pdf->GetPageWidth(),10,'Hello World!',0,0,'',false);
            $pdf->Ln(5);
            if(($i%37) == 0){
                $pdf->AddPage();
                $pdf->Code128($pdf->GetPageWidth()-60,55,$data->transaction_number,50,15);
                $pdf->Header($data->transaction_number,$data->paid_at,$data->customer);
            }
        }
        $pdf->Output();
        // if(!empty($data)){
            // if($type == 'PDF'){
            //     $view    =  \View::make('print.pdf.invoice')->render();
            //     $pdf     = \App::make('dompdf.wrapper');
            //     $pdf->loadHTML($view);
            //     return $pdf->stream();
            // }
        // }
    }
}
