@extends ('layouts.app')
@section('head-css')
@parent
    <!-- Tel Input -->
    <link href="{{ asset('plugins/telformat/css/intlTelInput.css') }}" rel="stylesheet">
    <!-- Bootstrap Select2 -->
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <!-- Date range picker -->
    <link href="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/cropper/cropper.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap-file-input/css/fileinput.css') }}" rel="stylesheet">
    <style type="text/css">
        .intl-tel-input{
            width: 100%;
            top:5px;
            margin-bottom: 10px;
        }
    </style>
@stop

@section('main-content')
<div class="block-header">
    <h2>
        Detail Tour Activity
        <small>Master Data / Tour Activity</small>
    </h2>
</div>
<!-- Basic Example | Horizontal Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Add New Tour Activity</h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="/product/tour-activity" class="btn btn-waves">Back</a>
                    </li>
                </ul>
            </div>
            <div class="body">
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
                                            <a href="#" id="c_p_picture" class="btn bg-teal btn-block btn-xs waves-effect">Upload Cover Image</a>
                                            <input name="cover_img" id="c_p_file" type='file' style="display: none" accept="image/x-png,image/gif,image/jpeg">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group m-b-20">
                                                <label>Company(*)</label>
                                                <select name="company_id" class="form-control" id="company_id" required>
                                                    @if(!empty($data->company_id))
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
                                                    <div class="form-group" id="div_product_type">
                                                        <label>Product Type(*)</label>
                                                        <div class="input-group dd-group">
                                                                {!! Form::select('product_type',Helpers::productType(),null,['class' => 'form-control']) !!}
                                                            <span class="input-group-addon">
                                                                <a href="#" class="info-type" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="left" title="" data-content="Within a single commencing schedule, customers can book for their own private group. They won't be grouped with another customers." data-original-title="Private Group"><i class="material-icons">info_outline</i></a>
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
                                            <div id="infowindow-content">
                                              <img src="" width="16" height="16" id="place-icon">
                                              <span id="place-name"  class="title"></span><br>
                                              <span id="place-address"></span>
                                            </div>
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
                                        <div class="map_canvas" id="map"></div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group ">
                                            <label>Term & Condition(*)</label>
                                            {{ Form::textArea('term_condition', null, ['id'=>'tinymce','class' => 'form-control no-resize','rows'=>"4","required"=>"required"]) }}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group ">
                                            <label>Buyer Remarks</label>
                                            {{ Form::textArea('buyer_remarks', null, ['id'=>'tinymce','class' => 'form-control no-resize','rows'=>"4","required"=>"required"]) }}
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-md-4">
                                    <div class="row clearfix">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-block btn-lg btn-success waves-effect">Continue</button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        {{Form::close()}}
                        </div>
                    </section>
                    <h3>Activity Details</h3>
                    <section>
                        <div class="alert alert-danger">
                            <strong>Disabled!</strong> Please save first to next step.
                        </div>
                    </section>
                    <h3>Itinerary</h3>
                    <section>
                        <div class="alert alert-danger">
                            <strong>Disabled!</strong> Please save first to next step.
                        </div>
                    </section>
                    <h3>Pricing</h3>
                    <section>
                        <div class="alert alert-danger">
                            <strong>Disabled!</strong> Please save first to next step.
                        </div>
                    </section>
                    <h3>Images</h3>
                    <section>
                        <div class="alert alert-danger">
                            <strong>Disabled!</strong> Please save first to next step.
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Basic Example | Horizontal Layout -->
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
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
@stop
@section('head-js')
@parent
   <!-- JQuery Steps Plugin Js -->
    <script src="{{asset('plugins/jquery-steps/jquery.steps.js')}}"></script>
    <script src="{{asset('js/pages/ui/tooltips-popovers.js')}}"></script>
    <script src="{{asset('js/pages/geocomplete/jquery.geocomplete.min.js')}}"></script>
    <script src="{{ asset('plugins/cropper/cropper.min.js') }}"></script>

    <script src="{{ asset('plugins/telformat/js/intlTelInput.js') }}"></script>
    <!-- Jquery Validation Plugin Css -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script> 
    <!-- Mask js -->
    <script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script> 
    <!-- Moment Plugin Js -->
    <script src="{{ asset('plugins/momentjs/moment.js') }}"></script>
    <!-- Bootstrap date range picker -->
    <script src="{{ asset('plugins/boostrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <!-- TinyMCE -->
    <script src="{{ asset('plugins/tinymce/tinymce.js') }}"></script>
    <script type="text/javascript">
        function initMap()
        {
            var myLatlng = new google.maps.LatLng({{!empty(old('meeting_point_latitude')) ? old('meeting_point_latitude') : 0}},{{!empty(old('meeting_point_longitude')) ? old('meeting_point_longitude') : 0}});
            var map = new google.maps.Map(document.getElementById('map'), {
              center: {lat:{{!empty(old('meeting_point_latitude')) ? old('meeting_point_latitude') : 0}} , lng:{{!empty(old('meeting_point_longitude')) ? old('meeting_point_longitude') : 0}} },
              zoom: 13
            });
            var input = document.getElementById('meeting_point_address');
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);
            autocomplete.setFields(['address_components', 'geometry', 'icon', 'name']);
            var infowindow = new google.maps.InfoWindow();
            var infowindowContent = document.getElementById('infowindow-content');
            infowindow.setContent(infowindowContent);
            var marker = new google.maps.Marker({
              map: map,
              position: myLatlng
            });
            autocomplete.addListener('place_changed', function(e) {
              infowindow.close();
              marker.setVisible(false);
              var place = autocomplete.getPlace();
              if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
              }

              // If the place has a geometry, then present it on a map.
              if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
              } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
              }
              marker.setPosition(place.geometry.location);
              marker.setVisible(true);
              
              $('#lat').val(place.geometry.location.lat());
              $('#lng').val(place.geometry.location.lng());
              var address = '';
              if (place.address_components) {
                address = [
                  (place.address_components[0] && place.address_components[0].short_name || ''),
                  (place.address_components[1] && place.address_components[1].short_name || ''),
                  (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
              }
              infowindowContent.children['place-icon'].src = place.icon;
              infowindowContent.children['place-name'].textContent = place.name;
              infowindowContent.children['place-address'].textContent = address;
              infowindow.open(map, marker);
            });
        }
        $( window ).on( "load", function() {

            $("select[name='product_category']").change(function(){
                if($(this).val().toLowerCase() == "event"){
                    $("select[name='product_type']").prop("readonly", "readonly")
                    $("#div_product_type").hide()
                }
                else{
                    $("select[name='product_type']").removeProp("readonly")
                    $("#div_product_type").show()
                }
            })
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
        
        $("#PICPhone").mask('000-0000-00000');
        $("#PICFormat").val("+62");
        $("#PICPhone").val("+62").intlTelInput({
            separateDialCode: true,
        });
        $(".country").click(function(){
            $(this).closest(".valid-info").find("#PICFormat").val("+"+$(this).attr( "data-dial-code" ));
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

            window.addEventListener('DOMContentLoaded', function () {
                var image = document.getElementById('crop-image');
                var cropBoxData;
                var canvasData;
                var cropper;

                $('#defaultModal').on('shown.bs.modal', function () {
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
                $('#defaultModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                readURL(this);
            });
            var destLength = 0;
            $("#add_more_destination").click(function(){
                destLength++;
                $(".master-destinations").children().clone().appendTo(".dynamic-destinations").addClass("child-"+destLength);
                $('.child-'+destLength+' .col-province select').attr('name','place['+destLength+'][province]').attr('id',destLength+'-province').attr('data-id',destLength);
                $('.child-'+destLength+' .col-city select').attr('name','place['+destLength+'][city]').attr('id',destLength+'-city').attr('data-id',destLength);
                $('.child-'+destLength+' .col-destination select').attr('name','place['+destLength+'][destination]').attr('id',destLength+'-destination').attr('data-id',destLength);
                $('.child-'+destLength).append('<button type="button" class="btn btn-danger waves-effect btn-delete-des"><i class="material-icons">clear</i></button>').attr('data-id',destLength);
            });
            $('.dynamic-destinations').delegate('.btn-delete-des','click', function(e){
                $(this).parent().remove();
            });

            $('.dd-cli').delegate('.province-sel','change',function(e){
                var id = $(this).attr('data-id');
                console.log(id);
                $.ajax({
                  method: "GET",
                  url: "{{ url('json/findCity') }}",
                  data: {
                    id: $(this).val()
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
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
             });
            $('.dd-cli').delegate('.city-sel','change',function(e){
                var id = $(this).attr('data-id');
                $.ajax({
                  method: "GET",
                  url: "{{ url('json/destination') }}",
                  data: {
                    city_id: $(this).val()
                  }
                }).done(function(response) {
                    $.each(response.data, function (index, value) {
                        $('#'+id+'-destination').append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
             });
            $("select[name='product_type']").change(function(){
                // console.log($(this).val());
                if($(this).val() == 'private'){
                    $(this).closest("div").find("a").attr("data-original-title","Private Group");
                    $(this).closest("div").find("a").attr("data-content","Within a single commencing schedule, customers can book for their own private group. They won't be grouped with another customers.");
                }else{
                    $(this).closest("div").find("a").attr("data-original-title","Open Group");
                    $(this).closest("div").find("a").attr("data-content","Within a single commencing schedule, customers will be grouped into one group");
                }
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
            //TinyMCE
            tinymce.init({
                selector: "textarea#tinymce",
                height: 300,
                plugins: [
                    'advlist autolink lists link image charmap preview anchor textcolor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code'
                ],
                toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor',
                image_advtab: true
            });
            tinymce.suffix = ".min";
            tinyMCE.baseURL = "{{ asset('plugins/tinymce') }}";

        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('API_GOOGLE_MAPS','AIzaSyCs3DPAN9pcNR6CBFXolpNNrE7PIxpbiGA')}}&libraries=places&callback=initMap"
        async defer></script>
    <!-- <script src="{{asset('js/pages/forms/form-wizard.js')}}"></script> -->
@stop
