@extends ('layouts.app')
@section('head-css')
@parent
    <!-- JQuery DataTable Css -->
    <link href="{{asset('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{ asset('plugins/telformat/css/intlTelInput.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-file-input/css/fileinput.css') }}" rel="stylesheet">

    <link href="{{ asset('plugins/cropper/cropper.min.css') }}" rel="stylesheet">
@stop
@section('main-content')

    <div class="container-fluid">
        <div class="card">
            <div class="header">
                <h2>
                    <a href="{{url('master/destination/')}}"> All Place / Destination</a>
                    > 
                    {{$destination->destination_name}}
                
                </h2>
            </div>
            <div class="body">
                {{ Form::model($destination, ['route' => ['destination.update', $destination->id], 'method'=>'PUT', 'class'=>'form-horizontal','id'=>'form_advanced_validation', 'enctype'=>'multipart/form-data']) }}
                    <div class="row container">
                        <div class="col-md-12">
                        <h5 for="destination_type_id">Destination Type</h5>
                        </div>
                        <div class="col-md-6">
                            <select name="destination_type_id" class="form-control" required>
                                @foreach($destination_type as $dt)
                                <option value="{{$dt->id}}">{{$dt->name_EN}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <h5 for="destination_name">Destination Name* :</h5>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="destination_name" class="form-control" value="{{$destination->destination_name}}" required>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="col-md-3">
                            <h5>Province*:</h5>
                            <select name="province_id" id="province" class="form-control" required>
                                <option value="">--Select Province--</option>
                                @foreach($province as $p)
                                <option value="{{$p->id}}">{{$p->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <h5>City*:</h5>
                            <select name="city_id" id="city"  class="form-control" required>
                                <option value="">--Select City--</option>
                            </select>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="col-md-3">
                            <h5>District*:</h5>
                            <select name="district_id" id="district"  class="form-control" required>
                                <option value="">--Select District-</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <h5>Village*:</h5>
                            <select name="village_id" id="village"  class="form-control" required>
                                <option value="">--Select Village--</option>
                            </select>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="col-md-3">
                            <h5>Latitude* :</h5>
                            <input type="text" name="latitude" class="form-control" value="{{$destination->latitude}}" required> 
                        </div>
                        <div class="col-md-3">
                            <h5>Longitude* :</h5>
                            <input type="text" name="longitude" class="form-control" value="{{$destination->longitude}}" required>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="col-md-12">
                            <input type="hidden" name="format">	
                            <h5>Phone Number if any:</h5>
                        </div>
                        <div class="col-md-6">
                            <input type="tel" class="form-control" name="phone_number" value="{{$destination->phone_number}}"  style="width:100%;">
                        </div>
                    </div>
                    <div class="row container">
                        <div class="col-md-12">
                            <h5>Address if any:</h5>
                        </div>
                        <div class="col-md-6">
                            <textarea name="address" rows="3" class="form-control">{{$destination->address}}</textarea>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="col-md-12">
                            <h5>How do you describe activity that can be done in this destination?</h5>
                            <h5>Example: Sight-seeing, sport, shopping, etc. You can add multiple activity type.</h5>
                        </div>
                        <div class="col-md-6">
                            <select type="text" class="form-control" name="destination_activities[][activity_tag_id]" multiple="multiple" style="width: 100%" id="destination_activity" required></select>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="col-md-12">
                            <h5>Is this destination has its own open / close schedule?*</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <input type="radio" name="schedule_type" value="1" id="yes" checked>
                                <label for="yes">Yes</label>
                            </div>
                            <div class="col-md-9">
                                <input type="radio" name="schedule_type" value="0" id="no">
                                <label for="no">Open all day, 24 hours</label>
                            </div>
                        </div>
                    </div>
                    <div class="row " id="destination_schedule">
                        <div class="col-md-10">
                            <h5>Open Schedule</h5>
                            <div class="row">
                                {{ Form::hidden('destination_schedule[0][DestinationScheduleId]', '') }}
                                <input type="hidden" name="destination_schedule[0][ScheduleDay]" value="Monday">
                                <div class="col-md-2">
                                    <h5>Monday</h5>
                                </div>
                                <div class="col-md-2">
                                    <select name="destination_schedule[0][ScheduleCondition]" id="" class="form-control type">
                                        <option value="Open">Open</option>
                                        <option value="Close">Close</option>
                                    </select>
                                </div>
                                <div class="col-md-4 time" id="scheduleTimeMon">
                                    <span>Open From*:</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[0][StartHour]" required>
                                    <span>to</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[0][EndHour]" required>
                                </div>
                                <div class="col-md-2 checkbox" id="scheduleFullDay">
                                    <input type="checkbox" class="filled-in form-control" name="destination_schedule[0][FullDay]" id="destination_schedule_mon" value="FullDay">
                                    <label for="destination_schedule_mon">Open 24 Hours</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                {{ Form::hidden('destination_schedule[1][DestinationScheduleId]', '') }}
                                <input type="hidden" name="destination_schedule[1][ScheduleDay]" value="Tuesday">
                                <div class="col-md-2">
                                    <h5>Tuesday</h5>
                                </div>
                                <div class="col-md-2">
                                    <select name="destination_schedule[1][ScheduleCondition]" id="" class="form-control type">
                                        <option value="Open">Open</option>
                                        <option value="Close">Close</option>
                                    </select>
                                </div>
                                <div class="col-md-4 time" id="scheduleTimeTue">
                                    <span>Open From*:</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[1][StartHour]" required>
                                    <span>to</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[1][EndHour]" required>
                                </div>
                                <div class="col-md-2 checkbox" id="scheduleFullDay">
                                    <input type="checkbox" class="filled-in form-control" name="destination_schedule[1][FullDay]" id="destination_schedule_tue" value="FullDay">
                                    <label for="destination_schedule_tue">Open 24 Hours</label>
                                </div>
                            </div>
                            <div class="row">
                                {{ Form::hidden('destination_schedule[2][DestinationScheduleId]', '') }}
                                <input type="hidden" name="destination_schedule[2][ScheduleDay]" value="Wednesday">
                                <div class="col-md-2">
                                    <h5>Wednesday</h5>
                                </div>
                                <div class="col-md-2">
                                    <select name="destination_schedule[2][ScheduleCondition]" id="" class="form-control type">
                                        <option value="Open">Open</option>
                                        <option value="Close">Close</option>
                                    </select>
                                </div>
                                <div class="col-md-4 time"  id="scheduleTimeWed">
                                    <span>Open From*:</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[2][StartHour]" required>
                                    <span>to</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[2][EndHour]" required>
                                </div>
                                <div class="col-md-2 checkbox" id="scheduleFullDay">
                                    <input type="checkbox" class="filled-in form-control" name="destination_schedule[2][FullDay]" id="destination_schedule_wed" value="FullDay">
                                    <label for="destination_schedule_wed">Open 24 Hours</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                {{ Form::hidden('destination_schedule[3][DestinationScheduleId]', '') }}
                                <input type="hidden" name="destination_schedule[3][ScheduleDay]" value="Thursday">
                                <div class="col-md-2">
                                    <h5>Thursday</h5>
                                </div>
                                <div class="col-md-2">
                                    <select name="destination_schedule[3][ScheduleCondition]" id="" class="form-control type">
                                        <option value="Open">Open</option>
                                        <option value="Close">Close</option>
                                    </select>
                                </div>
                                <div class="col-md-4 time"  id="scheduleTimeThu">
                                    <span>Open From*:</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[3][StartHour]" required>
                                    <span>to</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[3][EndHour]" required>
                                </div>
                                <div class="col-md-2 checkbox" id="scheduleFullDay">
                                    <input type="checkbox" class="filled-in form-control" name="destination_schedule[3][FullDay]" id="destination_schedule_thu" value="FullDay">
                                    <label for="destination_schedule_thu">Open 24 Hours</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                {{ Form::hidden('destination_schedule[4][DestinationScheduleId]', '') }}
                                <input type="hidden" name="destination_schedule[4][ScheduleDay]" value="Friday">
                                <div class="col-md-2">
                                    <h5>Friday</h5>
                                </div>
                                <div class="col-md-2 ">
                                    <select name="destination_schedule[4][ScheduleCondition]" id="" class="form-control type">
                                        <option value="Open">Open</option>
                                        <option value="Close">Close</option>
                                    </select>
                                </div>
                                <div class="col-md-4 time"  id="scheduleTimeFri">
                                    <span>Open From*:</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[4][StartHour]" required>
                                    <span>to</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[4][EndHour]" required>
                                </div>
                                <div class="col-md-2 checkbox" id="scheduleFullDay">
                                    <input type="checkbox" class="filled-in form-control" name="destination_schedule[4][FullDay]" id="destination_schedule_fri" value="FullDay">
                                    <label for="destination_schedule_fri">Open 24 Hours</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                {{ Form::hidden('destination_schedule[5][DestinationScheduleId]', '') }}
                                <input type="hidden" name="destination_schedule[5][ScheduleDay]" value="Saturday">
                                <div class="col-md-2">
                                    <h5>Saturday</h5>
                                </div>
                                <div class="col-md-2">
                                    <select name="destination_schedule[5][ScheduleCondition]" id="" class="form-control type">
                                        <option value="Open">Open</option>
                                        <option value="Close">Close</option>
                                    </select>
                                </div>
                                <div class="col-md-4 time"  id="scheduleTimeSat">
                                    <span>Open From*:</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[5][StartHour]" required>
                                    <span>to</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[5][EndHour]" required>
                                </div>
                                <div class="col-md-2 checkbox" id="scheduleFullDay">
                                    <input type="checkbox" class="filled-in form-control" name="destination_schedule[5][FullDay]" id="destination_schedule_sat" value="FullDay">
                                    <label for="destination_schedule_sat">Open 24 Hours</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                {{ Form::hidden('destination_schedule[6][DestinationScheduleId]', '') }}
                                <input type="hidden" name="destination_schedule[6][ScheduleDay]" value="Sunday">
                                <div class="col-md-2">
                                    <h5>Sunday</h5>
                                </div>
                                <div class="col-md-2">
                                    <select name="destination_schedule[6][ScheduleCondition]" id="" class="form-control type">
                                        <option value="Open">Open</option>
                                        <option value="Close">Close</option>
                                    </select>
                                </div>
                                <div class="col-md-4 time" id="scheduleTimeSun">
                                    <span>Open From*:</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[6][StartHour]" required>
                                    <span>to</span>
                                    <input id="time" type="text"  class="input-time" placeholder="HH:MM" style="width:30%" name="destination_schedule[6][EndHour]" required>
                                </div>
                                <div class="col-md-2 checkbox" id="scheduleFullDay">
                                    <input type="checkbox" class="filled-in form-control" name="destination_schedule[6][FullDay]" id="destination_schedule_sun" value="FullDay">
                                    <label for="destination_schedule_sun">Open 24 Hours</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row container">
                        <div class="col-md-12">
                            <h5>Average visit time /how long do people spend time at this destination on average?*</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <select name="visit_hours" id="" class="form-control" required>
                                        <option value="">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                hours
                            </div>
                            <div class="col-md-3">
                                <select name="visit_minutes" id="" class="form-control" required>
                                    <option value="0">0</option>
                                    <option value="30">30</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                    minutes
                                </div>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="col-md-12">
                            <h5>Destination Description. How do you describe this destination?*</h5>
                        </div>
                        <div class="col-md-6">
                            <textarea name="description" id="" rows="5" class="form-control">{{$destination->description}}</textarea>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="col-md-12">
                            <h5>Main Image* :</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="dd-avatar">
                                <img src="{{cdn($destination->path.'/'.$destination->filename)}}" class="img-responsive" id="img-avtr">
                            </div>
                            <input name="image_resize" type="text" value="" hidden>
                            <a href="#" id="c_p_picture" class="btn bg-teal btn-block btn-xs waves-effect">Change Cover Image</a>
                            <input name="avatar" id="c_p_file" type='file' style="display: none" accept="image/x-png,image/gif,image/jpeg">
                        </div>
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
                    </div>
                    <div class="row form-group container">
                        <div class="col-md-6" id="file_destination_photo">
                            <h4>Destination Photo</h4>
                            <div class="file-loading">
                                <input type="file" name="destination_photo[]" id="destination_photo" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-left:10px;">
                            <h5>Traveasl tips. Give some travel tips for this destination.</h5>
                            <h5>Enter each tips in separate input field. You can add more input field.</h5>
                        </div>
                        <div id="clone_destination_tips" hidden>
                            <div class="row" id="destination_tips">
                                <div class="col-md-6" style="margin:0; padding:0;">
                                    <br>
                                    <div class="col-xs-1">
                                        <ul>
                                            <li></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-11">
                                        <select name="destination_tips[][question_id]" class="form-control">
                                            <option value="">-- Select Option --</option>
                                            @foreach($destination_tips_question as $dtq)
                                                <option value="{{$dtq->id}}">{{$dtq->question}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-11 col-md-offset-1">
                                        <textarea name="destination_tips[][answer]" id="" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 delete_tips">
                                        <br><br>
                                    <button type="button" id="deleteTips" class="btn btn-danger waves-effect"><i class="material-icons">clear</i></button>
                                </div>
                            </div>
                        </div>
                        @foreach($destination->destination_tips as $key=>$ddt)
                        <div class="row" id="destination_tips">
                            <div class="col-md-6" style="margin:0; padding:0;">
                                <br>
                                <div class="col-xs-1">
                                    <ul>
                                        <li></li>
                                    </ul>
                                </div>
                                <div class="col-md-11">
                                    <select name="destination_tips[{{$key}}][question_id]" class="form-control">
                                        @foreach($destination_tips_question as $dtq)
                                            @if($dtq->id == $ddt->pivot->question_id)
                                                <option value="{{$dtq->id}}" selected>{{$dtq->question}}</option>
                                            @else
                                            <option value="{{$dtq->id}}">{{$dtq->question}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-11 col-md-offset-1">
                                    <textarea name="destination_tips[{{$key}}][answer]" id="" class="form-control" rows="5">{{$ddt->pivot->answer}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6 delete_tips">
                                <br><br>
                                <button type="button" id="deleteTips" class="btn btn-danger waves-effect"><i class="material-icons">clear</i></button>
                            </div>
                        </div>
                    </div>
                    
                    @endforeach
                    <div class="row">
                        <div id="destination_tips_clone"></div>
                    </div>
                    <br>
                    <div class="row"  style="margin-left:10px;" >
                        <div class="col-md-6">
                            <div class=" col-md-6 pull-right">
                                <button type="button" class="btn" id="add_more_tips" value="">Add More Tips</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row"  style="margin-left:10px;" >
                        <div class="col-md-6">
                            <input type="submit" class="form-control btn bg-orange waves-effect" value="Save Destination">
                        </div>
                    </div>
                    <br><br><br><br><br>
                </form>
            </div>
        </div>  
    </div>
@endsection
@section('head-js')
@parent
<script src="{{ asset('plugins/cropper/cropper.min.js') }}"></script>   
<script src="{{asset('plugins/bootstrap-file-input/js/fileinput.js') }}"></script>
<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('plugins/telformat/js/intlTelInput.js') }}"></script>
<script src="{{ asset('plugins/mask-js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/demo.js') }}"></script>
    <script>
        $(document).ready(function(){
            $("select[name='province_id']").find("option[value='{{$destination->province_id}}']").attr('selected', 'selected')
            $("select[name='destination_type_id']").find("option[value='{{$destination->destination_type_id}}']").attr('selected', 'selected')
            $("select[name='city_id']").empty();
            $("select[name='district_id']").empty();
            $("select[name='village_id']").empty();
            $.ajax({
                method: "GET",
                url: "{{ url('/json/city') }}",
                data:{
                    province_id : {{$destination->province_id}}
                }
            }).done(function(response) {
                $.each(response.data, function (index, value) {
                    $("select[name='city_id']").append(
                        "<option value="+value.id+">"+value.name+"</option>"
                    );
                });
                $("select[name='city']").find("option[value='{{$destination->city}}']").attr('selected', 'selected');
                $.ajax({
                    method: "GET",
                    url: "{{ url('/json/district') }}",
                    data:{
                        city_id : {{$destination->city_id}}
                    }
                }).done(function(response) {
                    $.each(response.data, function (index, value) {
                        $("select[name='district_id']").append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                    $("select[name='district_id']").find("option[value='{{$destination->district}}']").attr('selected', 'selected');
                    
                    $.ajax({
                        method: "GET",
                        url: "{{ url('/json/village') }}",
                        data:{
                            district_id : {{$destination->district_id}}
                        }
                    }).done(function(response) {
                        $.each(response.data, function (index, value) {
                            $("select[name='village_id']").append(
                                "<option value="+value.id+">"+value.name+"</option>"
                            );
                        });
                        $("select[name='village_id']").find("option[value='{{$destination->village}}']").attr('selected', 'selected');
                    });
                });
            });

        });
    </script>
    <script type="text/javascript">
    @if(count($destination->destination_tips) > 0)
    var i = "{{count($destination->destination_tips)}}";
    @else
    var i = {{count($destination->destination_tips)}}+1;
    console.log(i);
    @endif
    $("#add_more_tips").click(function (){
        $("#destination_tips").clone().appendTo("#destination_tips_clone").addClass("cloneTips"+i);
        $(".cloneTips"+i+" select[name='destination_tips[][question_id]']").attr("name","destination_tips["+i+"][question_id]");
        $(".cloneTips"+i+" textarea[name='destination_tips[][answer]']").attr("name","destination_tips["+i+"][answer]").val("");
        $(".cloneTips"+i+" select").removeAttr("disabled"); 
        $(".cloneTips"+i+" textarea").removeAttr("disabled"); 
        $(".cloneTips"+i+" .delete").append('<div class="col-md-6"><button type="button" id="deleteTips" class="btn btn-danger waves-effect"><i class="material-icons">clear</i></button></div>');
        i++;
    });
    var listPlacePhoto = [];
        $(document).ready(function(){ 
            $("#clone_destination_tips").find('select').attr("disabled", "disabled").off('click');
            $("#clone_destination_tips").find('textarea').attr("disabled", "disabled").off('click');
            var phoneNumber = "{{$destination->phone_number}}";
            var codePhoneNumber = phoneNumber.split("-");
            console.log(codePhoneNumber);
            $("input[name='phone_number']").val(codePhoneNumber[0]).intlTelInput({
                separateDialCode: true,
            });
            $("input[name='format']").val(codePhoneNumber[0]);
            var pn = phoneNumber.replace(codePhoneNumber[0]+'-', '');
            $("input[name='phone_number']").val(pn);
            $(".country").click(function(){
                $("input[name='format']").val("+"+$(this).attr( "data-dial-code" ));
            });
            $(".input-time").mask('00:00');
		    $("input[name='phone_number']").mask('000-0000-0000');
		    $("input[name='destination_schedule[][StartHour]']").mask('00:00');
            $("select[name='province_id']").change(function(){
                var idProvince = $(this).val();
                $("select[name='city_id']").empty();
                $("select[name='district_id']").empty();
                $("select[name='village_id']").empty();
                $.ajax({
                    method: "GET",
                    url: "{{ url('/json/city') }}",
                    data:{
                        province_id : idProvince
                    }
                }).done(function(response) {
                    $("select[name='city_id']").append("<option value=''>--Select City--</option>" );
                    $.each(response.data, function (index, value) {
                        $("select[name='city_id']").append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
            });

            $("select[name='city_id']").change(function(){
                var idCity = $(this).val();
                $("select[name='district_id']").empty();
                $("select[name='village_id']").empty();
                $.ajax({
                    method: "GET",
                    url: "{{ url('/json/district') }}",
                    data:{
                        city_id : idCity
                    }
                }).done(function(response) {
                    $("select[name='district_id']").append("<option value=''>--Select District--</option>" );
                    $.each(response.data, function (index, value) {
                        $("select[name='district_id']").append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
            });
            $("select[name='district_id']").change(function(){
                var idDistrict = $(this).val();
                $("select[name='village_id']").empty();
                $.ajax({
                    method: "GET",
                    url: "{{ url('/json/village') }}",
                    data:{
                        district_id : idDistrict
                    }
                }).done(function(response) {
                    $("select[name='village_id']").append("<option value=''>--Select Village--</option>" );
                    $.each(response.data, function (index, value) {
                        $("select[name='village_id']").append(
                            "<option value="+value.id+">"+value.name+"</option>"
                        );
                    });
                });
            });
            var activity_tag = [];
            @foreach($activity_tag as $at)
                var data_activity_tag = [];
                data_activity_tag["id"] = "{{$at->id}}";
                data_activity_tag["text"] = "{{$at->name}}";
                @foreach($destination->destination_activities as $da)
                    @if($da->pivot->activity_tag_id == $at->id)
                        data_activity_tag["selected"] = true;
                    @endif
                @endforeach
                activity_tag.push(data_activity_tag);
            @endforeach
            $("select[name='destination_activities[][activity_tag_id]']").select2({
                placeholder: "Start type here.",
                data: activity_tag  
            });
            @if($destination->schedule_type=="1")
                $("input[name='schedule_type'][value='1']:radio").attr('checked', 'checked');
                $("#destination_schedule").show();
                $("#destination_schedule").find("input[type='text']").attr("required", "");
            @else
                $("input[name='schedule_type'][value='0']:radio").attr('checked', 'checked');
                $("#destination_schedule").hide();
                $("#destination_schedule").find("input[type='text']").removeAttr("required"); 
            @endif
            $("input[name='schedule_type']:radio").change(function () {
                var choose = $(this).val();
                if(choose=="1"){
                    $("#destination_schedule").show();
                }
                else{
                    $("#destination_schedule").hide();
                }
            });
            
            @for($i=0; $i<count($destination->destination_schedules); $i++)
                $("select[name='destination_schedule[{{$i}}][ScheduleCondition]']").find("option[value='{{$destination->destination_schedules[$i]['destination_schedule_condition']}}']").attr('selected', 'selected')
                if("{{$destination->destination_schedules[$i]['destination_schedule_condition']}}" == "Close"){
                    $("select[name='destination_schedule[{{$i}}][ScheduleCondition]']").closest(".row").find(".time").hide();
                    $("select[name='destination_schedule[{{$i}}][ScheduleCondition]']").closest(".row").find(".checkbox").hide();
                }
                else{
                    $("select[name='destination_schedule[{{$i}}][ScheduleCondition]']").closest(".row").find(".time").show();
                    $("select[name='destination_schedule[{{$i}}][ScheduleCondition]']").closest(".row").find(".checkbox").show();
                }
                if("{{$destination->destination_schedules[$i]['destination_schedule_start_hours']}}" == "00:00:00" && "{{$destination->destination_schedules[$i]['destination_schedule_end_hours']}}"=="23:59:59"){
                    $("select[name='destination_schedule[{{$i}}][ScheduleCondition]']").closest(".row").find("input.input-time").attr('disabled','disabled');
                    $("select[name='destination_schedule[{{$i}}][ScheduleCondition]']").closest(".row").find("input:checkbox").removeAttr('disabled');
                    $("select[name='destination_schedule[{{$i}}][ScheduleCondition]']").closest(".row").find("input:checkbox").attr('checked','');
                }
                $("input[name='destination_schedule[{{$i}}][DestinationScheduleId]']").val("{{$destination->destination_schedules[$i]['id']}}");
                $("input[name='destination_schedule[{{$i}}][StartHour]']").val("{{date('H:i',strtotime($destination->destination_schedules[$i]['destination_schedule_start_hours']))}}");
                $("input[name='destination_schedule[{{$i}}][EndHour]']").val("{{date('H:i',strtotime($destination->destination_schedules[$i]['destination_schedule_end_hours']))}}"); 
            @endfor
            @if($destination->visit_hours == "0")
                $("select[name='visit_hours']").removeAttr("required"); 
            @endif
            $("select[name='visit_hours']").find("option[value='{{$destination->visit_hours}}']").attr('selected', 'selected')
            $("select[name='visit_minutes']").find("option[value='{{$destination->visit_minutes}}']").attr('selected', 'selected')
            $("select.type").change(function(){
                var value = $(this).val();
                if(value=="Close"){
                    $(this).closest(".row").find("input[type='text']").removeAttr("required");
                    $(this).closest(".row").find(".time").hide();
                    $(this).closest(".row").find(".checkbox").hide();
                }
                else{
                    $(this).closest(".row").find("input[type='text']").attr("required", "");
                    $(this).closest(".row").find(".time").show();
                    $(this).closest(".row").find(".checkbox").show();
                }
            });

            $("#destination_schedule_mon").click(function(){
                if ($(this).prop("checked")) {
                    $("#scheduleTimeMon").children().attr('disabled','disabled');
                }
                else{
                    $("#scheduleTimeMon").children().attr('disabled',false);
                }
            });
            $("#destination_schedule_tue").click(function(){
                if ($(this).prop("checked")) {
                    $("#scheduleTimeTue").children().attr('disabled','disabled');
                }
                else{
                    $("#scheduleTimeTue").children().attr('disabled',false);
                }
            });
            $("#destination_schedule_wed").click(function(){
                if ($(this).prop("checked")) {
                    $("#scheduleTimeWed").children().attr('disabled','disabled');
                }
                else{
                    $("#scheduleTimeWed").children().attr('disabled',false);
                }
            });
            $("#destination_schedule_thu").click(function(){
                if ($(this).prop("checked")) {
                    $("#scheduleTimeThu").children().attr('disabled','disabled');
                }
                else{
                    $("#scheduleTimeThu").children().attr('disabled',false);
                }
            });
            $("#destination_schedule_fri").click(function(){
                if ($(this).prop("checked")) {
                    $("#scheduleTimeFri").children().attr('disabled','disabled');
                }
                else{
                    $("#scheduleTimeFri").children().attr('disabled',false);
                }
            });
            $("#destination_schedule_sat").click(function(){
                if ($(this).prop("checked")) {
                    $("#scheduleTimeSat").children().attr('disabled','disabled');
                }
                else{
                    $("#scheduleTimeSat").children().attr('disabled',false);
                }
            });
            $("#destination_schedule_sun").click(function(){
                if ($(this).prop("checked")) {
                    $("#scheduleTimeSun").children().attr('disabled','disabled');
                }
                else{
                    $("#scheduleTimeSun").children().attr('disabled',false);
                }
            });

            $("select[name='visit_hours']").change(function(){
                var val = $(this).val();
                if(val==""){
                    $("select[name='visit_hours']").removeAttr("required");                    
                    $("select[name='visit_minutes']").attr("required", "required");
                }
                else{
                    $("select[name='visit_hours']").attr("required", "required");
                    $("select[name='visit_minutes']").removeAttr("required");                    
                }
            });

            $("select[name='visit_minutes']").change(function(){
                var val = $(this).val();
                if(val=="0"){
                    $("select[name='visit_minutes']").removeAttr("required");                    
                    $("select[name='visit_hours']").attr("required", "required");
                }
                else{
                    $("select[name='visit_minutes']").attr("required", "required");
                    $("select[name='visit_hours']").removeAttr("required");                    
                }
            });
            @foreach($destination->destination_photos as $dp)
                listPlacePhoto.push("{{cdn($dp->path.'/'.$dp->filename)}}")
            @endforeach
            $("#destination_photo").fileinput({
                theme: 'fa',
                uploadUrl: "a",
                uploadAsync: false,
                overwriteInitial: false,
                maxFileCount: 5,
                maxFileSize: 5000,
                showCaption: false,
                showRemove: false,
                showCancel: false,
                showUpload: false,
                previewSettings:{
                    image: {width: "auto", height: "auto"},
                },
                initialPreview: listPlacePhoto,
                initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
                initialPreviewFileType: 'image',
                initialPreviewConfig: [
                    @foreach($destination->destination_photos as $dp)
                        {width: "120px", url: "{{url('/master/destination_photo/destroy')}}", key:"{{$dp->id}}"},    
                    @endforeach
                ],
                allowedFileTypes: ['image'],
                allowedFileExtensions: ["jpg", "png", "gif"],
                allowedPreviewTypes :['image'],
                extra: function() { 
                    $("#filePlacePhoto").find(".kv-file-upload").remove();;
                },
            })
        });
        $(document).on("click", "#deleteTips", function() {
            $(this).closest(".row").remove();
        });
    </script>
    <script>
        $(document).ready(function(){
            window.addEventListener('DOMContentLoaded', function () {
            var image = document.getElementById('crop-image');
            var cropBoxData;
            var canvasData;
            var cropper;

            $('#defaultModal').on('shown.bs.modal', function () {
                cropper = new Cropper(image, {
                    autoCropArea: 1,
                    aspectRatio: 3/4,
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
                $('#img-avtr').attr('src',originalData.toDataURL('image/jpeg'));
                $('.btn-img-close').click();
            });
        });
            $('#c_p_picture').click(function(e){
                e.preventDefault();
                $('input[name="avatar"]').click();
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
        })
    </script>
@stop