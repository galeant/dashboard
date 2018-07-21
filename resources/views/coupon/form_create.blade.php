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
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                          @include('errors.error_notification')
                          <br>
                            {{ Form::open(['route'=>'coupon.store', 'method'=>'POST', 'class'=>'','id'=>'form_advanced_validation']) }}
                              @csrf
                              <div class="row clearfix">
                                    <div class="col-sm-12">
                                      <div class="form-group row">
                                        <div class="col-md-4">
                                          <h2 class="card-inside-title">Total Qty</h2>
                                            <input type="text" name="quantity" class="form-control" placeholder="Maximum Discount" />
                                         </div>
                                         <div class="col-md-4">
                                           <h2 class="card-inside-title">Qty Per Use</h2>
                                             <input type="text" name="quantity_per_use" class="form-control" placeholder="Maximum Discount" />
                                          </div>
                                          <div class="col-md-4">
                                            <h2 class="card-inside-title">Type</h2>
                                            <select name="gendre" class="form-control show-tick">
                                                <option value="">-- Please select --</option>
                                                <option value="amount">Amount</option>
                                                <option value="percentage">Percentage</option>
                                            </select>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Name</h2>
                                          <div class="form-line">
                                              <input name="name" type="text" class="form-control" placeholder="Name" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Code</h2>
                                          <div class="form-line">
                                              <input name="code" type="text" class="form-control" placeholder="Code" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Start Date</h2>
                                          <div class="form-line">
                                              <input name="start_date" type="text" class="datepicker form-control" placeholder="Start Date" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">End Date</h2>
                                          <div class="form-line">
                                              <input name="end_date" type="text" class="form-control" placeholder="End Date" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Discount Value</h2>
                                          <div class="form-line">
                                              <input type="text" name="discount_value" class="form-control" placeholder="Discount Value" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Minimum Order</h2>
                                          <div class="form-line">
                                              <input type="text" name="discount_value" class="form-control" placeholder="Minimum Order" />
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <h2 class="card-inside-title">Maximum Discount</h2>
                                          <div class="form-line">
                                              <input type="text" name="max_discount" class="form-control" placeholder="Maximum Discount" />
                                          </div>
                                      </div>
                                      <div class="demo-radio-button">
                                        <h2 class="card-inside-title">Status</h2>
                                          <input name="status" type="radio" id="radio_7" class="radio-col-purple" value="1" checked />
                                          <label for="radio_7">Active</label>
                                          <input name="status" type="radio" id="radio_8" class="radio-col-yellow" value="2" />
                                          <label for="radio_8">Non Active</label>
                                          <input name="status" type="radio" id="radio_9" class="radio-col-red" value="3" />
                                          <label for="radio_9">Banned</label>
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
    <script src="{{asset('plugins/momentjs/moment.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
    <!-- <script src="{{asset('js/pages/tables/jquery-datatable.js')}}"></script> -->
@stop
