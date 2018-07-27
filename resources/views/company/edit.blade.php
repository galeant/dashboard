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
    </style>
@stop
@section('main-content')
    <div class="block-header">
        <h2>
            Detail Company
            <small>Master Data / Company</small>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        All Company
                    </h2>
                </div>
                <div class="body">
                    <div class="row">
                        <h4>Personal Information</h4>
                        <div class="col-md-5" style="margin-top:0px;">
                            <div class="valid-info">
                                <h5>Full Name* :</h5>
                                <input type="text" class="form-control" value="{{$company->full_name}}" name="full_name">
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-top:0px;">
                            <div class="valid-info">
                                <h5>Email:</h5>
                                <input type="text" class="form-control" value="{{$company->email}}" name="email">
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
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h4>Company / Business Information</h4>
                        <div class="col-md-10 mg-top-10px" style="margin-top:0px;">
                            <div class="valid-info">
                                <h5>Company / Business Name*:</h5>
                                <input type="text" class="form-control" value="{{$company->company_name}}" name="company_name">
                            </div>
                        </div>
                        <div class="row" style="margin:0px">
                            <div class="col-md-5" style="margin-top:10px;">
                                <div class="valid-info">
                                    <h5>Company Phone Number*:</h5>
                                    <div class="row" style="margin: 0px">
                                        <div class="col-md-9" style="margin: 0px;padding: 0px;width:100%">
                                            <input type="hidden" class="form-control" name="format_company" id="telformat">	
                                            <input type="tel" class="form-control" name="company_phone" required>	
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5" style="margin-top:10px;">
                                <div class="valid-info">
                                    <h5>Company Email Address:</h5>
                                    <input type="email" class="form-control" value="{{$company->company_email}}" name="company_email">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-top:10px">
                            <div class="valid-info">
                                <h5>Province*:</h5>
                                <select class="form-control" name="company_province" required>
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
                                <select class="form-control" name="company_city" required>
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
                                <select class="form-control" name="bank_name">
                                    @foreach(Helpers::bankName() as $index => $value)
                                    <option value="{{$index}}" @if($company->bank_account_name === $index') selected @endif>{{$value}}</option>
                                    @endforeach
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
                                <select class="form-control" name="bank_account_holder_title" required>
                                 @foreach(Helpers::salutation() as $index => $value)
                                    <option value={{$index}} @if($index == $company->bank_account_title) selected @endif>{{$value}}</option>
                                 @endforeach
                                </select>
                            </div>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <div class="valid-info">
                                    <h5>Account Holder Name*:</h5>
                                    <input type="text" class="form-control" name="bank_account_holder_name" pattern="^[A-Za-z -]+$" value="{{$company->bank_account_name}}" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10" style="margin-top: 10px;">
                            <p>Bank Account Proof Upload:</p>
                            <ul>
                                <li>Provide your proof of your bank account number by uploading the scan / picture of the first page of your saving book or your e-banking</li>
                                <li>Bank account holder name must be the same with the company's owner name</li>
                            </ul>
                            <div class="col-md-12 valid-info" id="input">
                                <input id="bankPic" type="file" name="bank_pic">	
                            </div>
                            <div class="col-md-6" id="value">
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h4>Upload Document</h4>
                        <div class="row valid-info" style="padding-left:15px; padding-right:15px;">
                            <div class="col-md-12">
                                <p>What is your business ownership type?</p>
                            </div>
                            <div class="col-md-3">
                                <input name="onwershipType" type="radio" id="1o" class="radio-col-deep-orange" value="Company"/>
                                <label for="1o">Corporate</label>
                            </div>
                            <div class="col-md-3">
                                <input name="onwershipType" type="radio" id="2o" class="radio-col-deep-orange" value="Personal"/>
                                <label for="2o">Personal</label>
                            </div>
                        </div>
                        <h5 style="padding-left:15px; padding-right:15px;">Please upload softcopy of documents listed below or <b>you can skip and provide it later</b>.</h5>
                        <h5 style="padding-left:15px; padding-right:15px;">Accepted document format: PDF/JPG/JPEG/PNG. Maximum size is 5 MB per file</h5>
                        <div class="col-md-12" id="akta" style="margin-top: 10px;display: none; margin-left:15px; margin-right:15px;" >
                            <div class="row">
                                <div class="col-md-4">
                                    <li>Company Article of Association</li>
                                    <p>Akta Pendirian Usaha</p>		
                                </div>
                                <div class="col-md-6" id="input">
                                    <input id="aktaPic" type="file" name="akta_pic" />		
                                </div>
                                <div class="col-md-6" id="value">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="siup" style="display: none;margin:10px 15px 0px 15px;">
                            <div class="row">
                                <div class="col-md-4">
                                    <li>SIUP/TDP</li>
                                    <p>Surat Izin Usaha Perdagangan/Tanda Daftar Perusahaan</p>
                                </div>
                                <div class="col-md-6" id="input">
                                    <input id="SIUPPic" type="file" name="siup_pic" />
                                </div>
                                <div class="col-md-6" id="value">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="npwp" style="margin:10px 15px 0px 15px;">
                            <div class="row">
                                <div class="col-md-4">
                                    <li id="npwp">Tax Number</li>
                                    <p id="npwp">NPWP</p>
                                </div>
                                <div class="col-md-6" id="input">
                                    <input id="NPWPPic" type="file" name="npwp_pic" />
                                </div>
                                <div class="col-md-6" id="value">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="ktp" style="margin:10px 15px 0px 15px;">
                            <div class="row">
                                <div class="col-md-4">
                                    <li id="ktp">Identity Card</li>
                                    <p id="ktp">KTP</p>
                                </div>
                                <div class="col-md-6" id="input">
                                    <input id="KTPPic" type="file" name="ktp_pic">
                                </div>
                                <div class="col-md-6" id="value">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="evi" style="display: none; margin:10px 15px 0px 15px;">
                            <div class="row">
                                <div class="col-md-4">
                                    <li>Evidence you have a product</li>
                                    <p>Contoh product/info product</p>
                                </div>
                                <div class="col-md-6" id="input">
                                    <input id="eviPic" type="file" name="evi_pic" >
                                </div>
                                <div class="col-md-6" id="value">

                                </div>
                            </div>
                        </div>
                    </div>
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
    <!-- <script src="{{asset('js/pages/tables/jquery-datatable.js')}}"></script> -->

    <!-- Bootstrap File-Input-Js -->
    <script src="{{ asset('plugins/bootstrap-file-input/js/fileinput.js') }}" type="text/javascript"></script>
    <!-- Tel format -->
    <script src="{{ asset('plugins/telformat/js/intlTelInput.js') }}"></script>
    <!-- Mask js -->
	<script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script>
    <!-- Jquery Validation Plugin Css -->
	<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <script>
        $(document).ready(function () {
        // DISABLE ALL INPUT, SELECT, TEXTAREA
            $("input").attr("disabled","disabled");
            $("select").attr("disabled","disabled");
            $("textarea").attr("disabled","disabled");
        // BOOKING SYSTEM CHOICE
            $("input[name='bookingSystem']").change(function(){
                var val = $(this).val();
                if(val == 1){
                    $("#booking_system_name").show();
                }else{
                    $("input[name='bookingSystemName']:text").val(null);
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
            $("input[name='onwershipType']").change(function(){
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
            $("select[name='company_province']").change(function(){
                var idProvince = $(this).val();
                $("select[name='company_city']").empty();
                $.ajax({
                    method: "GET",
                    url: "{{ url('cities') }}",
                    data: { id: idProvince  }
                }).done(function(response) {
                    $.each(response, function (index, value) {
                        $("select[name='company_city']").append(
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
                var dbphone = "{{$company->phone}}";
                var dbformat = dbphone.split("-");
                var dbCompanyPhone = "{{$company->company_phone}}";
                var dbCompanyformat = dbCompanyPhone.split("-");
            // PROVINCE
                var province = '{{$company->province_id}}';
                var city = '{{$company->city_id}}';
                $("select[name='company_province']").find("option[value='"+province+"']").attr("selected","selected");
                $.ajax({
                    method: "GET",
                    url: "{{ url('cities') }}",
                    data: { id: province  }
                }).done(function(response) {
                    $.each(response, function (index, value) {
                        $("select[name='company_city']").append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        ).find("option[value='"+city+"']").attr("selected","selected");
                    });
                });
            // ROLE
                var dbRole = '{{$company->role}}'
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
                $("select[name='bank_account_holder_title']").find("option:contains('{{$company->bank_account_title}}')").attr("selected","selected");
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
			
			// COMPANY
                $("input[name='format_company']").val(dbCompanyformat[0]);
                $("input[name='company_phone']").val(dbCompanyformat[1]+'-'+dbCompanyformat[2]+'-'+dbCompanyformat[3]).intlTelInput({
                    separateDialCode: true,
                });

                $(".country").click(function(){
                    $(this).closest(".valid-info").find("#telformat").val("+"+$(this).attr("data-dial-code"));
                });
        });
    </script>

@stop