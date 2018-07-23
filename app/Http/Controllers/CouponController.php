<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupons;
use Validator;
use Datatables;
use DB;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->ajax())
        {
            $model = Coupons::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(coupons $data) {
                return '<a href="coupon/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="coupon/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/coupon/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);
        }
        return view('coupon.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('coupon.form_create');
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
          "quantity" => "required",
          "quantity_per_use" => "required",
          "type" => "required",
          "name" => "required",
          "code" => "required|unique:coupons",
          "start_date" => "required",
          "end_date" => "required",
          "discount_value" => "required",
          "discount_value" => "required",
          "max_discount" => "required"
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = new Coupons();
            $data->quantity = $request->input('quantity');
            $data->quantity_per_use = $request->input('quantity_per_use');
            $data->type = $request->input('type');
            $data->name = $request->input('name');
            $data->code = $request->input('code');
            $data->start_date = $request->input('start_date');
            $data->end_date = $request->input('end_date');
            $data->discount_value = $request->input('discount_value');
            $data->discount_value = $request->input('discount_value');
            $data->max_discount = $request->input('max_discount');
            $data->description = $request->input('description');
            if($data->save()){
                DB::commit();
                return redirect("coupon/".$data->id."/edit")->with('message', 'Successfully saved Coupon');
            }else{
                return redirect("coupon/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("coupon/create")->with('message', $exception->getMessage());
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $data = Coupons::find($id);
      return view('coupon.form_edit')->with([
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
          "quantity" => "required",
          "quantity_per_use" => "required",
          "type" => "required",
          "name" => "required",
          "code" => "required",
          "start_date" => "required",
          "end_date" => "required",
          "discount_value" => "required",
          "discount_value" => "required",
          "max_discount" => "required"
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
              $data = Coupons::find($id);
              $data->quantity = $request->input('quantity');
              $data->quantity_per_use = $request->input('quantity_per_use');
              $data->type = $request->input('type');
              $data->name = $request->input('name');
              $data->code = $request->input('code');
              $data->start_date = $request->input('start_date');
              $data->end_date = $request->input('end_date');
              $data->discount_value = $request->input('discount_value');
              $data->discount_value = $request->input('discount_value');
              $data->max_discount = $request->input('max_discount');
              $data->description = $request->input('description');
             if($data->save()){
                DB::commit();
                return redirect("coupon/".$data->id."/edit")->with('message', 'Successfully edit Coupon');
            }else{
                return redirect("coupon/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("coupon/".$data->id."/edit")->with('message', $exception->getMessage());
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
          $data = Coupons::find($id);
          if($data->delete()){
              DB::commit();
              return $this->sendResponse($data, "Delete Coupons ".$data->name." successfully", 200);
          }else{
              return $this->sendResponse($data, "Error Database;", 200);
          }
      }catch (\Exception $exception){
          DB::rollBack();
          \Log::info($exception->getMessage());
          return $this->sendResponse($data, $exception->getMessage() , 200);
      }
    }
}
