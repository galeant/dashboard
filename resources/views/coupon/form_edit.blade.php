@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@stop
@section('main-content')
			<div class="block-header">
                <h2>
                    Add Coupon
                    <small>Admin Data / Coupon / Add</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Add New Coupon
                            </h2>
                            <ul class="header-dropdown m-r--5">
                            </ul>
                        </div>
                        <div class="body">
                          @include('errors.error_notification')
                          <br>
                            {{ Form::open(['route'=> ['coupon.update',$data->id], 'method'=>'PUT', 'class'=>'','id'=>'form_advanced_validation']) }}
                              @csrf
                              <div class="row clearfix">
                                    <div class="col-sm-12">
                                      <div class="form-group row">
                                        <div class="col-md-6">
                                          <h2 class="card-inside-title">Usage Limit per coupon</h2>
                                            <input value="{{$data->quantity}}" required type="number" name="quantity" class="form-control" placeholder="Maximum Discount" />
                                         </div>
                                         <div class="col-md-6">
                                           <h2 class="card-inside-title">Usage limit per User/Customer</h2>
                                             <input value="{{$data->quantity_per_use}}" required type="number" name="quantity_per_use" class="form-control" placeholder="Maximum Discount" />
                                          </div>
                                      </div>
                                      <div class="form-group row">
                                        <div class="col-md-6">
                                          <h2 class="card-inside-title">Discount Type</h2>
                                          <select name="type" class="form-control show-tick coupon_type" required>
                                              <option value="" selected>Please select</option>
                                              <option @if($data->type == 'amount'){ selected } @endif value="amount">Amount</option>
                                              <option @if($data->type == 'percentage'){ selected } @endif value="percentage">Percentage</option>
                                          </select>
                                        </div>
                                        <div class="col-md-6">
                                          <h2 class="card-inside-title">Product Type</h2>
                                          <select name="product_type" class="form-control" required>
                                                  <option @if($data->product_type == 0){ selected } @endif value="0" selected>All</option>
                                                @foreach($product_type as $type)
                                                  <option @if($data->product_type == $type['id']){ selected } @endif value="{{$type['id']}}">{{$type['name']}}</option>
                                                @endforeach
                                          </select>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Coupon Name / Program Name</h2>
                                          <div class="form-line">
                                              <input value="{{$data->name}}" required name="name" type="text" class="form-control" placeholder="Coupon name / Program name" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Coupon Code</h2>
                                          <div class="form-line">
                                              <input value="{{$data->code}}" required name="code" type="text" class="form-control" placeholder="Coupon Code" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Start Date</h2>
                                          <div class="form-line">
                                            <input value="{{date('Y-m-d',strtotime($data->start_date))}}" required name="start_date" type="text" class="datepicker form-control" placeholder="Please choose a date...">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">End Date</h2>
                                          <div class="form-line">
                                            <input value="{{date('Y-m-d',strtotime($data->end_date))}}" required name="end_date" type="text" class="datepicker form-control" placeholder="Please choose a date...">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Discount Value</h2>
                                          <div class="form-line">
                                              <input @if($data->type == 'percentage') max=100 @endif value="{{$data->discount_value}}"  required type="number" name="discount_value" class="discount_value form-control" placeholder="Input amount or percentage based on discount type" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Minimum Order</h2>
                                          <div class="form-line">
                                              <input value="{{$data->minimum_order}}" required type="number" name="minimum_order" class="form-control" placeholder="Minimum order amount that needs to be in the cart before coupon applies" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Maximum Discount</h2>
                                          <div class="form-line">
                                              <input value="{{$data->max_discount}}" required type="number" name="max_discount" class="form-control" placeholder="Maximum order amount allowed when using this coupon" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Description</h2>
                                          <div class="form-line">
                                              <textarea required type="text" name="description" class="form-control" placeholder="Enter Description" />{{$data->description}}</textarea>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <br>
                              <br>
                              <br>
                              <button type="submit" class="btn btn-success center">Save</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

@stop
@section('head-js')
@parent
    <script src="{{asset('plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}"></script>
    <!--  for date and time picker -->
    <script src="{{asset('plugins/momentjs/moment.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
    <script src="{{asset('plugins/js/custom.js')}}"></script>
    <!-- <script src="{{asset('js/pages/tables/jquery-datatable.js')}}"></script> -->
    <script>
      $(document).ready(function(){
        $( ".coupon_type" ).change(function(){
          var copupon_type = $('.coupon_type').val();
          if(copupon_type == 'percentage')
            {
              $('.discount_value').attr({
                'max' : 100
              });
            }
          else if(copupon_type == 'amount')
            {
              $('.discount_value').removeAttr('max');
            }
        });
      })
    </script>
@stop
