@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <style>
    th.note,td.note {
        width: 10px; 
        overflow: hidden;
        text-overflow: ellipsis; 
        border: 1px solid #000000;
    }
    </style>
@stop
@section('main-content')
			<div class="block-header">
                <h2>
                    Settlment group List
                    <small>Admin Data / Settlement</small>
                </h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                All Settlement Group
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-modif table-bordered table-striped table-hover dataTable js-exportable" id="data-tables">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Total Commission</th>
                                            <th>Total Paid</th>
                                            <th class="note">Note</th>
                                            <th>Paid At</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($data) > 0)
                                        @foreach($data as $set)
                                        <tr>
                                            <td>{{$set->id}}</td>
                                            <td>{{$set->total_commission}}</td>
                                            <td>{{$set->total_paid}}</td>
                                            <td class="note" style="width:20px">
                                                <button class="btn btn-primary" data-id="{{$set->id}}" notes="{{$set->note}}" data-toggle="modal" data-target="#myModal">
                                                    See Notes
                                                </button>
                                            </td>
                                            <td>{{$set->paid_at}}</td>
                                            <td>
                                                @if($set->status == 1)
                                                    <span class="label bg-blue-grey">In Progress</span>
                                                @else
                                                    <span class="label bg-green">Paid</span>
                                                @endif
                                            </td>
                                            <td>{{$set->created_at}}</td>
                                            <td>
                                                <a href="{{ url('settlement/detail/'.$set->id)}}" class="btn-xs bg-green  waves-effect waves-circle waves-float"><i class="glyphicon glyphicon-eye-open"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form id="notesForm" method="POST" action="{{ url('settlement/update-notes') }}">
                                    @csrf
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value=""/>
                                                Notes : <textarea rows="5" class="form-control no-resize" name="notes"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-link waves-effect">SAVE CHANGES</button>
                                                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                            </div>
                                        </div>
                                    </form>
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
   <script>
    $(document).ready(function(){
        $('#data-tables').DataTable();
        $('#myModal').on('show.bs.modal', function (e) {
            // get information to update quickly to modal view as loading begins
            var opener=e.relatedTarget;//this holds the element who called the modal

            //we get details from attributes
            var notes=$(opener).attr('notes');
            var id=$(opener).attr('data-id');

            //set what we got to our form
            $('#notesForm').find('[name="id"]').val(id);
            $('#notesForm').find('[name="notes"]').val(notes);

        });
    })
    
   </script>
@stop
