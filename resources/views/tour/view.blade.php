@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@stop
@section('main-content')
			<div class="block-header">
                <h2>
                    Product List
                    <small>Product Data / Activity</small>
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                All Product Activity <span class="badge">{{$data->total()}}</span>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li >
                                    <a href="{{ url('product/tour-activity/create') }}" class="btn bg-teal btn-block waves-effect">Add New Activity</a>
                                </li>

                            </ul>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-12 search-box">
                                {!!Form::open(['method' => 'GET','route' => ['tour-activity.index'],'style'=>'display:inline'])!!}
                                
                                    <div class="col-md-10 ">
                                        <div class="row clearfix">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Product Name / Code</label>
                                                    {!! Form::text('product',Request::input('product',null),['class' => 'form-control']) !!}
                                                </div>
                                            </div> 
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Company</label>
                                                    {!! Form::text('company',Request::input('company',null),['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <div class="row clearfix" id="advance-search" style="display:none">
                                                <div class="col-md-12">
                                                    <div class="col-sm-3 col-xs-6">
                                                        <div class="form-group">
                                                            <label>Province</label>
                                                            {!! Form::select('province_id',Helpers::provinces(),Request::input('province_id',0),['class' => 'form-control']) !!}
                                                        </div>
                                                    </div> 
                                                    <div class="col-sm-3 col-xs-6">
                                                        <div class="form-group">
                                                            <label>City</label>
                                                            {!! Form::select('city_id',[0 => '-Please Select-'],null,['class' => 'form-control','id' => 'city_id']) !!}
                                                        </div>
                                                    </div> 
                                                    <div class="col-sm-3 col-xs-6">
                                                        <div class="form-group">
                                                            <label>Product Type</label>
                                                            {!! Form::select('product_type',['' => 'All','open' => 'Open Trip','private' =>'Private Trip'],Request::input('product_type'),['class' => 'form-control']) !!}
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 col-xs-6">
                                                        <div class="form-group">
                                                            <label>Status Product</label>
                                                            {!! Form::select('status',[99 => 'All',0 => 'Draft',1 =>'Awaiting Moderation',2 => 'Active',3 => 'Disable'],Request::input('status',2),['class' => 'form-control']) !!}
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                                <a href="#" id="more"><i class="material-icons">keyboard_arrow_down</i><span>MORE</span></a>
                                        </div>    
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="button-search">
                                            <button type="submit" class="btn btn-success waves-effect"><i class="material-icons">search</i></button>
                                            <a href="{{url('product/tour-activity')}}" class="btn btn-warning waves-effect" on="hide"><i class="material-icons">replay</i></a>
                                        </div>
                                    </div>                                 

                                </form>
                            </div>
                        </div>
                        <div class="body table-responsive">
                            
                            <table class="table table-hover table-modif">
                                <thead>
                                    <tr>
                                        <th>@if (Request::input('sort') == 'product_code')
                                                <a href="{!! Request::input('sort_code') !!}" >
                                                    # @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                                </a>
                                            @else
                                                <a href="{!! Request::input('sort_code') !!}">
                                                    # <i class="fa fa-fw fa-sort"></i>
                                                </a>
                                            @endif
                                        </th> 
                                        <th></th>
                                        <th>
                                            @if (Request::input('sort') == 'product_name')
                                                <a href="{!! Request::input('sort_product_name') !!}" >
                                                    Product Name @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                                </a>
                                            @else
                                                <a href="{!! Request::input('sort_product_name') !!}">
                                                    Product Name <i class="fa fa-fw fa-sort"></i>
                                                </a>
                                            @endif
                                        </th>
                                        <th>Company</th>
                                        <th>Province</th>
                                        <th>City</th>
                                        <th width="70">
                                        @if (Request::input('sort') == 'min_person')
                                                <a href="{!! Request::input('sort_min_person') !!}" >
                                                    Min @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                                </a>
                                            @else
                                                <a href="{!! Request::input('sort_min_person') !!}">
                                                    Min <i class="fa fa-fw fa-sort"></i>
                                                </a>
                                            @endif
                                        </th>
                                        <th width="70">
                                            @if (Request::input('sort') == 'max_person')
                                                <a href="{!! Request::input('sort_max_person') !!}" >
                                                    Max @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                                </a>
                                            @else
                                                <a href="{!! Request::input('sort_max_person') !!}">
                                                    Max <i class="fa fa-fw fa-sort"></i>
                                                </a>
                                            @endif
                                        </th>
                                        <th>Schedule</th>
                                        <th>Status</th>
                                        <th width="110">
                                            @if (Request::input('sort','created_at') == 'created_at')
                                                <a href="{!! Request::input('sort_created') !!}" >
                                                    <b>Created At</b> @if(Request::input('order') == 'ASC') <i class="fa fa-sort-asc"></i> @else <i class="fa fa-sort-desc"></i>@endif
                                                </a>
                                            @else
                                                <a href="{!! Request::input('sort_created') !!}">
                                                    <b>Created At</b> <i class="fa fa-fw fa-sort"></i>
                                                </a>
                                            @endif
                                        </th>
                                        <th width="90">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach($data as $dt)

                                   <tr>
                                       <td>{{$dt->product_code}}</td>
                                       <td><img src="{{cdn($dt->cover_path.'/xsmall/'.$dt->cover_filename)}}" width="50" height="50"></td>
                                       <td>{{$dt->product_name}}</td>
                                       <td>{{(!empty($dt->company) ? $dt->company->company_name : '-')}}</td>
                                       <td>
                                            @if(count($dt->destinations) > 0)
                                                @foreach($dt->destinations as $dst)
                                                    <span class="badge">{{$dst->province->name}}</span>
                                                @endforeach
                                            @endif
                                       </td>
                                       <td>
                                           @if(count($dt->destinations) > 0)
                                                @foreach($dt->destinations as $dst)
                                                    <span class="badge">{{$dst->city->name}}</span>
                                                @endforeach
                                            @endif
                                       </td>
                                       <td>{{$dt->min_person}}</td>
                                       <td>{{$dt->max_person}}</td>
                                       <td>{{count($dt->schedules)}}</td>
                                       <td>
                                             @if($dt->status == 0 )
                                                 <span class="badge bg-purple">Draft</span>
                                             @elseif($dt->status == 1 )
                                                 <span class="badge bg-blue">Awaiting Moderation</span>
                                             @elseif($dt->status == 2 )
                                                 <span class="badge bg-green">Active</span>
                                             @else
                                                 <span class="badge bg-red">Disabled</span>
                                             @endif
                                       </td>
                                       <td>{{$dt->created_at}}</td>
                                       <td>
                                            <a href="/product/tour-activity/{{$dt->id}}" class="btn-xs bg-green waves-effect waves-circle waves-float"><i class="glyphicon glyphicon-eye-open"></i></a>
                                            <a href="/product/tour-activity/{{$dt->id}}/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float"><i class="glyphicon glyphicon-edit"></i></a>
                                           <!-- <a href="/product/tour-activity/{{$dt->id}}" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/product/tour-activity/{{$dt->id}}" data-id="{{$dt->id}}" id="data-{{$dt->id}}">
                                             <i class="glyphicon glyphicon-trash"></i>
                                            </a> -->
                                       </td>
                                   </tr>
                                   @endforeach
                                </tbody>
                            </table>
                            <div>{{$data->appends(['province_id' => Request::input('province_id',0),'city_id' => Request::input('city_id',0),'status' => Request::input('status',2),'product' => Request::input('product'),'company' => Request::input('company'),'product_type' => Request::input('product_type'),'sort' => Request::input('sort'),'order' => Request::input('order')])->links('vendor.pagination.default-material')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@stop
@section('head-js')
@parent
    <script type="text/javascript">
        var parseQueryString = function() {
            var str = window.location.search;
            var objURL = {};
            str.replace(
                new RegExp( "([^?=&]+)(=([^&]*))?", "g" ),
                function( $0, $1, $2, $3 ){
                    objURL[ $1 ] = $3;
                }
            );
            return objURL;
        };
        var params = parseQueryString();
        $( window ).on( "load", function() {
            var province_id = $('Select[name="province_id"]').val();
            if(province_id != 0){
                $.ajax({
                  method: "GET",
                  url: "{{ url('json/findCity') }}",
                  data: {
                    province_id: province_id
                  }
                }).done(function(response) {
                    $('#city_id option').remove();
                    $('#city_id').append(
                    "<option value=0>-Please Select-</option>"
                    );
                    $.each(response, function (index, value) {
                        if(params['city_id'] == value.id){
                            $('#city_id').append(
                            "<option value="+value.id+" selected>"+value.name+"</option>"
                            );
                        }else{
                            $('#city_id').append(
                            "<option value="+value.id+">"+value.name+"</option>"
                            );
                        }
                        
                    });
                });
            }
        });
        if(params["status"] !=2 && params['status'] != undefined){
            $('#advance-search').show();
            $('#more').html("<i class='material-icons'>keyboard_arrow_up</i><span>LESS</span>");
            $('#more').attr('on','hide');
        }
        if(params["city_id"] !=0 && params['city_id'] != undefined){
            $('#advance-search').show();
            $('#more').html("<i class='material-icons'>keyboard_arrow_up</i><span>LESS</span>");
            $('#more').attr('on','hide');
        }

        if(params["province_id"] !=0 && params['province_id'] != undefined){
            $('#advance-search').show();
            $('#more').html("<i class='material-icons'>keyboard_arrow_up</i><span>LESS</span>");
            $('#more').attr('on','hide');
        }
        if(params["product"] != "" && params['product'] != undefined){
            $('#advance-search').show();
            $('#more').html("<i class='material-icons'>keyboard_arrow_up</i><span>LESS</span>");
            $('#more').attr('on','hide');
       }
        if(params["product_type"] != "" && params['product_type'] != undefined){
            $('#advance-search').show();
            $('#more').html("<i class='material-icons'>keyboard_arrow_up</i><span>LESS</span>");
            $('#more').attr('on','hide');
        }
        if(params["company"] != "" && params['company'] != undefined){
            $('#advance-search').show();
            $('#more').html("<i class='material-icons'>keyboard_arrow_up</i><span>LESS</span>");
            $('#more').attr('on','hide');
        }
        $( document ).ready(function() {
            $('Select[name="province_id"]').change(function(){
                $.ajax({
                  method: "GET",
                  url: "{{ url('json/findCity') }}",
                  data: {
                    province_id: $(this).val()
                  }
                }).done(function(response) {
                    $('#city_id option').remove();
                    $('#city_id').append(
                    "<option value=0>-Please Select-</option>"
                    );
                    $.each(response, function (index, value) {
                        $('#city_id').append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
            });
            $( "#more" ).click(function(e) {
                e.preventDefault();
                if($(this).attr('on') == 'hide'){
                    $("#advance-search").hide();
                    $(this).attr('on','show');
                    $(this).html("<i class='material-icons'>keyboard_arrow_down</i><span>MORE</span>");
                }else{
                    $(this).attr('on','hide');
                    $("#advance-search").show();
                    $(this).html("<i class='material-icons'>keyboard_arrow_up</i><span>LESS</span>");
                }
            });
        });
        
    </script>
@stop