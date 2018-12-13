<!DOCTYPE html>
<html>
<head>
	@section('head-meta')
	<meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@show
    <title>Pigijo | Travel Planner</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @section('head-css')
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{asset('plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{asset('plugins/node-waves/waves.css')}}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{asset('plugins/animate-css/animate.css')}}" rel="stylesheet" />

	<link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="{{asset('css/style.css?v=3')}}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{asset('css/themes/all-themes.css')}}" rel="stylesheet" />
		<link href="{{asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />
    @show
</head>
<body class="{{{ isset($class_body) ? $class_body : 'theme-red' }}}">

<!-- Body -->
@section('body')
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Top Bar -->
		<nav class="navbar" style="background-color: #ffff">
		    <div class="container-fluid">
		        <div class="navbar-header">
		        	<a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                	<a href="javascript:void(0);" class="bars"></a>
		            <a class="navbar-brand" style="padding:0 0 0 25px" href="{{ URL('/') }}"><img style="height: 40px;" src="{{ asset('images/logo.png') }}"></a>
		        </div>
		        <div class="collapse navbar-collapse" id="navbar-collapse">
		            <ul class="nav navbar-nav navbar-right">
		            </ul>
		        </div>
		    </div>
		</nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
		<aside id="leftsidebar" class="sidebar" style="width:250px">
		    <!-- Menu -->
			@php
				$permission = Cache::get('permission_'.Auth::user()->remember_token);
			@endphp
		    <div class="menu" style="background-color: #676C56">
		        <ul class="list">

		            <li id="overview" {{{ (Request::is('*') ? 'class=active' : '') }}}>
		                <a href="{{ URL('/') }}">
		                    <i class="material-icons">dashboard</i>
		                    <span>Overview</span>
		                </a>
		            </li>
					@if(array_key_exists("Members",$permission))
		            <li {{{ (Request::is('members*') ? 'class=active' : '') }}}>
		                <a href="{{ URL('/members') }}">
		                    <i class="material-icons">person</i>
		                    <span>Members</span>
		                </a>
		            </li>
					@endif
					@if(
						array_key_exists("Company",$permission) ||
						array_key_exists("CompanyProductType",$permission)
					)
		            <li {{{ (Request::is('partner*') ? 'class=active' : '') }}}>
		                <a  class="menu-toggle waves-effect waves-block toggled">
		                    <i class="material-icons">people</i>
		                    <span>Partners</span>
		                </a>
		                <ul class="ml-menu" style="display: block;">
							@if(array_key_exists("Company",$permission))
		                    <li {{{ (Request::is('partner') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('partner') }}" class=" waves-effect waves-block">
		                            <span>Partner List</span>
		                        </a>
							</li>
							@endif
							@if(array_key_exists("CompanyProductType",$permission))
							<li {{{ (Request::is('partner-product-type') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('partner-product-type') }}" class=" waves-effect waves-block">
		                            <span>Partner Type List</span>
		                        </a>
		                    </li>
							@endif
							@if(array_key_exists("Company",$permission))
		                    <li {{{ (Request::is('/partner/activity*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/partner/registration/activity') }}" class=" waves-effect waves-block">
		                            <span>Registration - Activity</span>
		                        </a>
		                    </li>
							@endif
		                </ul>
		            </li>
					@endif
					@if(
						array_key_exists("Tour",$permission) ||
						array_key_exists("TourGuide",$permission)
					)
		            <li {{{ (Request::is('product*') ? 'class=active' : '') }}}>
		                <a  class="menu-toggle waves-effect waves-block toggled">
		                    <i class="material-icons">camera_enhance</i>
		                    <span>Products</span>
		                </a>
		                <ul class="ml-menu" style="display: block;">
							@if(array_key_exists("Tour",$permission))
		                    <li {{{ (Request::is('product/tour-activity*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('product/tour-activity') }}" class=" waves-effect waves-block">
		                            <span>Tour Activity</span>
		                        </a>
		                    </li>
							@endif
							@if(array_key_exists("TourGuide",$permission))
		                    <li {{{ (Request::is('product/tour-guide*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/product/tour-guide') }}" class=" waves-effect waves-block">
		                            <span>Tour Guide</span>
		                        </a>
		                    </li>
							@endif
		                </ul>
					</li>
					@endif
					@if(
						array_key_exists("Campaign",$permission) ||
						array_key_exists("CampaignProduct",$permission)
					)
					<li {{{ (Request::is('campaign*') ? 'class=active' : '') }}}>
						<a  class="menu-toggle waves-effect waves-block toggled">
							<i class="material-icons">account_balance</i>
		                    <span>Campaign</span>
		                </a>
						<ul class="ml-menu" style="display: block;">
							@if(array_key_exists("Campaign",$permission))
		                    <li {{{ (Request::is('campaign/campaign-list*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('campaign/campaign-list') }}" class=" waves-effect waves-block">
		                            <span>List</span>
		                        </a>
							</li>
							@endif
							@if(array_key_exists("CampaignProduct",$permission))
							<li {{{ (Request::is('campaign/campaign-product*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('campaign/campaign-product') }}" class=" waves-effect waves-block">
		                            <span>Product</span>
		                        </a>
		                    </li>
							@endif
		                </ul>
		            </li>
					@endif
					@if(
						array_key_exists("Transaction",$permission) ||
						array_key_exists("BookingTour",$permission) ||
						array_key_exists("BookingAccomodationUHotel",$permission) ||
						array_key_exists("BookingAccomodationTiket",$permission) ||
						array_key_exists("BookingRentCar",$permission)
					)
					<li {{{ (Request::is('bookings*') ? 'class=active' : '') }}}>
		                <a  class="menu-toggle waves-effect waves-block toggled">
		                    <i class="material-icons">money</i>
		                    <span>Transaction & Bookings</span>
		                </a>
						<ul class="ml-menu" style="display: block;">
							@if(array_key_exists("Transaction",$permission))
		                    <li {{{ (Request::is('transaction*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/transaction') }}" class=" waves-effect waves-block">
		                            <span>Transaction</span>
		                        </a>
		                    </li>
							@endif
							@if(
								array_key_exists("BookingTour",$permission) ||
								array_key_exists("BookingAccomodationUHotel",$permission) ||
								array_key_exists("BookingAccomodationTiket",$permission) ||
								array_key_exists("BookingRentCar",$permission)
							)
							<li {{{ (Request::is('bookings*') ? 'class=active' : '') }}}>
								<a  class="menu-toggle waves-effect waves-block toggled">
									<span>Bookings</span>
								</a>
								<ul class="ml-menu" style="display: block;">
									@if(array_key_exists("BookingTour",$permission))
									<li {{{ (Request::is('bookings/tour*') ? 'class=active' : '') }}}>
										<a href="{{ URL('/bookings/tour') }}" class=" waves-effect waves-block">
											<span>Tour</span>
											{{-- <span class="badge bg-orange">
												<b class="font-bold col-white" id="sidebar_count_bookings">
													999
												</b>
											</span> --}}
										</a>
									</li>
									@endif
									@if(array_key_exists("BookingAccomodationUHotel",$permission))
									<li {{{ (Request::is('bookings/accomodation-uhotel*') ? 'class=active' : '') }}}>
										<a href="{{ URL('/bookings/accomodation-uhotel') }}" class=" waves-effect waves-block">
											<span>Accomodation uHotel</span>
										</a>
									</li>
									@endif
									@if(array_key_exists("BookingAccomodationTiket",$permission))
									<li {{{ (Request::is('bookings/accomodation-tiket*') ? 'class=active' : '') }}}>
										<a href="{{ URL('/bookings/accomodation-tiket') }}" class=" waves-effect waves-block">
											<span>Accomodation Tiket</span>
										</a>
									</li>
									@endif
									@if(array_key_exists("BookingRentCar",$permission))
									<li {{{ (Request::is('bookings/rent-car*') ? 'class=active' : '') }}}>
										<a href="{{ URL('/bookings/rent-car') }}" class=" waves-effect waves-block">
											<span>Rent Car</span>
										</a>
									</li>
									@endif
									{{-- @if(array_key_exists("BookingTransport",$permission)) --}}
									<li {{{ (Request::is('bookings/transport*') ? 'class=active' : '') }}}>
										<a href="{{ URL('/bookings/transport') }}" class=" waves-effect waves-block">
											<span>Transport</span>
										</a>
									</li>
									{{-- @endif --}}
								</ul>
							</li>
							@endif
		                </ul>
		            </li>
					@endif
					@if(array_key_exists("Settlement",$permission))
					<li {{{ (Request::is('settlement*') ? 'class=active' : '') }}}>
						<a  class="menu-toggle waves-effect waves-block toggled">
							<i class="material-icons">account_balance</i>
		                    <span>Partner Settlement</span>
		                </a>
						<ul class="ml-menu" style="display: block;">
		                    <li {{{ (Request::is('settlement/all') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('settlement/all') }}" class=" waves-effect waves-block">
		                            <span>Settlement List</span>
		                        </a>
							</li>
							<li {{{ (Request::is('settlement/generate') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('settlement/generate') }}" class=" waves-effect waves-block">
		                            <span>Settlement Generate</span>
		                        </a>
		                    </li>
		                </ul>
		            </li>
					@endif
		            {{-- <li>
		                <a href="{{ URL('/admin/members') }}">
		                    <i class="material-icons">money</i>
		                    <span>Refunds</span>
		                </a>
		            </li>
		            <li>
		                <a href="{{ URL('/admin/members') }}">
		                    <i class="material-icons">person</i>
		                    <span>Promotions</span>
		                </a>
		            </li>
		            <li>
		                <a href="{{ URL('/admin/members') }}">
		                    <i class="material-icons">person</i>
		                    <span>Help Center</span>
		                </a>
		            </li>
		            <li>
		                <a href="{{ URL('/admin/members') }}">
		                    <i class="material-icons">person</i>
		                    <span>User Management</span>
		                </a>
		            </li>
		            <li>
		                <a href="{{ URL('/admin/members') }}">
		                    <i class="material-icons">person</i>
		                    <span>Reporting</span>
		                </a>
		            </li> --}}
					@if(
						array_key_exists("Language",$permission) ||
						array_key_exists("Coupon",$permission) ||
						array_key_exists("Country",$permission) ||
						array_key_exists("Province",$permission) ||
						array_key_exists("City",$permission) ||
						array_key_exists("District",$permission) ||
						array_key_exists("Village",$permission) ||
						array_key_exists("TourGuideService",$permission) ||
						array_key_exists("Destination",$permission) ||
						array_key_exists("DestinationTipsQuestion",$permission) ||
						array_key_exists("ActivityTag",$permission) ||
						array_key_exists("CompanyLevel",$permission)
					)
		            <li {{{ (Request::is('master*') ? 'class=active' : '') }}}>
		                <a  class="menu-toggle waves-effect waves-block toggled">
		                    <i class="material-icons">library_books</i>
		                    <span>Master Data</span>
		                </a>
		                <ul class="ml-menu" style="display: block;">
							@if(array_key_exists("CompanyLevel",$permission))
		                	<li {{{ (Request::is('master/partner-level*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/partner-level') }}" class=" waves-effect waves-block">
		                            <span>Partner Level</span>
		                        </a>
		                    </li>
							@endif
							@if(array_key_exists("Language",$permission))
		                	<li {{{ (Request::is('master/language*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/language') }}" class=" waves-effect waves-block">
		                            <span>Language</span>
		                        </a>
		                    </li>
							@endif
							@if(array_key_exists("Coupon",$permission))
							<li {{{ (Request::is('coupon*') ? 'class=active' : '') }}}>
								<a href="{{ URL('coupon') }}" class=" waves-effect waves-block">
										<span>Coupon</span>
								</a>
							</li>
							@endif
							@if(
								array_key_exists("Country",$permission) ||
								array_key_exists("Province",$permission) ||
								array_key_exists("City",$permission) ||
								array_key_exists("District",$permission) ||
								array_key_exists("Village",$permission) ||
								array_key_exists("Area",$permission)
							)
		                    <li {{{ (Request::is('master/country*') ? 'class=active' : '') }}} {{{ (Request::is('master/province*') ? 'class=active' : '') }}} {{{ (Request::is('master/city*') ? 'class=active' : '') }}} {{{ (Request::is('master/district*') ? 'class=active' : '') }}}
		                    {{{ (Request::is('master/village*') ? 'class=active' : '') }}}>
			                    <a href="javascript:void(0);" class="menu-toggle">
	                                <span>Location</span>
	                            </a>
	                            <ul class="ml-menu">
									@if(array_key_exists("Country",$permission))
	                            	<li {{{ (Request::is('master/country*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('master/country') }}" class=" waves-effect waves-block">
				                            <span>Country</span>
				                        </a>
				                    </li>
									@endif
									@if(array_key_exists("Province",$permission))
				                    <li {{{ (Request::is('master/province*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('master/province') }}" class=" waves-effect waves-block">
				                            <span>Province</span>
				                        </a>
				                    </li>
									@endif
									@if(array_key_exists("City",$permission))
				                	<li {{{ (Request::is('master/city*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('/master/city') }}" class=" waves-effect waves-block">
				                            <span>City</span>
				                        </a>
				                    </li>
									@endif
									@if(array_key_exists("Area",$permission))
				                	<li {{{ (Request::is('master/area*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('/master/area') }}" class=" waves-effect waves-block">
				                            <span>Area</span>
				                        </a>
				                    </li>
									@endif
									@if(array_key_exists("District",$permission))
				                    <li {{{ (Request::is('master/district*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('/master/district') }}" class=" waves-effect waves-block">
				                            <span>District</span>
				                        </a>
				                    </li>
									@endif
									@if(array_key_exists("Village",$permission))
				                    <li {{{ (Request::is('master/village*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('master/village') }}" class=" waves-effect waves-block">
				                            <span>Village</span>
				                        </a>
				                    </li>
									@endif
	                            </ul>
                            </li>
							@endif
							@if(array_key_exists("TourGuideService",$permission))
		                    <li {{{ (Request::is('master/tour-guide-service*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/tour-guide-service') }}" class=" waves-effect waves-block">
		                            <span>Tour Guide Service</span>
		                        </a>
		                    </li>
							@endif
							@if(array_key_exists("Destination",$permission))
		                    <li {{{ (Request::is('master/destination/*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/destination') }}" class=" waves-effect waves-block">
		                            <span>Destination Management</span>
		                        </a>
		                    </li>
							@endif
							@if(array_key_exists("DestinationType",$permission))
		                    <li {{{ (Request::is('master/destination-type/*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/destination-type') }}" class=" waves-effect waves-block">
		                            <span>Destination Type Management</span>
		                        </a>
							</li>
							@endif
							@if(array_key_exists("DestinationTipsQuestion",$permission))
		                    <li {{{ (Request::is('master/tips-question/*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/tips-question') }}" class=" waves-effect waves-block">
		                            <span>Tips Question Management</span>
		                        </a>
		                    </li>
							@endif
		                    {{--<li {{{ (Request::is('admin/master/calender*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('admin/master/calender') }}" class=" waves-effect waves-block">
		                            <span>Calender Management</span>
		                        </a>
		                    </li> --}}
							@if(array_key_exists("ActivityTag",$permission))
		                    <li {{{ (Request::is('master/activity-tag/*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/master/activity-tag') }}" class=" waves-effect waves-block">
		                            <span>Activity Tag Management</span>
		                        </a>
		                    </li>
							@endif
		                    {{--<li {{{ (Request::is('bookings/byactivityschedule*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/bookings/byactivityschedule') }}" class=" waves-effect waves-block">
		                            <span>Pigijo Company Setting</span>
		                        </a>
		                    </li--}}>
		                </ul>
		            </li>
					@endif
					@if(array_key_exists("Supplier",$permission))
		            <li {{{ (Request::is('supplier*') ? 'class=active' : '') }}}>
		                <a href="{{ URL('supplier') }}">
		                    <i class="material-icons">person</i>
		                    <span>Supplier</span>
		                </a>
		            </li>
					@endif
					@if(
						array_key_exists("Employee",$permission) ||
						array_key_exists("Roles",$permission) ||
						array_key_exists("Permission",$permission)
					)
					<li {{{ (Request::is('autorization*') ? 'class=active' : '') }}}>
		                <a  class="menu-toggle waves-effect waves-block toggled">
		                    <i class="material-icons">developer_mode</i>
		                    <span>Autorization</span>
		                </a>
		                <ul class="ml-menu" style="display: block;">
							@if(array_key_exists("Employee",$permission))
		                    <li {{{ (Request::is('autorization/employee*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('autorization/employee') }}" class=" waves-effect waves-block">
		                            <span>Employee</span>
		                        </a>
		                    </li>
							@endif
							@if(array_key_exists("Roles",$permission))
		                    <li {{{ (Request::is('autorization/roles*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('autorization/roles') }}" class=" waves-effect waves-block">
		                            <span>Roles</span>
		                        </a>
		                    </li>
							@endif
							@if(array_key_exists("Permission",$permission))
							<li {{{ (Request::is('autorization/permission*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('autorization/permission') }}" class=" waves-effect waves-block">
		                            <span>Permission</span>
		                        </a>
		                    </li>
							@endif
		                </ul>
		            </li>
					@endif
					@if(
						array_key_exists("Report",$permission)
					)
					<li {{{ (Request::is('report*') ? 'class=active' : '') }}}>
		                <a  class="menu-toggle waves-effect waves-block toggled">
		                    <i class="material-icons">assessment</i>
		                    <span>Reporting</span>
		                </a>
		                <ul class="ml-menu" style="display: block;">
		                    <li {{{ (Request::is('report/company*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('report/company') }}" class=" waves-effect waves-block">
		                            <span>Company</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('report/member*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('report/member?option=today') }}" class=" waves-effect waves-block">
		                            <span>Member</span>
		                        </a>
		                    </li>
							<li {{{ (Request::is('report/city*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('report/city') }}" class=" waves-effect waves-block">
		                            <span>City</span>
		                        </a>
		                    </li>
							<li {{{ (Request::is('report/tour*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('report/tour') }}" class=" waves-effect waves-block">
		                            <span>Tour</span>
		                        </a>
		                    </li>
							<li {{{ (Request::is('report/destinations*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('report/destinations?option=today') }}" class=" waves-effect waves-block">
		                            <span>Destinations</span>
		                        </a>
		                    </li>
		                </ul>
		            </li>
					@endif
		            <li>
		                <a href="{{ URL('/logout') }}">
		                    <i class="material-icons">exit_to_app</i>
		                    <span>Logout</span>
		                </a>
		            </li>
		        </ul>
		    </div>
		</aside>
		<!-- #END# Left Sidebar -->
    </section>
    <section class="content">
    	<div class="container-fluid">
	    	@section('main-content')

	        @show
        </div>
    </section>
    <!-- Js -->
@show
<!-- End Body -->
@section('head-js')
<!-- Jquery Core Js -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

<script src="{{asset('plugins/jquery-validation/jquery.validate.js')}}"></script>
<!-- Bootstrap Core Js -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.js')}}"></script>

<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script src="{{asset('plugins/momentjs/moment.js')}}"></script>
<!-- Select Plugin Js -->
<!-- <script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script> -->

<!-- Slimscroll Plugin Js -->
<script src="{{asset('plugins/jquery-slimscroll/jquery.slimscroll.js')}}"></script>

<!-- Bootstrap Notify Plugin Js -->
<script src="{{asset('plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

<!-- Waves Effect Plugin Js -->
<script src="{{asset('plugins/node-waves/waves.js')}}"></script>

<!-- Custom Js -->
<script src="{{asset('js/admin.js')}}"></script>

<!-- Demo Js -->
<script src="{{asset('js/demo.js')}}"></script>

<script src="{!! asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') !!}"></script>
<script>
	$('.ml-menu').each(function(){
        var parent = $(this).parent();
        $(this).children('li').each(function(){
            if($(this).hasClass('active'))
            {
                parent.addClass('active');
            }
        });
    });
	@if(Cache::get('permission_'.Auth::user()->remember_token) != null)
		@foreach(Cache::get('permission_'.Auth::user()->remember_token) as $in=>$p)
			var menu = "{{$in}}";
		@endforeach
	@endif
	$('.date2').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
    });
    function getFormattedDateTime(date) {
    	date = date.replace(" ","T");
    	date = new Date(date);
        var year = date.getFullYear();
        var month = (1 + date.getMonth()).toString();
        month = month.length > 1 ? month : '0' + month;
        var day = date.getDate().toString();
        day = day.length > 1 ? day : '0' + day;
        var minutes = date.getMinutes();
        minutes = minutes > 9 ? minutes : '0' + minutes;
        var hour = date.getHours();
        hour = hour > 9 ? hour : '0' + hour;
        return day + '/' + month + '/' + year + ' '+hour+':'+minutes;
    }
    function formatNumber(n) {
        var number = parseInt(n, 10).toString().split('').reverse().join('');
        var formattedNumber = '';
        for(var i = 0; i < number.length; i++){
            formattedNumber  += number[i];
            if((i + 1) % 3 === 0 && i !== (number.length - 1)){
                formattedNumber += '.';
            }
        }
        return formattedNumber.split('').reverse().join('');
    }
    function getFormattedDate(date) {
    	date = date.replace(" ","T");
    	date = new Date(date);
    	var year = date.getFullYear();
        var month = (1 + date.getMonth()).toString();
        month = month.length > 1 ? month : '0' + month;
        var day = date.getDate().toString();
        day = day.length > 1 ? day : '0' + day;
        return day + '/' + month + '/' + year
    }
    function getStatusBooking(n){
   		if(n == 1){
    		return '<span class="badge" style="background-color:#066dd6">{{__("Awaiting Payment")}}</span>';
    	}
    	else if(n == 2){
    		return '<span class="badge" style="background-color:#06d663">{{__("Payment Accepted")}}</span>';
    	}
    	else if(n == 3){
    		return '<span class="badge" style="background-color:#d60606">{{__("Cancelled")}}</span>';
    	}
    	else if(n == 4){
    		return '<span class="badge" style="background-color:#e0b000">{{__("On Progress Settlement")}}</span>';
    	}
    	else if(n == 5){
    		return '<span class="badge" style="background-color:#c73de0">{{__("Settled")}}</span>';
    	}
    }
</script>
@show
<!-- End Js -->
</body>
</html>
