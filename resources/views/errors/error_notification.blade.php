@if( Session::has('errors') )
  <div class="alert alert-danger" role="alert" align="center">
     @foreach($errors->all() as $error) 
        <div>{{$error}}</div>
     @endforeach
  </div>
@endif
@if( Session::has('message') )
  <div class="alert alert-success" role="alert" align="center">
  {{ Session::get('message') }}
  </div>
@endif
@if( Session::has('error') )
  <div class="alert alert-danger" role="alert" align="center">
  {{ Session::get('error') }}
  </div>
@endif

@if(Session::has('problems'))
<?php $problems =  Session::get('problems'); ?>
    @if(!empty($problems['errors']))
    <div class="panel panel-danger" role="alert" align="center">
      <div class="panel-heading">Data import failed! The following errors were encountered!</div>
      <br/>
      <div class="panely-body">
      <p align="left">
      @foreach($problems['errors'] as $import_error)
        <div class="danger"><i class="btn btn-danger btn-circle fa fa-times-circle fa-2x"></i>&nbsp;&nbsp;{{ "Row ".$import_error['row']." - ".$import_error['msg']}}</div>
      @endforeach
      </p>
      </div>

      <br/>
    @endif

    @if(!empty($problems['warnings']))
    <div class="panel panel-warning" role="alert" align="center">
      <div class="panel-heading">The following warnings were encountered! You can still import the data</div>
      <br/>
      <div class="panel-body">
      <p align="left">
      @foreach($problems['warnings'] as $import_warning)
        <div class="warning"><i class="btn btn-warning btn-circle fa fa-times-circle fa-2x"></i>&nbsp;&nbsp;{{ "Row ".$import_warning['row']." - ".$import_warning['msg']}}</div>
      @endforeach
      </p>
      @if(empty($problems['errors']) && !empty($problems['warnings']))
        <a href="{{ URL::to('import-process') }}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Continue with Import Process</a>
      @endif
      </div>
      <br/>
    @endif
  </div>

@endif
