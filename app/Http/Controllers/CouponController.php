<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupons;
use App\Models\Transaction;
use App\Models\ProductType;
use Validator;
use Datatables;
use DB;
use Carbon\Carbon;

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
            ->addColumn('used',function(coupons $data){
                return Transaction::where('coupon_code','=',$data->code)->count();
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
      $data['product_type'] = ProductType::all();
      return view('coupon.form_create',$data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
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
          "max_discount" => "required",
          "product_type" => "required",
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        if($request->has('is_gacha')){
            if($request->has('is_gacha')){
                $validation2 = Validator::make($request->all(), [
                    "gacha_start_date" => "required|date_format:Y-m-d H:i:s",
                    "gacha_end_date" => "required|date_format:Y-m-d H:i:s|after:gacha_start_date"
                  ]);
                if($validation2->fails() ){
                    return redirect()->back()->withInput()
                    ->with('errors', $validation2->errors() );
                }
            }
        }
        DB::beginTransaction();
        try{
         for($i = 0 ;$i < $request->input('number_of_generate') ; $i++)
          {
              $data = new Coupons();
              $data->quantity = $request->input('quantity');
              $data->quantity_per_use = $request->input('quantity_per_use');
              $data->type = $request->input('type');
              $data->name = $request->input('name');
              $data->code = $request->input('code').'R'.rand(1,99).'N'.$i;
              $data->start_date = $request->input('start_date');
              $data->end_date = $request->input('end_date');
              $data->discount_value = $request->input('discount_value');
              $data->discount_value = $request->input('discount_value');
              $data->max_discount = $request->input('max_discount');
              $data->minimum_order = $request->input('minimum_order');
              $data->description = $request->input('description');
              $data->product_type = $request->input('product_type');
              $data->is_itinerary_only = ($request->input('is_itinerary_only') == 'on') ? 1 : 0;
              if($request->has('is_gacha')){
                $data->is_gacha = 1;
                $data->gacha_start_date = $request->input('gacha_start_date',Carbon::now()->format('Y-m-d H:i:s'));
                $data->gacha_end_date = $request->input('gacha_end_date',Carbon::now()->format('Y-m-d H:i:s'));
              }
              $data->save();
            }

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
      $data['data'] = Coupons::find($id);
      $data['product_type'] = ProductType::all();
    //   dd($data['data']);
      return view('coupon.form_edit')->with($data);
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
        // dd($request->all());
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
        if($request->has('is_gacha')){
            $validation2 = Validator::make($request->all(), [
                "gacha_start_date" => "required|date_format:Y-m-d H:i:s",
                "gacha_end_date" => "required|date_format:Y-m-d H:i:s|after:gacha_start_date"
              ]);
            if($validation2->fails() ){
                return redirect()->back()->withInput()
                ->with('errors', $validation2->errors());
            }
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
              $data->minimum_order = $request->input('minimum_order');
              $data->description = $request->input('description');
              $data->product_type = $request->input('product_type');
              $data->is_itinerary_only = ($request->input('is_itinerary_only') == 'on') ? 1 : 0;
              if($request->has('is_gacha')){
                $data->is_gacha = 1;
                $data->gacha_start_date = $request->input('gacha_start_date',Carbon::now()->format('Y-m-d H:i:s'));
                $data->gacha_end_date = $request->input('gacha_end_date',Carbon::now()->format('Y-m-d H:i:s'));
              }else{
                $data->is_gacha = 0;
                $data->gacha_start_date = null;
                $data->gacha_end_date = null;
              }
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
