@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/jquery-qtips/jquery.qtip.min.css')}}" rel="stylesheet"/>
@stop
@section('main-content')
            <div class="block-header">
                <h2>
                    {{__('Area List')}}
                    <small>{{__('Master')}} / {{__('Location')}} / {{__('Area')}}</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                {{__('All Area')}}
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li>
                                <a href="{{ url('master/area/create') }}" class="btn bg-teal btn-block waves-effect">{{__('Add New Area')}}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable table-modif" id="data-tables">
                                    <thead>
                                        <tr>
                                            <th width="10">No.</th>
                                            <th>{{__('Area Name')}}</th>
                                            <th>{{__('Country')}}</th>
                                            <th>{{__('Province')}}</th>
                                            <th>{{__('Latitude')}}</th>
                                            <th>{{__('Longitude')}}</th>
                                            <th>{{__('Status')}}</th>
                                            <th>{{__('Radius')}}</th>
                                            <th>{{__('Created At')}}</th>
                                            <th>{{__('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="table-filter">
                                        <tr class="form-filter">
                                            <td class="text-center">--</td>
                                            <td><input class="input-filter full" type="text" id="area_name"></td>
                                            <td><select class="input-filter full" id="country">
                                                <option value="">{{__("All")}}</option>
                                            </select></td>
                                            <td><select class="input-filter full" id="province">
                                                <option value="">{{__("All")}}</option>
                                            </select></td>
                                            <td><input class="input-filter full" type="text" id="latitude"></td>
                                            <td><input class="input-filter full" type="text" id="longitude"></td>
                                            <td class="text-center"><select class="input-filter full" id="status">
                                                <option value="">{{__("All")}}</option>
                                                <option value="0">{{__("Inactive")}}</option>
                                                <option value="1">{{__("Active")}}</option>
                                            </select></td>
                                            <td><input class="input-filter full" type="text" id="radius"></td>
                                            <td><input class="input-filter full date2" type="text" id="created_at"></td>
                                            <td width="75">
                                                <div class="btn-group">
                                                    <a href="#" class="btn btn-xs bg-orange btn-filter"><i class="material-icons">search</i>
                                                    </a>
                                                    <a href="{{ route('area.index') }}" class="btn btn-xs bg-cyan btn-reset"><i class="material-icons">refresh</i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@stop
@section('head-js')
@parent
    <script src="{{asset('plugins/jquery-qtips/jquery.qtip.min.js')}}"></script>
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
    var table = $('#data-tables').DataTable({
        oLanguage: {
            sLengthMenu: "{{__('Show')}} _MENU_ {{__('entries')}} per {{__('page')}}",
            sZeroRecords: "{{__('Nothing found - sorry')}}",
            sInfo: "{{__('Showing')}} _START_ {{__('to')}} _END_ {{__('of')}} _TOTAL_ {{__('records')}}",
            sInfoEmpty: "{{__('Showing')}} 0 {{__('to')}} 0 {{__('of')}} 0 {{__('records')}}",
            sInfoFiltered: "({{__('filtered from')}} _MAX_ {{__('total records')}})",
            sSearch: "{{__('Search')}}",
            oPaginate: {
                sFirst     : "{{__('First')}}",
                sLast      : "{{__('Last')}}",
                sNext      : "{{__('Next')}}",
                sPrevious  : "{{__('Previous')}}"
            }
        },
        processing: true,
        serverSide: true,
        ajax: {
                "url": "/master/area",
                "type": "GET",
                "data": function(d){
                        d.area_name = $('#area_name').val(),
                        d.country_id = $('#country').val(),
                        d.province_id = $('#province').val(),
                        d.latitude = $('#latitude').val(),
                        d.longitude = $('#longitude').val(),
                        d.status = $('#status').val(),
                        d.radius = $('#radius').val(),
                        d.created_at = $('#created_at').val()
                }
        },
        "order": [[ 7, "desc" ]],
        columns: [
            {
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                } ,
                "name": "idx", "sortable": false, "searchable" : false
            },
            {
                "data": "area_name", "name": "area_name", "sortable": true, "searchable" : true
            },
            {

                "data":"country.name","name": "country", "sortable": true, "searchable" : true
            },
            {

                "data":"province.name","name": "province.name", "sortable": true, "searchable" : true
            },
            {

                "data":"latitude","name": "latitude", "sortable": true, "searchable" : true
            },
            {

                "data":"longitude","name": "longitude", "sortable": true, "searchable" : true
            },
            {
                "render": function(data, type , row ,meta){
                     if(row.status == 1 ){
                        return '<span class="badge bg-green">{{__("Active")}}</span>';
                     }
                     else{
                        return '<span class="badge bg-red">{{__("Disabled")}}</span>';
                     }
                },
                "data":"status","name": "status", "sortable": false, "searchable" : false
            },
            {

                "data":"radius","name": "radius", "sortable": true, "searchable" : true
            },
            {
                "render": function(data, type , row ,meta){
                        if(row.created_at != null) {
                            return getFormattedDateTime(row.created_at);
                        }
                        else{
                            return '-';
                        }
                }, "name": "created_at", "sortable": true, "searchable" : true
            },               {
                "render": function (data, type, row, meta) {
                        html = '';
                        html = html+'<a href="/master/area/'+row.id+'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float"><i class="glyphicon glyphicon-edit"></i></a>';
                        html = html + '<a href="/master/area/'+row.id+'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/product/tour-activity/'+row.id+'" data-id="'+row.id+'" id="data-'+row.id+'"><i class="glyphicon glyphicon-trash"></i></a>'
                        return html;
                },
                "name": "action", "sortable": false, "searchable" : false
            }
        ],
        createdRow: function(row, data, dataIndex){
        },
        "columnDefs": [

        ],
        dom: "<'row clearfix'<'col-md-6 col-xs-12'l><'col-md-6 col-xs-12'<'pull-right' fB>>r>"+"t"+"<'row'<'col-md-6'i><'col-md-6'p>>",
        "filter": false,
        buttons: [

        ]
    });

    $(document.body).on('keyup', '#area_name,#latitude,#longitude,#radius', function(e){
        if (e.keyCode == 13) {
            table.draw();
        }
    });
    $(document.body).on('change', '#country,#province,#status,#created_at', function(e){
        table.draw();
    });
    $(document.body).on('click', '.btn-filter', function(){
        table.draw();
    });
    $(document.body).on('click', '.btn-reset', function(){
        window.location.reload();
    });
    </script>
@stop
