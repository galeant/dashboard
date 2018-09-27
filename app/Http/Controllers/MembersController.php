<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Members;
use App\Models\Transaction;
use Validator;
use Datatables;
use DB;
use Carbon\Carbon;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // if($request->ajax())
        // {
        //     $model = Members::query();
        //     return Datatables::eloquent($model)
        //     ->addColumn('action', function(Members $data) {
        //         return '<a href="/members/'.$data->id.'" class="btn-xs btn-info  waves-effect waves-circle waves-float">
        //                 <i class="glyphicon glyphicon-eye-open"></i>
        //             </a>';
        //     })
        //     ->addColumn('fullname', function(Members $data) {
        //         return $data->firstname.' '.$data->lastname;
        //     })
        //     ->addColumn('join_date', function(Members $data) {
        //         return date('j F Y',strtotime($data->created_at));
        //     })
        //     ->addColumn('status', function(Members $data) {
        //         if($data->password != null){
        //             if($data->status == 1){
        //                 return '<span class="label bg-green">Active</span>';
        //             }else{
        //                 return '<span class="label bg-red">Not Verified</span>';
        //             }
        //         }else{
        //             return '<span class="label bg-red">Not Verified</span>';
        //         }
        //     })
        //     ->editColumn('id', 'ID: {{$id}}')
        //     ->rawColumns(['action', 'status'])
        //     ->make(true);
        // }
        // dd($request->keyword);
        if($request->status != null){
            $stat = $request->status;
        }else{
            $stat = null;
        }
        if($request->keyword != null){
            $keyword = $request->keyword;
        }else{
            $keyword = null;
        }
        $member = Members::where(function($query) use($keyword){
                $query->where('id','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%')
                    ->orWhere('firstname','like','%'.$keyword.'%')
                    ->orWhere('lastname','like','%'.$keyword.'%')
                    ->orWhere('phone','like','%'.$keyword.'%');
                });
        if($stat != null && $stat != 99){
            if($stat == 1){
                $response = $member->whereRaw('(password != null or password !="") and status = 1')->get();
            }else{
                $response = $member->whereRaw('(password = null or password ="") or status = 0')->get();
            }
        }else{
            $response = $member->get();
        }
        // dd($request->all());
        // dd($response);
        return view('members.index',['data'=>$response,'request' => $request->all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('members.form_create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation //
        $validation = Validator::make($request->all(), [
            "gendre" => "required",
            "firstname" => "required",
            "lastname" => "required",
            "username" => "required",
            "email" => "required",
            "phone" => "required",
            "status" => "required"
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = new Members();
            $data->salutation = $request->input('gendre');
            $data->firstname = $request->input('firstname');
            $data->lastname = $request->input('lastname');
            $data->username = $request->input('username');
            $data->email = $request->input('email');
            $data->phone = $request->input('phone');
            $data->status = $request->input('status');
            $data->password = md5($data->email);
            if($data->save()){
                DB::commit();
                return redirect("members/".$data->id."/edit")->with('message', 'Successfully saved Members');
            }else{
                return redirect("members/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("members/create")->with('message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Members::where('id',$id)->with(['transactions' => function($query){
            $query->limit(5)->orderBy('created_at','desc');
        }])->with('transactions.contact')->first();
        return view('members.detail',['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $data = Members::find($id);
      return view('members.form_edit')->with([
          'data'=> $data
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation //
        $validation = Validator::make($request->all(), [
            "gendre" => "required",
            "firstname" => "required",
            "lastname" => "required",
            "username" => "required|unique:users",
            "email" => "required|unique:users",
            "phone" => "required|unique:users",
            "status" => "required"
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
          $data = Members::find($id);
          $data->salutation = $request->input('gendre');
          $data->firstname = $request->input('firstname');
          $data->lastname = $request->input('lastname');
          $data->username = $request->input('username');
          $data->email = $request->input('email');
          $data->phone = $request->input('phone');
          $data->status = $request->input('status');

             if($data->save()){
                DB::commit();
                return redirect("members/".$data->id."/edit")->with('message', 'Successfully saved Members');
            }else{
                return redirect("members/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("members/".$data->id."/edit")->with('message', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      DB::beginTransaction();
      try{
          $data = Members::find($id);
          if($data->delete()){
              DB::commit();
              return $this->sendResponse($data, "Delete Members ".$data->name." successfully", 200);
          }else{
              return $this->sendResponse($data, "Error Database;", 200);
          }
      }catch (\Exception $exception){
          DB::rollBack();
          \Log::info($exception->getMessage());
          return $this->sendResponse($data, $exception->getMessage() , 200);
      }
    }
    public function filter(Request $request){
        $keyword = $request->keyword;
        $data = Members::where('status',$request->status)
                ->where(function($query) use($keyword){
                    $query->where('id','like','%'.$keyword.'%')
                        ->orWhere('email','like','%'.$keyword.'%')
                        ->orWhere('firstname','like','%'.$keyword.'%')
                        ->orWhere('lastname','like','%'.$keyword.'%')
                        ->orWhere('phone','like','%'.$keyword.'%');
                })
                ->get();
        // dd($data);
        return view('members.index',['data'=>$data]);
    }

    public function report(Request $request){
        $option = $request->option;
        $member = Members::all();
        if($option=='day'){
            $start_date = Carbon::now()->format('Y-m-d');
            $until_date = Carbon::now()->format('Y-m-d');
        }
        else if($option=='this_week'){
            $start_date = Carbon::now()->startOfWeek()->format('Y-m-d');
            $start_week = Carbon::now()->startOfWeek();
            $until_date = $start_week->addDay(6)->format('Y-m-d');
        }
        else if($option=='this_month'){
            $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
            $until_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
        else if($option == 'this_year'){
            $start_date = Carbon::now()->startOfYear();
            $until_date = Carbon::now()->endOfYear();
        }
        else if(!empty($request->input('start_date')) && !empty($request->input('until_date'))){
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $until_date = Carbon::parse($request->until_date)->format('Y-m-d');
        }
        else{
            $start_date = Carbon::now()->format('Y-m-d');
            $until_date = Carbon::now()->format('Y-m-d');
        }
        $member = Members::where('created_at','>=', $start_date)->where('created_at','<=', $until_date)
            ->select('id', 'created_at')->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d'); 
            })->toArray();
        return view('members.report', [
            'data'=> $member,
            'start_date' => $start_date,
            'until_date' => $until_date
        ]);
    }
}
