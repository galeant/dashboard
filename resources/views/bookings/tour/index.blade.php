@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@stop
@section('main-content')
<div class="block-header">
        <h2>
            Bookings List
            <small>Transaction & Bookings / Bookings</small>
        </h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        All Bookings
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        {{-- <li >
                            <a href="/product/tour-guide/create" class="btn bg-teal btn-block waves-effect">Add New Tour Guide</a>
                        </li> --}}
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable table-modif" id="data-tables">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Booking Number</th>
                                    <th>Company</th>
                                    <th>Tour Name</th>
                                    <th>Product Code</th>
                                    <th>Total Booking</th>
                                    <th>Total Price</th>
                                    <th>Total Commission</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
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
        <script type="text/javascript">
            $('#data-tables').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/bookings/tour',
                columns: [
                    {data: 'transaction_id'},
                    {data: 'booking_number'},
                    {data: 'company_name'},
                    {data: 'tour_name'},
                    {data: 'product_code'},
                    {data: 'number_of_person'},
                    {data: 'net_price'},
                    {data: 'commission'},
                    {data: 'status'}
                ]
            });
        </script>
    @stop
    