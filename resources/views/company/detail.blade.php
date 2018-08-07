@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <!-- Bootstrap File-Input -->
	<link href="{{ asset('plugins/bootstrap-file-input/css/fileinput.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('plugins/bootstrap-file-input/themes/explorer-fa/theme.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <!-- Tel Input -->
    <link href="{{ asset('plugins/telformat/css/intlTelInput.css') }}" rel="stylesheet">
    <!-- Bootstrap Select2 -->
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
     <!-- Light Gallery Plugin Css -->
    <link href="{{asset('plugins/light-gallery/css/lightgallery.css')}}" rel="stylesheet">
    <style>
        .row{
            margin:0px;
        }
        .input-group input[type="text"], .input-group .form-control {
            border: solid #555 1px;
            padding-left: 15px;
        }
        .intl-tel-input{
            width: 100%;
        }
        .kv-file-upload,.kv-file-remove{
            display:none;
        }
    </style>
@stop
@section('main-content')
    <div class="block-header">
        <h2>
            Detail Partners
            <small>Data / Partners</small>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" id="company_detail" >
                <div class="header">
                    <div class="row">
                        <div class="col-md-3" style="margin-top:10px">
                            <h2>Detail Partners</h2>
                        </div>
                        <ul class="header-dropdown m-r--5">
                            <li >
                                <button style="display: none" type="button" class="btn btn-warning waves-effect" id="change-status" data-toggle="modal" data-target="#defaultModal">Change Status</button>
                            </li>
                            <li>
                                @if($company->status == 0)
                                <span class="badge bg-purple">Not Verified</span>
                                @elseif($company->status ==1)
                                <span class="badge bg-blue">Awaiting Submission</span>
                                @elseif($company->status ==2)
                                <span class="badge bg-cyan">Awaiting Moderation</span>
                                @elseif($company->status ==3)
                                <span class="badge bg-pink">Insufficient Data</span>
                                @elseif($company->status ==4)
                                <span class="badge bg-red">Rejected</span>
                                @elseif($company->status ==5)
                                <span class="badge bg-green">Active</span>
                                @else
                                <span class="badge bg-red">Disabled</span>
                                @endif
                                <button type="button" class="btn bg-deep-purple waves-effect" id="edit">
                                    <i class="material-icons">mode_edit</i>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="body" style="padding-left:25px">
                @include('errors.error_notification')
                {{ Form::open(['route'=>['partner.update', $company->id], 'method'=>'PUT','enctype' => 'multipart/form-data']) }}
                    <div class="row">   
                        <h4>Personal Information</h4>
                        <div class="col-md-5" style="margin-top:0px;">
                            <div class="valid-info">
                                <h5>Full Name* :</h5>
                                <input type="text" class="form-control" name="fullname" value="@if(!empty($company->pic)) {{$company->pic->fullname}} @endif">
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-top:0px;">
                            <div class="valid-info">
                                <h5>Email:</h5>
                                <input type="email" class="form-control" name="email" value="@if(!empty($company->pic)) {{$company->pic->email}} @endif">
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-top: 10px;">
                            <div class="valid-info">
                                <h5>Phone Number* :</h5>
                                <input type="hidden" class="form-control" name="format" id="telformat">
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-top: 10px;">
                            <div class="valid-info">
                                <h5>What is your role?* :</h5>
                                {{ Form::select('role', $roles, (!empty($company->pic) ? $company->pic->role_id : null) ,['class' => 'form-control', 'id'=>'role_id'])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h4>Company / Business Information</h4>
                        <div class="col-md-10 mg-top-10px" style="margin-top:0px;">
                            <div class="valid-info">
                                <h5>Company / Business Name*:</h5>
                                <input type="text" class="form-control" name="company_name" value="{{$company->company_name}}">
                            </div>
                        </div>
                        <div class="row" style="margin:0px">
                            <div class="col-md-5" style="margin-top:10px;">
                                <div class="valid-info">
                                    <h5>Company Phone Number*:</h5>
                                    <div class="row" style="margin: 0px">
                                        <div class="col-md-9" style="margin: 0px;padding: 0px;width:100%">
                                            <input type="hidden" class="form-control" name="format_company" id="telformat">	
                                            <input type="text" class="form-control" name="company_phone" required>	
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5" style="margin-top:10px;">
                                <div class="valid-info">
                                    <h5>Company Email Address:</h5>
                                    <input type="email" class="form-control" name="company_email" value="{{$company->company_email}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-top:10px">
                            <div class="valid-info">
                                <h5>Province*:</h5>
                                <select class="form-control" name="province_id" required>
                                    <option value="" selected>-- Select Province --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{$province->id}}">{{$province->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-top: 10px;">
                            <div class="valid-info">
                                <h5>City / Regency*:</h5>
                                <select class="form-control" name="city_id" required>
                                    <option value="">-- Select City --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-10" style="margin-top:10px">
                            <div class="valid-info">
                                <h5>Address*:</h5>
                                <textarea rows="5" name="company_address" class="form-control no-resize">{{$company->company_address}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-10" style="margin-top:10px;">
                            <div class="valid-info">
                                <h5>Postal Code*:</h5>
                                <input type="text" class="form-control" name="company_postal" value="{{$company->company_postal}}">
                            </div>
                        </div>
                        <div class="col-md-10" style="margin-top:10px;">
                            <div class="valid-info">
                                <h5>Website Link:</h5>
                                <input type="url" class="form-control" name="company_web" placeholder="http://" value="{{$company->company_web}}">
                            </div>
                        </div>
                        <div class="col-md-10" style="margin-top: 10px;">
                            <div class="row valid-info">
                                <div class="col-md-12">
                                    <h5>Do you have existing booking system?</h5>
                                </div>
                                <div class="col-md-2">
                                    <input name="bookingSystem" type="radio" id="1bo" class="radio-col-deep-orange" value="1" required />
                                    <label for="1bo">Yes</label>
                                </div>
                                <div class="col-md-2">
                                    <input name="bookingSystem" type="radio" id="2bo" class="radio-col-deep-orange" value="2"/>
                                    <label for="2bo">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10" id="booking_system_name" style="display: none">
                            <div class="valid-info">
                                <h5>Booking system name:</h5>
                                <input type="text" class="form-control" name="book_system" placeholder="Let it empty if you dont have one" value="{{$company->book_system}}">
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <h4>Payment Data</h4>
                        <div class="col-md-3">
                            <div class="valid-info">
                                <h5>Bank Name*:</h5>
                                <select class="form-control" name="bank_name" value="{{$company->bank_name}}">
                                    <option>BRI</option>
                                    <option>BCA</option>
                                    <option>BNI</option>
                                    <option>Mandiri</option>
                                    <option>CIMB</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="valid-info">
                                <h5>Bank Account Number*:</h5>
                                <input type="text" class="form-control" name="bank_account_number" value="{{$company->bank_account_number}}">
                            </div>
                        </div>
                        <div class="row" style="margin:0px">
                            <div class="col-md-3 col-sm-3 col-xs-5 valid-info">
                                <h5>Title*:</h5>
                                {{ Form::select('bank_account_title', Helpers::salutation(), null ,['class' => 'form-control','id'=>'bank_account_title']) }}
                            </div>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <div class="valid-info">
                                    <h5>Account Holder Name*:</h5>
                                    <input type="text" class="form-control" name="bank_account_name" pattern="^[A-Za-z -]+$" value="{{$company->bank_account_name}}" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10" style="margin-top: 10px;">
                            <p>Bank Account Proof Upload:</p>
                            <ul>
                                <li>Provide your proof of your bank account number by uploading the scan / picture of the first page of your saving book or your e-banking</li>
                                <li>Bank account holder name must be the same with the company's owner name</li>
                            </ul>
                            <div class="col-md-12 valid-info" id="input" hidden>
                                <input id="bankPic" type="file" name="bank_pic">	
                            </div>
                            @if($company->bank_account_scan_path != null || $company->bank_account_scan_path != '')
                            <div class="col-md-8" id="value" cat="bank">
                                <div class="thumbnail">
                                    <img src="{{cdn($company->bank_account_scan_path)}}">
                                    <div class="caption">
                                        <button type="button" class="btn bg-red waves-effect" id="change">
                                            <i class="material-icons">replay</i>
                                            <span>Change</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-md-8" id="value" cat="bank">
                                <div class="thumbnail">
                                    <img src="http://placehold.it/500x300" class="img-responsive">
                                    <div class="caption">
                                        <button type="button" class="btn bg-red waves-effect" id="change">
                                            <i class="material-icons">replay</i>
                                            <span>Change</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <h4>Upload Document</h4>
                        <div class="row valid-info" style="padding-left:15px; padding-right:15px;">
                            <div class="col-md-12">
                                <p>What is your business ownership type?</p>
                            </div>
                            <div class="col-md-3">
                                <input name="company_ownership" type="radio" id="1o" class="radio-col-deep-orange" value="Company" required/>
                                <label for="1o">Corporate</label>
                            </div>
                            <div class="col-md-3">
                                <input name="company_ownership" type="radio" id="2o" class="radio-col-deep-orange" value="Personal" required checked/>
                                <label for="2o">Personal</label>
                            </div>
                        </div>
                        <h5 style="padding-left:15px; padding-right:15px;">Please upload softcopy of documents listed below or <b>you can skip and provide it later</b>.</h5>
                        <h5 style="padding-left:15px; padding-right:15px;">Accepted document format: PDF/JPG/JPEG/PNG. Maximum size is 5 MB per file</h5>
                        <div class="col-md-12" id="akta" style="margin-top: 10px;display: none; margin-left:15px; margin-right:15px;" >
                            <div class="row">
                                <div class="col-md-12">
                                    <p style="font-weight: bold">Company Article of Association</p>
                                    <p>Akta Pendirian Usaha</p>		
                                </div>
                                <div class="col-md-6" id="input" hidden>
                                    <input id="aktaPic" type="file" name="akta_pic" />		
                                </div>
                                @if($company->akta_path != null || $company->akta_path != '')
                                <div class="col-md-6" id="value" cat="akta">
                                    <div class="thumbnail">
                                        <img src="{{cdn($company->akta_path)}}">
                                        <div class="caption">
                                            <button type="button" class="btn bg-red waves-effect" id="change">
                                                <i class="material-icons">replay</i>
                                                <span>Change</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6" id="value" cat="akta">
                                    <div class="thumbnail">
                                        <img src="http://placehold.it/500x300" class="img-responsive">
                                        <div class="caption">
                                            <button type="button" class="btn bg-red waves-effect" id="change">
                                                <i class="material-icons">replay</i>
                                                <span>Change</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12" id="siup" style="display: none;margin:10px 15px 0px 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <p style="font-weight: bold">SIUP/TDP</p>
                                    <p>Surat Izin Usaha Perdagangan/Tanda Daftar Perusahaan</p>
                                </div>
                                <div class="col-md-6" id="input" hidden>
                                    <input id="SIUPPic" type="file" name="siup_pic" />
                                </div>
                                @if($company->siup_path != null || $company->siup_path != '')
                                <div class="col-md-6" id="value" cat="siup">
                                    <div class="thumbnail">
                                        <img src="{{cdn($company->siup_path)}}">
                                        <div class="caption">
                                            <button type="button" class="btn bg-red waves-effect" id="change">
                                                <i class="material-icons">replay</i>
                                                <span>Change</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6" id="value" cat="siup">
                                    <div class="thumbnail">
                                        <img src="http://placehold.it/500x300" class="img-responsive">
                                        <div class="caption">
                                            <button type="button" class="btn bg-red waves-effect" id="change">
                                                <i class="material-icons">replay</i>
                                                <span>Change</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12" id="npwp" style="margin:10px 15px 0px 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <p id="npwp" style="font-weight: bold"> Tax Number</p>
                                    <p id="npwp">NPWP</p>
                                </div>
                                <div class="col-md-6" id="input" hidden>
                                    <input id="NPWPPic" type="file" name="npwp_pic" />
                                </div>
                                @if($company->npwp_path != null || $company->npwp_path != '')
                                <div class="col-md-6" id="value" cat="npwp">
                                    <div class="thumbnail">
                                        <img src="{{cdn($company->npwp_path)}}">
                                        <div class="caption">
                                            <button type="button" class="btn bg-red waves-effect" id="change">
                                                <i class="material-icons">replay</i>
                                                <span>Change</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6" id="value" cat="npwp">
                                    <div class="thumbnail">
                                        <img src="http://placehold.it/500x300" class="img-responsive">
                                        <div class="caption">
                                            <button type="button" class="btn bg-red waves-effect" id="change">
                                                <i class="material-icons">replay</i>
                                                <span>Change</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12" id="ktp" style="margin:10px 15px 0px 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <p id="ktp" style="font-weight: bold">Identity Card</p>
                                    <p id="ktp">KTP</p>
                                </div>
                                <div class="col-md-6" id="input" hidden>
                                    <input id="KTPPic" type="file" name="ktp_pic">
                                </div>
                                @if($company->ktp_path != null || $company->ktp_path != '')
                                <div class="col-md-6" id="value" cat="ktp">
                                    <div class="thumbnail">
                                        <img src="{{cdn($company->ktp_path)}}">
                                        <div class="caption">
                                            <button type="button" class="btn bg-red waves-effect" id="change">
                                                <i class="material-icons">replay</i>
                                                <span>Change</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6" id="value" cat="ktp">
                                    <div class="thumbnail">
                                        <img src="http://placehold.it/500x300" class="img-responsive">
                                        <div class="caption">
                                            <button type="button" class="btn bg-red waves-effect" id="change">
                                                <i class="material-icons">replay</i>
                                                <span>Change</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12" id="evi" style="display: none; margin:10px 15px 0px 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <p style="font-weight: bold">Evidence you have a product</p>
                                    <p>Contoh product/info product</p>
                                </div>
                                <div class="col-md-6" id="input" cat="evi" hidden>
                                    <input id="eviPic" type="file" name="evi_pic" >
                                </div>
                                @if($company->evidance_path != null || $company->evidance_path != '')
                                <div class="col-md-6" id="value" cat="evi">
                                    <div class="thumbnail">
                                        <img src="{{cdn($company->evidance_path) }}">
                                        <div class="caption">
                                            <button type="button" class="btn bg-red waves-effect" id="change">
                                                <i class="material-icons">replay</i>
                                                <span>Change</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6" id="value" cat="evi">
                                    <div class="thumbnail">
                                        <img src="http://placehold.it/500x300" class="img-responsive">
                                        <div class="caption">
                                            <button type="button" class="btn bg-red waves-effect" id="change">
                                                <i class="material-icons">replay</i>
                                                <span>Change</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if($company->status == 1)
                        <div class="col-md-2 col-md-offset-8">
                            <a href="{{ url('/product/tour-activity/create') }}">
                                <button type="button" class="btn btn-block bg-orange btn-lg waves-effect">Add product</button>
                            </a>
                        </div>
                        @endif
                        <div class="col-md-2" id="submit" hidden>
                            <button type="submit" class="btn btn-block bg-green btn-lg waves-effect">SAVE</button>
                        </div>
                    </div>
                {{ Form::close() }}
                </div>
            </div>
        @if($company->status != 5 && $company->status != 6)
        
            @if($data != null)
            <div class="card" id="sample_product" >
                <div class="header">
                    <h2>Edit Tour Activity</h2>
                    <ul class="header-dropdown m-r--5">
                        <li>
                            <button type="button" class="btn bg-teal btn-block waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Schedule</button>
                        </li>
                        <li>
                            <a href="{{ url('/product/tour-activity/'.$data->id.'/edit') }}" class="btn bg-deep-orange btn-waves">Edit Product</a>
                        </li>
                    </ul>
                </div>
                <div class="body" id="content">
                    <div id="step">
                        @include('errors.error_notification')
                        <h3>General Information</h3>
                        <section>
                            <div class="row clearfix">
                            @if(isset($data))
                                {{ Form::model($data, ['route' => ['tour-activity.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'product_form','enctype' =>'multipart/form-data']) }}
                            @else
                                {{ Form::open(['url'=>'product/tour-activity', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'product_form','enctype' =>'multipart/form-data']) }}
                            @endif
                                {{ Form::hidden('step',1)}}
                                <div class="col-md-12">
                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <div class="col-md-4 cover-image">
                                                <label>Cover Product Image*</label>
                                                <div class="dd-main-image">
                                                    <img style="width: 100%" src="{{(!empty($data)? cdn($data->cover_path.'/'.$data->cover_filename) : 'http://via.placeholder.com/400x300' )}}" id="cover-img">
                                                </div>
                                                {{ Form::hidden('image_resize', null, ['class' => 'form-control','required'=>'required']) }}
                                                <input name="cover_img" id="c_p_file" type='file' style="display: none" accept="image/x-png,image/gif,image/jpeg">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group m-b-20">
                                                    <label>Company(*)</label>
                                                    <select name="company_id" class="form-control" id="company_id" required>
                                                        @if(!empty($data->company_id) && !empty($data->company))
                                                        <option value="{{$data->company_id}}">{{$data->company->company_name}}</option>
                                                        @else
                                                        <option value="">--Select Company--</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-md-6">
                                                        <div class="form-group m-b-20">
                                                            <label>Product Category(*)</label>
                                                            {!! Form::select('product_category',Helpers::productCategory(),null,['class' => 'form-control show-tick']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Product Type(*)</label>
                                                            <div class="input-group dd-group">
                                                                    {!! Form::select('product_type',Helpers::productType(),null,['class' => 'form-control']) !!}
                                                                <span class="input-group-addon">
                                                                    <a href="#" class="info-type" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="left" title="" data-content="Within a single commencing schedule, customers can book for their own private group. They won't be grouped with another customers." data-original-title="Private Group"><i class="material-icons">info_outline</i></a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group m-b-20">
                                                    <label>Product Name(*)</label>
                                                    {{ Form::text('product_name', null, ['class' => 'form-control','id'=>'product_name','required'=>'required']) }}
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-md-6">
                                                        <div class="form-group m-b-20">
                                                            <label>Min Person(*)</label>
                                                            {{ Form::text('min_person', null, ['class' => 'form-control','id'=>'min_person','required'=>'required']) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group m-b-20">
                                                            <label>Max Person(*)</label>
                                                            {{ Form::text('max_person', null, ['class' => 'form-control','id'=>'max_person','required'=>'required']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 m-t-20">
                                        <div class="col-md-6"> 
                                            {{ Form::hidden('meeting_point_latitude', null, ['placeholder'=>'Latitude','id'=>'lat']) }}
                                            {{ Form::hidden('meeting_point_longitude', null, ['placeholder'=>'Longitude','id'=>'lng']) }}
                                            <div class="form-group m-b-20">
                                            <label>Starting Point/Gathering Point(where should your costumer meet you)?*</label> 
                                            {{ Form::text('meeting_point_address', null, ['class' => 'form-control','id'=>'meeting_point_address','required'=>'required']) }}
                                            </div>
                                            <div class="form-group m-b-20">
                                                <label>Meeting Point Notes</label>
                                                {{ Form::textArea('meeting_point_note', null, ['class' => 'form-control no-resize','rows'=>"4"]) }}
                                            </div>
                                            <div class="form-group m-b-20">
                                                <label>PIC Name(*)</label>
                                                {{ Form::text('pic_name', null, ['class' => 'form-control','required'=>"required"]) }}
                                            </div>
                                            <div class="form-group m-b-20">
                                                <label>PIC Phone(*)</label>
                                                <input type="hidden" class="form-control" id="PICFormat" name="format_pic_phone">   
                                                <input style="width: 100%;margin-top: 5px" type="text" class="form-control" id="PICPhone" name="pic_phone" data-old="@if(!empty(old('pic_phone'))){{old('pic_phone')}} @elseif(!empty($data)){{$data->pic_phone}}@endif" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="map_canvas"></div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group m-b-20">
                                                <label>Term & Condition(*)</label>
                                                {{ Form::textArea('term_condition', null, ['class' => 'form-control no-resize','rows'=>"4","required" => "required"]) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            {{Form::close()}}
                            </div>
                        </section>
                        <h3>Activity Details</h3>
                        <section>
                            <div class="row clearfix">
                            @if(isset($data))
                                {{ Form::model($data, ['route' => ['tour-activity.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'step_2','enctype' =>'multipart/form-data']) }}
                            @else
                                {{ Form::open(['url'=>'product/tour-activity', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'step_2','enctype' =>'multipart/form-data']) }}
                            @endif
                            {{ Form::hidden('step',2)}}
                            <div class="col-md-12 activity-details">
                                <div class="col-md-12 dd-cli">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4 class="dd-title m-t-20">
                                                Activity Duration
                                            </h4>
                                            <h5>How long is the duration of your tour/activity ?</h5>
                                            <div class="col-md-3">
                                                <input name="schedule_type" type="radio" id="1d" class="radio-col-deep-orange schedule-type" value="1" sel="1" required @if($data->schedule_type == 1) checked @elseif($data->schedule_type == 0) 
                                                checked
                                                @endif/>
                                                <label for="1d">Multiple days</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input name="schedule_type" type="radio" id="2d" class="radio-col-deep-orange schedule-type" value="2" sel="2" @if($data->schedule_type == 2) checked @endif/>
                                                <label for="2d">A couple of hours</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input name="schedule_type" type="radio" id="3d" class="radio-col-deep-orange schedule-type" value="3" sel="3" @if($data->schedule_type == 3) checked @endif/>
                                                <label for="3d">One day full</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <h5>This is related with your itinerary.</h5>
                                            <div class="col-md-2 scheduleDays">
                                                <div class="form-group">
                                                    <h5>Day?* :</h5>
                                                    <select class="form-control" id="day" name="day"  required>
                                                        @for($i=2;$i<=24;$i++)
                                                        <option values="{{$i}}" @if(old('day') == $i) selected @elseif(count($data->itineraries) == $i) selected @endif>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 scheduleHours" hidden>
                                                <div class ="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Hours?* :</label>
                                                            <select class="form-control" id="hours" name="hours" required>
                                                                @for($i=1;$i<12;$i++)
                                                                <option values="{{$i}}" @if(old('hours') ==$i)selected @elseif($data->schedule_type == 2 &&
                                                                (int)substr($data->schedule_interval,0,2) == $i) selected @endif>{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Minutes?* :</label>
                                                            <select class="form-control" id="minutes" name="minutes" required>
                                                                <option values="0" @if(old('minutes') ==0)selected @elseif($data->schedule_type == 2 &&
                                                                (int)substr($data->schedule_interval,3) == 0) selected @endif>0</option>
                                                                <option values="30" @if(old('minutes') ==30)selected @elseif($data->schedule_type == 2 &&
                                                                (int)substr($data->schedule_interval,3) == 30) selected @endif>30</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="dd-title m-t-20">
                                        Destination Details
                                    </h4>
                                    <h5>List down all destination related to your tour package / activity.</h5>
                                    <h5>The more accurate you list down the destinations, better your product's peformance in search result.</h5>
                                    <div class="master-destinations">
                                        <div class="row clearfix">
                                            <div class="col-md-3 col-province">

                                                <h5>Province*</h5>
                                                <select  class="form-control province-sel" name="place[0][province]" id="0-province" data-id="0" style="width: 100%" required>
                                                    <option value="" selected>-- Select Province --</option>
                                                    @if(!empty($provinces))
                                                        @foreach($provinces as $province)
                                                            @if(count($data->destinations) !=0)
                                                            <option value="{{$province->id}}" @if($data->destinations[0]->province_id == $province->id) selected="" @endif>{{$province->name}}</option>
                                                            @else
                                                            <option value="{{$province->id}}">{{$province->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-3 col-city">
                                                <h5>City*</h5>
                                                <select  class="form-control city-sel" name="place[0][city]" id="0-city" style="width: 100%" data-id="0" required>
                                                    @if(count($data->destinations) !=0)

                                                    <option value="{{$data->destinations[0]->city_id}}" selected="">{{$data->destinations[0]->city->name}}</option>
                                                    @else
                                                    <option value="" selected>-- Select City --</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-destination">
                                                <h5>Destination</h5>
                                                <select class="form-control destination-sel" id="0-destination" name="place[0][destination]" style="width: 100%">
                                                    @if(count($data->destinations) !=0)
                                                    <option value="{{$data->destinations[0]->destination_id}}" selected="">@if(!empty($data->destinations[0]->destination_id)) $data->destinations[0]->destination->name @endif</option>
                                                    @else
                                                    <option value="" selected>-- Select City --</option>
                                                    @endif
                                                </select>
                                                <b style="font-size:10px">Leave empty if you can't find the destination</b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dynamic-destinations">
                                        @if(!empty($data))
                                            @foreach($data->destinations as $index => $destination)
                                            @if($index > 0)
                                        <div class="row clearfix">
                                            <div class="col-md-3 col-province">
                                                <h5>Province*</h5>
                                                <select  class="form-control province-sel" name="place[{{$index}}][province]" id="{{$index}}-province" data-id="{{$index}}" style="width: 100%" required>
                                                    <option value="" selected>-- Select Province --</option>
                                                    @if(!empty($provinces))
                                                    @foreach($provinces as $province)
                                                        @if(!empty($destination))
                                                        <option value="{{$province->id}}" @if($destination->province_id == $province->id) selected="" @endif>{{$province->name}}</option>
                                                        @else
                                                        <option value="{{$province->id}}">{{$province->name}}</option>
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-city">
                                                <h5>City*</h5>
                                                <select  class="form-control city-sel" name="place[{{$index}}][city]" id="{{$index}}-city" data-id="{{$index}}" style="width: 100%" required>
                                                    @if(!empty($destination))
                                                    <option value="{{$destination->city_id}}" selected="">{{$destination->city->name}}</option>
                                                    @else
                                                    <option value="" selected>-- Select City --</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-destination">
                                                <h5>Destination</h5>
                                                <select class="form-control destination-sel" id="{{$index}}-destination" name="place[{{$index}}][destination]" style="width: 100%">
                                                    @if(!empty($destination))
                                                    <option value="{{$destination->destination_id}}" selected="">@if(!empty($destination->destination_id)) $destination->destination->destination_name @endif</option>
                                                    @else
                                                    <option value="" selected>-- Select Destination --</option>
                                                    @endif
                                                </select>
                                                <b style="font-size:10px">Leave empty if you can't find the destination</b>
                                            </div>
                                        </div>
                                            @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12" >
                                    <h4 class="dd-title m-t-20">
                                        Activity Tag
                                    </h4>
                                    <h5>How would you describe the activities in this product?</h5>
                                    <select class="form-control" id="activity_tag" name="activity_tag[]" multiple="multiple" style="width: 100%">
                                        @if(!empty($data))
                                            @foreach($data->activities as $activity)
                                                <option value="{{$activity->id}}" selected>{{$activity->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                    
                            </div>
                            {{Form::close()}}
                            </div>
                        </section>
                        <h3>Itinerary</h3>
                        <section>
                        @if(isset($data))
                            {{ Form::model($data, ['route' => ['tour-activity.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'step_3','enctype' =>'multipart/form-data']) }}
                        @else
                            {{ Form::open(['url'=>'product/tour-activity', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'step_3','enctype' =>'multipart/form-data']) }}
                        @endif   
                            {{ Form::hidden('step',3)}}
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="dd-title m-t-20">
                                        Itinerary Details
                                    </h4>
                                    @foreach($data->itineraries as $itinerary)
                                    <div class="col-md-12">
                                        <h5>Day-{{$itinerary->day}}</h5>
                                        {{Form::hidden('id[]',$itinerary->id)}}
                                        <div class="col-md-2 valid-info" id="field_itinerary_{{$itinerary->id}}">
                                            <h5>Start time*</h5>
                                            {{ Form::text('start_time[]', substr($itinerary->start_time,0,5), ['class' => 'form-control','placeholder'=>"HH:mm",'required'=>'required']) }}
                                        </div>
                                        <div class="col-md-2 valid-info" id="field_itinerary_{{$itinerary->id}}">
                                            <h5>End time*</h5>
                                            {{ Form::text('end_time[]', substr($itinerary->end_time,0,5), ['class' => 'form-control','placeholder'=>"HH:mm",'required'=>'required']) }}
                                        </div>
                                        <div class="col-md-8 valid-info" id="field_itinerary_{{$itinerary->id}}">
                                            <h5>Description</h5>
                                            {{ Form::textArea('description[]', $itinerary->description, ['class' => 'form-control','rows'=>"6"]) }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                            </div>
                        {{ Form::close() }}
                        </section>
                        <h3>Pricing</h3>
                        <section>
                            @if(isset($data))
                                {{ Form::model($data, ['route' => ['tour-activity.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'step_4','enctype' =>'multipart/form-data']) }}
                            @else
                                {{ Form::open(['url'=>'product/tour-activity', 'method'=>'POST', 'class'=>'form-horizontal','id'=>'step_4','enctype' =>'multipart/form-data']) }}
                            @endif
                            {{ Form::hidden('step',4)}}
                            <h4 class="dd-title m-t-20">
                                Pricing Details
                            </h4>
                            <div class="col-md-12">
                                <div class="row valid-info">
                                    <div class="col-md-4">
                                        <input name="price_kurs" type="radio" id="1p" class="radio-col-deep-orange" value="1" required
                                        @if(count($data->prices) !== 0)
                                            @if(empty($data->prices[0]->price_usd))
                                                checked
                                            @endif
                                        @else
                                            @if(empty($data->price_idr))
                                                checked
                                            @endif
                                        @endif 
                                        @if(!empty($data->price_idr) && empty($data->price_usd))
                                            checked 
                                        @endif
                                        />
                                        <label for="1p" style="font-size:15px">I only have pricing in IDR</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="price_kurs" type="radio" id="2p" class="radio-col-deep-orange" value="2" @if(count($data->prices) !== 0)@if(!empty($data->prices[0]->price_usd)) checked @endif @endif @if(!empty($data->price_idr) && !empty($data->price_usd)) checked @endif/>
                                        <label for="2p" style="font-size:15px">I want to add pricing in USD for international tourist</label>
                                    </div>
                                </div>
                                <div class="row" id="price_row">
                                    <div class="col-md-3">
                                        <h5>Pricing Option</h5>
                                        <select name="price_type" id="priceType" class="form-control" required>
                                            <option value="1" @if(count($data->prices) == 0)) selected @endif>Fixed Price</option>
                                            <option value="2" @if(count($data->prices) != 0 ) selected @endif>Based on Number of Person</option>
                                        </select>
                                    </div>
                                    <div id="price_fix">
                                        <div class="col-md-3" id="price_idr">
                                            <h5>Price (IDR)*:</h5>
                                            <input type="hidden" name="price[0][people]" value="fixed"> 
                                            <input type="text" value="{{(int)$data->price_idr}}" id="idr" name="price[0][IDR]" class="form-control" required />     
                                        </div>
                                        <div class="col-md-3 valid-info" id="price_usd" @if(count($data->prices) !== 0)@if(!empty($data->prices[0]->price_usd)) style="display: block"
                                        @else style="display: block" @endif @endif @if(!empty($data->price_idr) && !empty($data->price_usd)) style="display: block" @else style="display: none" @endif >
                                            <h5>Price (USD)*</h5>
                                            <input type="text" id="usd" value="{{(int)$data->price_usd}}" name="price[0][USD]" class="form-control" />     
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="display: none" id="price_table_container">
                                <div class="row">
                                <h4 class="dd-title m-t-20">
                                    Pricing Tables
                                </h4>
                                    <div class="col-md-12" id="price_list" style="display: none">
                                        <div class="row">
                                            <div class="col-md-1" style="padding: 20px 0px 0px 0px;">
                                                <h5><i class="material-icons">person</i></h5>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="row">
                                                    <div class="col-md-6 valid-info" id="price_idr">
                                                        <h5>Price (IDR)*</h5>
                                                        <input id="price_list_field1" type="hidden" required>  
                                                        <input id="price_list_field2" type="text" class="form-control" required>     
                                                    </div>
                                                    <div class="col-md-6 valid-info" id="price_usd" style="display: none">
                                                        <h5>Price (USD)*</h5>
                                                        <input id="price_list_field3" type="text" class="form-control" required />     
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="price_list_container">
                                        <div class="row">
                                            <div class="col-md-6" id="price_list_container_left">
                                                @if(count($data->prices) != 0)
                                                <?php $count = count($data->prices); ?>
                                                    @foreach($data->prices as $index => $val)
                                                        @if($index < ceil($count/2))
                                                        <div class="col-md-12" id="price_list{{$index}}">
                                                            <div class="row">
                                                                <div class="col-md-1" style="padding: 20px 0px 0px 0px;">
                                                                    <h5><i class="material-icons">person</i></h5>
                                                                </div>
                                                                <div class="col-md-11">
                                                                    <div class="row">
                                                                        <div class="col-md-6 valid-info" id="price_idr">
                                                                            <h5>Price (IDR)*</h5>
                                                                            <input id="price_list_field1" type="hidden" name="price[{{$index}}][people]" value="{{$val->number_of_person}}" required>  
                                                                            <input id="price_list_field2" type="text" name="price[{{$index}}][IDR]" class="form-control" value="{{(int)$val->price_idr}}" required>     
                                                                        </div>
                                                                        <div class="col-md-6 valid-info" id="price_usd" @if(!empty($price_usd))style="display: none" @endif>
                                                                            <h5>Price (USD)*</h5>
                                                                            <input id="price_list_field3" name="price[{{$index}}][USD]"  type="text" class="form-control" value="{{(int)$val->price_usd}}" required />     
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="col-md-6" id="price_list_container_right">
                                                @if(count($data->prices) != 0)
                                                    @foreach($data->prices as $index => $val)
                                                        @if($index >= ceil($count/2))
                                                        <div class="col-md-12" id="price_list{{$index}}">
                                                            <div class="row">
                                                                <div class="col-md-1" style="padding: 20px 0px 0px 0px;">
                                                                    <h5><i class="material-icons">person</i></h5>
                                                                </div>
                                                                <div class="col-md-11">
                                                                    <div class="row">
                                                                        <div class="col-md-6 valid-info" id="price_idr">
                                                                            <h5>Price (IDR)*</h5>
                                                                            <input id="price_list_field1" type="hidden" name="price[{{$index}}][people]" value="{{$val->number_of_person}}" required> 
                                                                            <input id="price_list_field2" type="text" name="price[{{$index}}][IDR]" class="form-control" value="{{(int)$val->price_idr}}" required>     
                                                                        </div>
                                                                        <div class="col-md-6 valid-info" id="price_usd" @if(!empty($price_usd))style="display: none" @endif>
                                                                            <h5>Price (USD)*</h5>
                                                                            <input id="price_list_field3" name="price[{{$index}}][USD]"  type="text" class="form-control" value="{{(int)$val->price_usd}}" require />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <h4 class="dd-title m-t-20">
                                        Pricing Includes
                                    </h4>
                                    <div class="col-md-6 valid-info">
                                        <h5>What's already included with pricing you have set?What will you provide?</h5>
                                        <h5 style="font-size: 18px">Example: Meal 3 times a day, mineral water, driver as tour guide.</h5>
                                        <select type="text" class="form-control" name="price_includes[]" multiple="multiple" style="width: 100%">
                                            @foreach($data->includes as $include)
                                                <option selected>{{$include->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6" style="padding-top:85px">
                                        <h5>Type a paragraph and press Enter.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <h4 class="dd-title m-t-20">
                                        Pricing Exclude
                                    </h4>
                                    <div class="col-md-6 valid-info">
                                        <h5>What's not included with pricing you have set?Any extra cost the costumer should be awere of?</h5>
                                        <h5 style="font-size: 18px">Example: Entrance fee IDR 200,000, bicycle rental, etc</h5>
                                        <select class="form-control" name="price_excludes[]" multiple="multiple" style="width: 100%">
                                            @foreach($data->excludes as $exclude)
                                                <option value="{{$exclude->name}}" selected="">{{$exclude->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6" style="padding-top:85px">
                                        <h5>Type a paragraph and press Enter.</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <h4 class="dd-title m-t-20">
                                        Cancellation Policy
                                    </h4>
                                    <div class="col-md-3">
                                        <input name="cancellation_type" type="radio" id="1c" class="radio-col-deep-orange" value="0" required @if($data->cancellation_type == 0) checked @elseif($data->cancellation_type == null) checked @endif />
                                        <label for="1c">No cancellation</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input name="cancellation_type" type="radio" id="2c" class="radio-col-deep-orange" value="1"  @if($data->cancellation_type == 1) checked @endif />
                                        <label for="2c">Free cancellation</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input name="cancellation_type" type="radio" id="3c" class="radio-col-deep-orange" value="2"   @if($data->cancellation_type == 2) checked @endif />
                                        <label for="3c">Cancellation policy applies</label>
                                    </div>
                                </div>
                                <div class="row" id="cancel_policy" style="display: none;margin:0px">
                                    <h5>How is your cancellation policy?</h5>
                                    <div class="row" style="font-size: 14px;margin:0px">
                                        <div class="col-md-2" style="margin:5px;padding:0px;width:auto">
                                            <h5>Cancellation less than</h5>
                                        </div>
                                        <div class="col-md-1 valid-info" style="margin:5px;padding:0px">
                                            <input type="text" id="cancellationDay" name="max_cancellation_day" class="form-control" placeholder="Day" value="{{$data->max_cancellation_day}}" required>
                                        </div>
                                        <div class="col-md-2" style="margin:5px;padding:0px;width:auto">
                                            <h5>days from shcedule, cancellation fee is</h5>
                                        </div>
                                        <div class="col-md-1 valid-info" style="margin:5px;padding:0px">
                                            <input type="text" id="cancellationFee" name="cancel_fee" class="form-control" placeholder="Percent" value="{{$data->cancellation_fee}}" required>
                                        </div>
                                        <div class="col-md-2" style="margin:5px;padding:0px;width:auto">
                                            <h5>percent(%)</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{Form::close()}}
                        </section>
                        <h3>Images</h3>
                        <section>
                            <h4 class="dd-title m-t-20">
                                Activity Image
                            </h4>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="file-loading">
                                        <input id="activity_images" name="activity_images[]" type="file" multiple>
                                    </div>
                                </div>
                            </div>
                            <h4 class="dd-title m-t-20">
                                Destination Image
                            </h4>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="file-loading">
                                        <input id="destination_images" name="destination_images[]" type="file" multiple>
                                    </div>
                                </div>
                            </div>
                            <h4 class="dd-title m-t-20">
                                Accomodation Image
                            </h4>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="file-loading">
                                        <input id="accomodation_images" name="accomodation_images[]" type="file" multiple>
                                    </div>
                                </div>
                            </div>
                            <h4 class="dd-title m-t-20">
                                Others Image
                            </h4>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="file-loading">
                                        <input id="other_images" name="other_images[]" type="file" multiple>
                                    </div>
                                </div>
                            </div>
                            <h4 class="dd-title m-t-20"><i class="material-icons">perm_media</i> Video URL</h4>
                            <h5>Add your video link to embed the video into your product's gallery.</h5>
                            {{ Form::model($data, ['route' => ['tour-activity.update', $data->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'step_5','enctype' =>'multipart/form-data']) }}
                            {{ Form::hidden('step',5)}}
                            <div class="row" id="embed" style="display: block;margin-bottom: 10px">
                                
                                <div class="col-md-6" >
                                    <input type="url" class="form-control" name="videoUrl[]" value="@if(count($data->videos) !== 0){{$data->videos[0]->url}}@endif" />
                                </div>
                            </div>
                            <div id="clone_dinamic_video">
                                @if(count($data->videos) !== 0)
                                    @foreach($data->videos as $index => $vid)
                                    @if($index > 0)
                                        <div class="row dinamic_video6" id="embed" style="display: block;margin-bottom: 10px">
                                            <div class="col-md-6">
                                                <input type="url" class="form-control" name="videoUrl[]" value="{{$vid->url}}">
                                            </div>
                                        </div>
                                    @endif
                                    @endforeach
                                @endif
                            </div>
                            </form>
                        </section>
                        
                    </div>
                </div>
            </div>
            @endif
        
        @endif
        </div>
        <!-- Status Modal -->
        <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                
                    <div class="modal-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="modal-title" id="defaultModalLabel">Status</h4>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-sm btn-warning right btn-change-status">Change Status</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped log-status-list">
                            <tbody>
                                @if(!empty($company->log_statuses))
                                @foreach($company->log_statuses as $test)
                                
                                <tr>
                                    <td>
                                        @if($test->status == 0)
                                        <span class="badge bg-purple">Not Verified</span>
                                        @elseif($test->status ==1)
                                        <span class="badge bg-blue">Awaiting Submission</span>
                                        @elseif($test->status ==2)
                                        <span class="badge bg-cyan">Awaiting Moderation</span>
                                        @elseif($test->status ==3)
                                        <span class="badge bg-pink">Insufficient Data</span>
                                        @elseif($test->status ==4)
                                        <span class="badge bg-red">Rejected</span>
                                        @elseif($test->status ==5)
                                        <span class="badge bg-green">Active</span>
                                        @else
                                        <span class="badge bg-red">Disbaled</span>
                                        @endif
                                    </td>
                                    <td>{{date_format($test->created_at,"d/M/Y H:i:s")}}</td>
                                    <td>{{$test->note}}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="change-status row clearfix" style="display: none">
                        <form method="POST" action="{{ url('partner/'.$company->id.'/change/status') }}" enctype="multipart/form-data">
                        @csrf
                            <div class="col-md-12">
                                <div class="valid-info">
                                    <h5>Status:</h5>
                                    <select class="form-control" name="status" required>
                                        @if($company->status == 5 || $company->status == 6)
                                            <option value="5" @if($company->status == 5) selected @endif>Active</option>
                                            <option value="6" @if($company->status == 6) selected @endif>Disabled</option>
                                        @else
                                            <option value="2" @if($company->status == 2) selected @endif>Awaiting Moderation</option>
                                            <option value="3" @if($company->status == 3) selected @endif>Insufisient Data</option>
                                            <option value="4" @if($company->status == 4) selected @endif>Rejected</option>
                                            <option value="5" @if($company->status == 5) selected @endif>Active</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="valid-info">
                                    <h5>Note:</h5>
                                    <textarea class="form-control" name="note" rows="6"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" style="display: none" class="btn btn-link waves-effect btn-save">SAVE CHANGES</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        
        <!-- Croper Modal -->
        <div class="modal fade" id="croperModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Cropper Image</h4>
                    </div>
                    <div class="modal-body">
                        <div class="img-container">
                            <img id="crop-image" src="" alt="Picture" class="img-responsive">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link waves-effect btn-img-save">SAVE CHANGES</button>
                        <button type="button" class="btn btn-link waves-effect btn-img-close" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
        @if($data != null)
        <!-- Schedule -->
        <div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="largeModalLabel">Schedule List</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th id="startDate">Start Date</th>
                                    <th id="endDate">End Date</th>
                                    <th id="startHours">Start Hours</th>
                                    <th id="endHours">End Hours</th>
                                    <th id="maxBookDate">Max Booking Date</th>
                                    <th id="maxBook">Max Booking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data->schedules) != 0)
                                    @foreach($data->schedules as $schedule)
                                    <tr id="value" data-id="{{$schedule->id}}">
                                        <td id="startDate">
                                            <p>{{date('d-m-Y',strtotime($schedule->start_date))}}</p>
                                        </td>
                                        <td id="endDate">
                                            <p>{{date('d-m-Y',strtotime($schedule->end_date))}}</p>
                                            
                                        </td>
                                        <td id="startHours">
                                            <p>{{$schedule->start_hours}}</p>
                                        </td>
                                        <td id="endHours">
                                            <p>{{$schedule->end_hours}}</p>
                                        </td>
                                        <td id="maxBookDate">
                                            <p>{{date('d-m-Y',strtotime($schedule->max_booking_date_time))}}</p>
                                        </td>
                                        <td id="maxBook">
                                            <p>{{$schedule->maximum_booking}}</p>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
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
    <!-- <script src="{{asset('js/pages/tables/jquery-datatable.js')}}"></script> -->
    <!-- JQuery Steps Plugin Js -->
    <script src="{{asset('plugins/jquery-steps/jquery.steps.js')}}"></script>
    <script src="{{asset('js/pages/ui/tooltips-popovers.js')}}"></script>
    <script src="{{asset('js/pages/geocomplete/jquery.geocomplete.min.js')}}"></script>
    <script src="{{ asset('plugins/cropper/cropper.min.js') }}"></script>
    <!-- Bootstrap File-Input-Js -->
    <script src="{{ asset('plugins/bootstrap-file-input/js/fileinput.js') }}" type="text/javascript"></script>
    <!-- Tel format -->
    <script src="{{ asset('plugins/telformat/js/intlTelInput.js') }}"></script>
    <!-- Mask js -->
	<script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script>
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAWlnymqJ5QiPRrM_NIvEMg9eQLuPzS4rE&libraries=places"></script>
    <!-- Light Gallery Plugin Js -->
    <script src="{{asset('plugins/light-gallery/js/lightgallery-all.js')}}"></script>
    <!-- Jquery Validation Plugin Css -->
	<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{asset('js/pages/medias/image-gallery.js')}}"></script>
    <script>
        $(document).ready(function () {
        // DISABLE ALL INPUT, SELECT, TEXTAREA
            $("input[type='text'],input[type='email'],input[type='radio'],input[type='url']").attr("disabled","disabled");
            $("select").attr("disabled","disabled");
            $("textarea").attr("disabled","disabled");
            $("button#change").closest(".caption").hide();
        // BOOKING SYSTEM CHOICE
            $("input[name='bookingSystem']").change(function(){
                var val = $(this).val();
                if(val == 1){
                    $("#booking_system_name").show();
                }else{
                    $("input[name='book_system']").val(null);
                    $("#booking_system_name").hide();
                }
            });
        // UPLOAD IMAGE
            $("#bankPic,#aktaPic,#SIUPPic,#NPWPPic,#KTPPic,#eviPic").fileinput({
                theme: 'fa',
                maxFileSize: 1000,
                showPreview: false,
                showRemove: false,
                showCancel: false,
                showUpload: false,
                allowedFileExtensions: ["jpg", "png", "gif","pdf","doc","docs","xls"]
            });
        // OWNERSHIP ELEMENT
            $("input[name='company_ownership']").change(function(){
                var val = $(this).val();
                if(val == "Company"){
                    $("#akta,#siup,#npwp,#ktp,#evi").show();
                    $("#npwp").find("h5#npwp").text("Company's Tax Number");
                    $("#npwp").find("p#npwp").text("NPWP Perusahaan");
                    $("#ktp").find("h5#ktp").text("Company Owner Identity Card");
                    $("#ktp").find("p#ktp").text("KTP Direksi Perusahaan");
                }else{
                    $("#npwp,#ktp").show();
                    $("#npwp").find("h5#npwp").text("Tax Number");
                    $("#npwp").find("p#npwp").text("NPWP");
                    $("#ktp").find("h5#ktp").text("Identity Card");
                    $("#ktp").find("p#ktp").text("KTP");
                    $("#akta,#siup,#evi").hide();
                }
            });
        // CITY
        $("select[name='province_id']").change(function(){
                var idProvince = $(this).val();
                $("select[name='city_id']").empty();
                $.ajax({
                    method: "GET",
                    url: "{{ url('json/findCity') }}",
                    data: { province_id: idProvince  }
                }).done(function(response) {
                    $.each(response, function (index, value) {
                        $("select[name='city_id']").append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
            });
        // MASK 
            $("input[name='company_phone'],input[name='phone'],input[name='pic_Phone']").mask('000-0000-00000');
            $("input[name='bank_account_number']").mask('0000000000000000');
            $("input[name='company_postal']").mask('00000');
        // PHONE
            // PIC COMPANY
			$("input[name='format']").val("+62");
			$("input[name='phone']").val("+62").intlTelInput({
				separateDialCode: true,
			});
			$(".country").click(function(){
				$(this).closest(".valid-info").find("input[name='format']").val("+"+$(this).attr("data-dial-code"));
			});
            // COMPANY
			$("input[name='format_company']").val("+62");
			$("input[name='company_phone']").val("+62").intlTelInput({
				separateDialCode: true,
			});
			$(".country").click(function(){
				$(this).closest(".valid-info").find("input[name='fomat_company']").val("+"+$(this).attr("data-dial-code" ));
			});
		// DETAIL
            // PHONE
                var dbphone = "{{(!empty($company->pic) ? $company->pic->phone : '+62-')}}";
                var dbformat = dbphone.split("-");
                var dbCompanyPhone = "{{$company->company_phone}}";
                var dbCompanyformat = dbCompanyPhone.split("-");
            // PROVINCE
                var province = '{{$company->province_id}}';
                var city = '{{$company->city_id}}';
                $("select[name='province_id']").find("option[value='"+province+"']").attr("selected","selected");
                $.ajax({
                    method: "GET",
                    url: "{{ url('json/findCity') }}",
                    data: { province_id: province  }
                }).done(function(response) {
                    $.each(response, function (index, value) {
                        $("select[name='city_id']").append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        ).find("option[value='"+city+"']").attr("selected","selected");
                    });
                });
            // ROLE
                var dbRole = '{{(!empty($company->pic) ? $company->pic->role_id : 0)}}'
                $("select[name='role']").find("option[value='"+dbRole+"']").attr("selected","selected");
            // BOOK SYS
                var dbBookSys = '{{$company->book_system}}';
                if(dbBookSys == null || dbBookSys == '' ){
                    $("input#2bo").attr("checked","checked");
                    $("#booking_system_name").hide();
                }else{
                    $("input#1bo").attr("checked","checked");
                    $("#booking_system_name").show();
                }
            // BANK NAME
                $("select[name='bank_name']").find("option:contains('{{$company->bank_name}}')").attr("selected","selected");
            // BANK TITLE
                $("select[name='bank_account_title']").find("option[value='{{$company->bank_account_title}}']").attr("selected","selected");
            // OWNERSHIP
                var dbownership = '{{$company->company_ownership}}';
                if(dbownership == 'Company'){
                    $("input#1o").attr('checked','checked');
                    $("#akta,#siup,#npwp,#ktp,#evi").show();
                    $("#npwp").find("h5#npwp").text("Company's Tax Number");
                    $("#npwp").find("p#npwp").text("NPWP Perusahaan");
                    $("#ktp").find("h5#ktp").text("Company Owner Identity Card");
                    $("#ktp").find("p#ktp").text("KTP Direksi Perusahaan");
                }else{
                    $("input#2o").attr('checked','checked');
                    $("#npwp,#ktp").show();
                    $("#npwp").find("h5#npwp").text("Tax Number");
                    $("#npwp").find("p#npwp").text("NPWP");
                    $("#ktp").find("h5#ktp").text("Identity Card");
                    $("#ktp").find("p#ktp").text("KTP");
                    $("#akta,#siup,#evi").hide();
                }
			// PIC 
                $("input[name='format']").val(dbformat[0]);
                $("input[name='phone']").val(dbformat[1]+'-'+dbformat[2]+'-'+dbformat[3]).intlTelInput({
                    separateDialCode: true,
                });
                if(dbphone != ""){
                    var dbformat = dbphone.split("-");
                    var pic_phone = "";
                    $(dbformat).each(function(index,value) {
                        if(index == 1){
                            pic_phone = value;
                        }
                        else if(index > 1){
                            pic_phone = pic_phone+'-'+value;
                        }

                    });
                    $("input[name='format']").val(dbformat[0]);
                    $("input[name='phone']").val(pic_phone).intlTelInput({
                        separateDialCode: true,
                    });
                }
			
			// COMPANY
                $("input[name='format_company']").val(dbCompanyformat[0]);
                $("input[name='company_phone']").val(dbCompanyformat[1]+'-'+dbCompanyformat[2]+'-'+dbCompanyformat[3]).intlTelInput({
                    separateDialCode: true,
                });
                $(".country").click(function(){
                    $(this).closest(".valid-info").find("#telformat").val("+"+$(this).attr("data-dial-code"));
                });
            // CHANGE PIC BUTTON
                $("button#change").click(function(){
                    $(this).closest("div#value").hide().siblings("div#input").show();
                })
            // EDIT BUTTON
                $('.modal-header').delegate('.btn-back','click', function(e){
                    $(this).text('Change Status');
                    $(this).removeClass('btn-back');
                    $(this).addClass('btn-change-status');
                    $('.change-status').hide();
                    $('.btn-save').hide();
                    $('.log-status-list').show();
                });
                
                $('.modal-header').delegate('.btn-change-status','click', function(e){
                    $(this).text('Back');
                    $(this).removeClass('btn-change-status');
                    $(this).addClass('btn-back');
                    $('.change-status').show();
                    $('.btn-save').show();
                    $('.log-status-list').hide();
                });
                $("button#edit").click(function(){
                    $(this).hide()
                    $("#change-status").show();
                    $("#submit").show();
                    $("div#company_detail input,#company_detail select,#company_detail textarea").removeAttr("disabled");
                    $("#sample_product input,#sample_product select,#sample_product textarea").attr("disabled","disabled");
                    $("button#change").closest(".caption").show();
                });
        });
    </script>

    <!-- SAMPEL PRODUCT -->
    <script type="text/javascript">
        $( window ).on( "load", function() {
            if($('#3d').prop('checked')){
                $(".scheduleDays, .scheduleHours").removeAttr("required").hide();;
            }else if($('#2d').prop('checked')){
                $(".scheduleHours").show();
                $(".scheduleDays").removeAttr("required").hide();
            }
            
            var cancel = $("#3c").prop("checked");
            if(cancel){
                $("#cancel_policy").show();
            }
            // console.log(cancel);
            var tpPrice = $('#1p').prop("checked");
            var opPrice = $('#priceType').val();
            if(tpPrice){
                $("#price_usd, #price_list_container #price_usd").hide();
            }else{
                $("#price_usd, #price_list_container #price_usd").show();
            }
            if(opPrice == 1){
                $("#price_fix").show();
                $("#price_table_container").hide();
                $("#price_list_container_left,#price_list_container_right").empty();
            }else{
                $("#price_fix").hide();
                $("#price_table_container").show(); 
             }     
            var hash = location.hash;
            if(hash != ""){
                $('#step').steps('setStep',location.hash.substring(location.hash.length-1));
                $('.steps ul li').removeClass('done');
            }
            @if(!empty(old('company_id')))
                $.ajax({
                    type: "GET",
                    data: {"id": {{old('company_id')}} },
                    url: '/json/company',
                    success: function(result) {
                        if(result.code == 200){
                            $("#company_id").select2("trigger", "select", {
                                data: { id: result.data[0].id,name: result.data[0].name }
                            });
                        }
                    }
                });
            @endif
            var image = $('input[name="image_resize"').val();
            if(image != ""){
                $('#cover-img').attr('src',image);
            }
            var dbphone = $('input[name="pic_phone"]').attr('data-old');
            if(dbphone != ""){
                var dbformat = dbphone.split("-");
                var pic_phone = "";
                $(dbformat).each(function(index,value) {
                    if(index == 1){
                        pic_phone = value;
                    }
                    else if(index > 1){
                        pic_phone = pic_phone+'-'+value;
                    }

                });
                $("input[name='format_pic_phone']").val(dbformat[0]);
                $("input[name='pic_phone']").val(pic_phone).intlTelInput({
                    separateDialCode: true,
                });
            }

        });
        $(document).ready(function () {
            
            $( "#product_form" ).validate({
              rules: {
                min_person: {
                  number: true
                },
                max_person: {
                  number: true
                }
              }
            });
            $( "#step_4" ).validate({
              rules: {
                "price[0][IDR]": {
                  number: true
                },
                "price[0][USD]": {
                  number: true
                },
                "max_cancellation_day":{
                    number:true
                },
                "cancel_fee":{
                    number:true
                }
              }
            });
            $( "#step_3" ).validate({
              rules: {
                
              }
            });
            $( "#step_2" ).validate({
              rules: {
                "activity_tag":{
                    required:true
                }
              }
            });
            $('.steps ul li a').click(function(){
                var temp = $(this).attr('href');
                location.hash = temp;
            });
        });
        $("#step").steps({
            headerTag: "h3",
            bodyTag: "section",
            enableAllSteps: true,
            enablePagination: false
        });
        $("#idr,#usd").each(function(){
            $(this).mask('0.000.000.000', {reverse: true});
        });
        $("#PICPhone").mask('000-0000-00000');
        $("#PICFormat").val("+62");
        $("#PICPhone").val("+62").intlTelInput({
            separateDialCode: true,
        });
        $(".country").click(function(){
            $(this).closest(".valid-info").find("#PICFormat").val("+"+$(this).attr( "data-dial-code" ));
        });
        $(function(){
            lat = $('#lng').val();
            lng = $('#lat').val();
            $("#meeting_point_address").geocomplete({
                map: ".map_canvas",
                details: ".place",
                @if(!empty($data->meeting_point_latitude) && !empty($data->meeting_point_longitude))
                location:[{{$data->meeting_point_latitude}},{{$data->meeting_point_longitude}}],
                @elseif(!empty(old('meeting_point_latitude')) && !empty(old('meeting_point_longitude')))
                location:[lat,lng],
                @endif
                mapOptions: {
                  zoom: 8,
                  scrollwheel: true,
                  mapTypeId: "roadmap"
                },

                blur: false,
                geocodeAfterResult: false,
                restoreValueAfterBlur: false
            });
            $("#meeting_point_address")
              .geocomplete()
              .bind("geocode:result", function(event, result){
                $("input#lat").val(result.geometry.location.lat());
                $("input#lng").val(result.geometry.location.lng());
              });
            $("#activity_tag").select2({
                ajax: {
                    url: "/json/activity",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        name: params.term, // search term
                        page: params.page,
                      };
                    },
                    processResults: function (data, params) {
                      // parse the results into the format expected by Select2
                      // since we are using custom formatting functions we do not need to
                      // alter the remote JSON data, except to indicate that infinite
                      // scrolling can be used
                      params.page = params.page || 1;
                      return {
                        results: data,
                        pagination: {
                          more: (params.page * 30) < data.total_count
                        }
                      };
                    },
                    cache: true
                  },
                  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                  minimumInputLength: 1,
                  templateResult: formatRepo, // omitted for brevity, see the source of this page
                  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
            });
            $("#company_id").select2({
                ajax: {
                    url: "/json/company",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        name: params.term, // search term
                        page: params.page,
                      };
                    },
                    processResults: function (data, params) {
                      // parse the results into the format expected by Select2
                      // since we are using custom formatting functions we do not need to
                      // alter the remote JSON data, except to indicate that infinite
                      // scrolling can be used
                      params.page = params.page || 1;
                      return {
                        results: data.data,
                        pagination: {
                          more: (params.page * 30) < data.total_count
                        }
                      };
                    },
                    cache: true
                  },
                  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                  minimumInputLength: 1,
                  templateResult: formatRepo, // omitted for brevity, see the source of this page
                  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
            });
            function formatRepo (repo) {
                  if (repo.loading) return repo.text;

                  var markup = "<div class='select2-result-repository clearfix'>" +

                    "<div class='select2-result-repository__meta'>" +
                      "<div class='select2-result-repository__title'>" + repo.name + "</div>";

                  "</div></div>";

                  return markup;
                }
            function formatRepoSelection (repo) {
              return repo.name || repo.text;
            }
        });
        $(document).ready(function () {

            window.addEventListener('DOMContentLoaded', function () {
                var image = document.getElementById('crop-image');
                var cropBoxData;
                var canvasData;
                var cropper;

                $('#croperModal').on('shown.bs.modal', function () {
                    cropper = new Cropper(image, {
                        autoCropArea: 1,
                        aspectRatio: 4/3,
                        strict: false,
                        guides: false,
                        highlight: false,
                        dragCrop: false,
                        zoomable: false,
                        scalable: false,
                        rotatable: false,
                        cropBoxMovable: true,
                        cropBoxResizable: false,
                        responsive: true,
                        viewMode: 1,
                        ready: function () {
                            // Strict mode: set crop box data first
                            cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                        }
                    });
                    
                }).on('hidden.bs.modal', function () {
                    cropBoxData = cropper.getCropBoxData();
                    canvasData = cropper.getCanvasData();
                    originalData = cropper.getCroppedCanvas();
                    cropper.destroy();
                });
                $('.btn-img-save').click(function(){
                    data = originalData = cropper.getCroppedCanvas();
                    console.log(originalData.toDataURL('image/jpeg'));
                    $('input[name="image_resize"]').val(originalData.toDataURL('image/jpeg'));
                    $('#cover-img').attr('src',originalData.toDataURL('image/jpeg'));
                    $('.btn-img-close').click();
                });
            });

            $('#c_p_picture').click(function(e){
                e.preventDefault();
                $('input[name="cover_img"]').click();

            });
            function readURL(input) {
            if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('img#crop-image').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(document).delegate('#c_p_file', 'change', function(e){
                e.preventDefault();
                $('#croperModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                readURL(this);
            });
            // ADDMORE
            

            $('.dd-cli').delegate('.province-sel','change',function(e){
                var id = $(this).attr('data-id');
                $.ajax({
                  method: "GET",
                  url: "{{ url('json/findCity') }}",
                  data: {
                    province_id: $(this).val()
                  }
                }).done(function(response) {
                    $('#'+id+'-city option').remove();
                    $.each(response, function (index, value) {
                        $('#'+id+'-city').append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
                $.ajax({
                  method: "GET",
                  url: "{{ url('json/destination') }}",
                  data: {
                    province_id: $(this).val()
                  }
                }).done(function(response) {
                    $.each(response.data, function (index, value) {
                        $('#'+id+'-destination').append(
                            "<option value="+value.id+">"+value.destination_name+"</option>"
                        );
                    });
                });
             });
            $('.dd-cli').delegate('.city-sel','change',function(e){
                var id = $(this).attr('data-id');
                $.ajax({
                  method: "GET",
                  url: "{{ url('json/findDestination') }}",
                  data: {
                    city_id: $(this).val()
                  }
                }).done(function(response) {
                    $('#'+id+'-destination option').remove();
                    $.each(response, function (index, value) {
                        $('#'+id+'-destination').append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
             });
            $("input[name='price_kurs']").change(function () {
                var priceKurs = $(this).val();
                $("#price_row").show();
                if(priceKurs == 1){
                    $("#price_usd, #price_list_container #price_usd").hide();
                    $("#price_usd input, #price_list_container #price_usd input").val(null);
                }else{
                    $("#price_usd, #price_list_container #price_usd").show();
                }
            });
            $("#priceType").change(function () {
                var maxPerson = $("#max_person").val();
                var dif = Math.round(maxPerson/2)-1;
                var prictType = $(this).val();
                if(prictType == 1){
                    $("#price_fix").show();
                    $("#price_table_container").hide();
                    $("#price_list_container_left,#price_list_container_right").empty();
                }else{
                    $("#price_fix").hide();
                    $("#price_table_container, #price_list").show();    
                    // 
                    for(var pric=0;pric<=dif;pric++){ 
                        $("#price_list").clone().appendTo("#price_list_container_left").attr("id","price_list"+pric);
                        $("#price_list"+pric+" .col-md-1 h5").append((pric+1));
                        $("#price_list"+pric+" #price_list_field1").val((pric+1));
                        $("#price_list"+pric+" #price_list_field1").attr("name","price["+pric+"][people]");
                        $("#price_list"+pric+" #price_list_field2").attr("name","price["+pric+"][IDR]").mask('0.000.000.000', {reverse: true});
                        $("#price_list"+pric+" #price_list_field3").attr("name","price["+pric+"][USD]").mask('0.000.000.000', {reverse: true});
                    }
                    // 
                    for(var prik=(dif+1);prik<maxPerson;prik++){ 
                        $("#price_list").clone().appendTo("#price_list_container_right").attr("id","price_list"+prik);
                        $("#price_list"+prik+" .col-md-1 h5").append((prik+1));
                        $("#price_list"+prik+" #price_list_field1").val((prik+1));
                        $("#price_list"+prik+" #price_list_field1").attr("name","price["+prik+"][people]");
                        $("#price_list"+prik+" #price_list_field2").attr("name","price["+prik+"][IDR]").mask('0.000.000.000', {reverse: true});
                        $("#price_list"+prik+" #price_list_field3").attr("name","price["+prik+"][USD]").mask('0.000.000.000', {reverse: true});
                    }
                    $("#price_list").hide();
                }
            });

            // PRICE INCLUDE
            $("select[name='price_includes[]']").select2({
                tags:true
            });
        // PRICE EXCLUDE
            $("select[name='price_excludes[]']").select2({
                tags:true
            });
        // CANCELLATION 
            $("input[name='cancellation_type']").change(function () {
                var cancelType = $(this).val();
                if(cancelType == 2){
                    $("#cancel_policy").show();
                }else{
                    $("#cancel_policy").hide();
                }
            });

        });
    @if($data != null)
        $(document).on('ready', function() {
            var url_activity = [];var url_accommodation = [];var url_other = [];var url_destination = [];
            var config_activity = [];var config_accommodation = [];var config_other = [];var config_destination = [];
            
                @if(count($data->image_activity) != 0)
                @foreach($data->image_activity as $index => $img)
                    url_activity[{{$index}}] = "{{cdn($img->path.'/'.$img->filename)}}";
                    config_activity[{{$index}}] = {caption:'{{$img->filename}}', filename:"{{$img->filename}}",downloadUrl:"{{cdn($img->path.'/'.$img->filename)}}",width:'120px', key:{{$img->id}} };
                @endforeach
                @endif
                @if(count($data->image_destination) != 0)
                @foreach($data->image_destination as $index => $img)
                    url_destination[{{$index}}] = "{{cdn($img->path.'/'.$img->filename)}}";
                    config_destination[{{$index}}] = {caption:'{{$img->filename}}', filename:"{{$img->filename}}",downloadUrl:"{{cdn($img->path.'/'.$img->filename)}}",width:'120px', key:{{$img->id}} };
                @endforeach
                @endif
                @if(count($data->image_accommodation) != 0)
                @foreach($data->image_accommodation as $index => $img)
                    url_accommodation[{{$index}}] = "{{cdn($img->path.'/'.$img->filename)}}";
                    config_accommodation[{{$index}}] = {caption:'{{$img->filename}}', filename:"{{$img->filename}}",downloadUrl:"{{cdn($img->path.'/'.$img->filename)}}",width:'120px', key:{{$img->id}} };
                @endforeach
                @endif
                @if(count($data->image_other) != 0)
                @foreach($data->image_other as $index => $img)
                    url_other[{{$index}}] = "{{cdn($img->path.'/'.$img->filename)}}";
                    config_other[{{$index}}] = {caption:'{{$img->filename}}', filename:"{{$img->filename}}",downloadUrl:"{{cdn($img->path.'/'.$img->filename)}}",width:'120px', key:{{$img->id}} };
                @endforeach
                @endif
            

            $("#activity_images").fileinput({
                initialPreviewAsData: true,
                initialPreviewConfig:config_activity,
                initialPreview: url_activity,
                maxFileSize: 2000,
                showBrowse: false,
                showCaption: false,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
                uploadUrl: '/product/upload/image',
                uploadExtraData:{id:{{$data->id}},type:'activity',"_token": "{{ csrf_token() }}"},
                deleteUrl:'/product/delete/image',
                deleteExtraData:{type:'activity',"_token": "{{ csrf_token() }}"},
                maxFileCount: 10
            });
            $("#accomodation_images").fileinput({
                initialPreviewAsData: true,
                initialPreviewConfig:config_accommodation,
                initialPreview: url_accommodation,
                maxFileSize: 2000,
                showBrowse: false,
                showCaption: false,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
                uploadUrl: '/product/upload/image',
                uploadExtraData:{id:{{$data->id}},type:'accommodation',"_token": "{{ csrf_token() }}"},
                deleteUrl:'/product/delete/image',
                deleteExtraData:{type:'accommodation',"_token": "{{ csrf_token() }}"},
                maxFileCount: 10
            });
            $("#other_images").fileinput({
                initialPreviewAsData: true,
                initialPreviewConfig:config_other,
                initialPreview: url_other,
                maxFileSize: 2000,
                showBrowse: false,
                showCaption: false,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
                uploadUrl: '/product/upload/image',
                uploadExtraData:{id:{{$data->id}},type:'other',"_token": "{{ csrf_token() }}"},
                deleteUrl:'/product/delete/image',
                deleteExtraData:{type:'other',"_token": "{{ csrf_token() }}"},
                maxFileCount: 10
            });
            $("#destination_images").fileinput({
                initialPreviewAsData: true,
                initialPreviewConfig:config_destination,
                initialPreview: url_destination,
                maxFileSize: 2000,
                showBrowse: false,
                showCaption: false,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
                uploadUrl: '/product/upload/image',
                uploadExtraData:{id:{{$data->id}},type:'destination',"_token": "{{ csrf_token() }}"},
                deleteUrl:'/product/delete/image',
                deleteExtraData:{type:'destination',"_token": "{{ csrf_token() }}"},
                maxFileCount: 10
            });
            var i = {{count($data->videos)}};
            $("#add_more_video").click(function(){
                    i++;
                $(this).closest("#embed").clone().appendTo("#clone_dinamic_video").addClass("row dinamic_video"+i);
                $(".dinamic_video"+i+" .col-md-3").empty().append('<button type="button" id="delete_video" class="btn btn-danger waves-effect"><i class="material-icons">clear</i></button>');
            });
            $(document).on("click", "#delete_video", function() {
                $(this).closest("#embed").remove();
            });
            $("input[name='end_time[]']").mask('00:00');
            $("input[name='start_time[]']").mask('00:00');
            $("input[name='schedule_type']").change(function () {
                $("#schedule_body").show(200);
                $("input[name='schedule_type']").each(function(){
                    $(this).removeAttr('sel');
                });
                scheduleType = $(this).val();
                if(scheduleType == 1){
                    $(this).attr('sel',scheduleType);
                    $(".scheduleDays").show();
                    $(".scheduleHours").removeAttr("required").hide();
                    
                }else if(scheduleType == 2){
                    $(this).attr('sel',scheduleType);
                    $(".scheduleHours").show();
                    $(".scheduleDays").removeAttr("required").hide();
                    
                }else{
                    $(this).attr('sel',scheduleType);
                    $(".scheduleDays, .scheduleHours").removeAttr("required").hide();;
                }
            });
        });
    @endif
         // EDIT BUTTON
         $('.modal-header').delegate('.btn-back','click', function(e){
            $(this).text('Change Status');
            $(this).removeClass('btn-back');
            $(this).addClass('btn-change-status');
            $('.change-status').hide();
            $('.btn-save').hide();
            $('.log-status-list').show();
        });
        
        $('.modal-header').delegate('.btn-change-status','click', function(e){
            $(this).text('Back');
            $(this).removeClass('btn-change-status');
            $(this).addClass('btn-back');
            $('.change-status').show();
            $('.btn-save').show();
            $('.log-status-list').hide();
        });
        $("button#edit").click(function(){
            $(this).hide()
            $("#sample").show();
            $("#change-status").show();
            $("#submit").show();
            $("input").removeAttr("disabled");
            $("select").removeAttr("disabled");
            $("textarea").removeAttr("disabled");
            $("button#change").closest(".caption").show();
        });
        @if($data != null)
            var scheType = '{{$data->schedule_type}}';
            if(scheType == 1){
                $("#scheduleCol3,#scheduleCol4").remove();
                $("th#startHours,th#endHours").each(function(){
                    $(this).remove();
                });
                $("td#startHours,td#endHours").each(function(){
                    $(this).remove();
                });
            }else if(scheType == 2){
                $("#scheduleCol2").remove();
                $("th#endDate").each(function(){
                    $(this).remove();
                });
                $("td#endDate").each(function(){
                    $(this).remove();
                });
            }else{
                $("#scheduleCol2,#scheduleCol3,#scheduleCol4").remove();
                $("th#endDate,th#startHours,th#endHours").each(function(){
                    $(this).remove();
                });
                $("td#endDate,td#startHours,td#endHours").each(function(){
                    $(this).remove();
                });
            }
        @endif
        
    </script>

@stop