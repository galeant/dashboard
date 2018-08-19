<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

use DB;
use Datatables;

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
        foreach($data->transaction_log_status as $log){
            if($log->transaction_status_id == $request->input('status')){
                return redirect()->back()->with('error','Can`t change status because status is duplicated' );
            }
        }
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
}
