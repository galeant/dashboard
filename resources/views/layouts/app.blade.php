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
    <!-- <link rel="icon" href="../../favicon.ico" type="image/x-icon"> -->
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
    <link href="{{asset('css/style.css')}}" rel="stylesheet">

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
		    <div class="menu" style="background-color: #676C56">
		        <ul class="list">
		            <li id="overview" class="active">
		                <a href="{{ URL('/admin') }}">
		                    <i class="material-icons">dashboard</i>
		                    <span>Overview</span>
		                </a>
		            </li>
		            <li>
		                <a href="{{ URL('/members') }}">
		                    <i class="material-icons">person</i>
		                    <span>Members</span>
		                </a>
		            </li>
		            <li {{{ (Request::is('admin/partner*') ? 'class=active' : '') }}}>
		                <a  class="menu-toggle waves-effect waves-block toggled">
		                    <i class="material-icons">people</i>
		                    <span>Partners</span>
		                </a>
		                <ul class="ml-menu" style="display: block;">
		                    <li {{{ (Request::is('admin/partner/index*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/admin/partner/index') }}" class=" waves-effect waves-block">
		                            <span>Partner List</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('admin/partner/activity*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/admin/partner/activity') }}" class=" waves-effect waves-block">
		                            <span>Registration - Activity (5)</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('admin/partner/accomodation*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/admin/partner/accomodation') }}" class=" waves-effect waves-block">
		                            <span>Registration - Accomodation (0)</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('admin/partner/carrental*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/admin/partner/carrental') }}" class=" waves-effect waves-block">
		                            <span>Registration - Car Rental (0)</span>
		                        </a>
		                    </li>

		                </ul>
		            </li>

		            <li {{{ (Request::is('product*') ? 'class=active' : '') }}}>
		                <a  class="menu-toggle waves-effect waves-block toggled">
		                    <i class="material-icons">people</i>
		                    <span>Products</span>
		                </a>
		                <ul class="ml-menu" style="display: block;">
		                    <li {{{ (Request::is('product/activity*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('product/activity') }}" class=" waves-effect waves-block">
		                            <span>Activity</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('product/tour-guide*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/product/tour-guide') }}" class=" waves-effect waves-block">
		                            <span>TourGuide</span>
		                        </a>
		                    </li>

		                </ul>
		            </li>

		            <li>
		                <a href="{{ URL('/admin/members') }}">
		                    <i class="material-icons">person</i>
		                    <span>Transaction & Bookings</span>
		                </a>
		            </li>

		            {{-- <li>
		                <a href="{{ URL('/admin/members') }}">
		                    <i class="material-icons">money</i>
		                    <span>Refunds</span>
		                </a>
		            </li>
		            <li>
		                <a href="{{ URL('/admin/members') }}">
		                    <i class="material-icons">person</i>
		                    <span>Partner Settlement</span>
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
		            <li {{{ (Request::is('master*') ? 'class=active' : '') }}}>
		                <a  class="menu-toggle waves-effect waves-block toggled">
		                    <i class="material-icons">library_books</i>
		                    <span>Master Data</span>
		                </a>
		                <ul class="ml-menu" style="display: block;">
							<li {{{ (Request::is('master/company*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/company') }}" class=" waves-effect waves-block">
		                            <span>Company</span>
		                        </a>
		                    </li>
		                	<li {{{ (Request::is('master/language*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/language') }}" class=" waves-effect waves-block">
		                            <span>Language</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('master/country*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/country') }}" class=" waves-effect waves-block">
		                            <span>Country</span>
		                        </a>
		                    </li>
							<li {{{ (Request::is('coupon') ? 'class=active' : '') }}}>
								<a href="{{ URL('coupon') }}" class=" waves-effect waves-block">
										<span>Coupon</span>
								</a>
							</li>
							<li {{{ (Request::is('master/product*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/product') }}" class=" waves-effect waves-block">
		                            <span>Tour</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('master/country*') ? 'class=active' : '') }}} {{{ (Request::is('master/province*') ? 'class=active' : '') }}} {{{ (Request::is('master/city*') ? 'class=active' : '') }}} {{{ (Request::is('master/district*') ? 'class=active' : '') }}}
		                    {{{ (Request::is('master/village*') ? 'class=active' : '') }}}>
			                    <a href="javascript:void(0);" class="menu-toggle">
	                                <span>Location</span>
	                            </a>
	                            <ul class="ml-menu">
	                            	<li {{{ (Request::is('master/country*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('master/country') }}" class=" waves-effect waves-block">
				                            <span>Country</span>
				                        </a>
				                    </li>
				                    <li {{{ (Request::is('master/province*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('master/province') }}" class=" waves-effect waves-block">
				                            <span>Province</span>
				                        </a>
				                    </li>
				                	<li {{{ (Request::is('master/city*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('/master/city') }}" class=" waves-effect waves-block">
				                            <span>City</span>
				                        </a>
				                    </li>
				                    <li {{{ (Request::is('master/district*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('/master/district') }}" class=" waves-effect waves-block">
				                            <span>District</span>
				                        </a>
				                    </li>
				                    <li {{{ (Request::is('master/village*') ? 'class=active' : '') }}}>
				                        <a href="{{ URL('master/village') }}" class=" waves-effect waves-block">
				                            <span>Village</span>
				                        </a>
				                    </li>
	                            </ul>
                            </li>

		                    <li {{{ (Request::is('master/tour-guide-service*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/tour-guide-service') }}" class=" waves-effect waves-block">
		                            <span>Tour Guide Service</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('master/destination/*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/destination') }}" class=" waves-effect waves-block">
		                            <span>Place Management</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('master/destination-type/*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('master/destination-type') }}" class=" waves-effect waves-block">
		                            <span>Place Type Management</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('admin/master/calender*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('admin/master/calender') }}" class=" waves-effect waves-block">
		                            <span>Calender Management</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('bookings/byactivityschedule*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/bookings/byactivityschedule') }}" class=" waves-effect waves-block">
		                            <span>Activity Type Management</span>
		                        </a>
		                    </li>
		                    <li {{{ (Request::is('bookings/byactivityschedule*') ? 'class=active' : '') }}}>
		                        <a href="{{ URL('/bookings/byactivityschedule') }}" class=" waves-effect waves-block">
		                            <span>Pigijo Company Setting</span>
		                        </a>
		                    </li>

		                </ul>
		            </li>
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
@show
<!-- End Js -->
</body>
</html>
