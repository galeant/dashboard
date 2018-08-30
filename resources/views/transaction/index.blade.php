@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@stop
@section('main-content')
    <div class="block-header">
        <h2>
            Transaction List
            <small>Transaction</small>
        </h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        All Tour Guides
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable table-modif" id="data-tables">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Transaction Number</th>
                                    <th>User</th>
                                    {{-- <th>Total Discount</th> --}}
                                    <th>Total Price</th>
                                    <th>Total Paid</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Paid At</th>
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
                ajax: '/transaction',
                columns: [
                    {data: 'id'},
                    {data: 'transaction_number'},
                    {data: 'user'},
                    // {data: 'total_discount'},
                    {data: 'total_price'},
                    {data: 'total_price'},
                    {data: 'status'},
                    {data: 'payment_method'},
                    {data: 'paid_at'}
                ]
            });
        </script>
    @stop
    