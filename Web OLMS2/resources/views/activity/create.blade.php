@extends('layouts.app')
@section('content')
<div class="container">
    @if(isset($warning)) 
        <div class="alert alert-danger">
	        <ul>
               {{$warning}}
	        </ul>
		</div>
    @endif 
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">ขอลา</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{url('leave')}}/{{$subject->id}}">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}

                        <div class="form-group{{ $errors->has('format') ? ' has-error' : '' }}">
                            <label for="format" class="col-md-4 control-label">รูปแบบการลา</label>

                            <div class="col-md-6">
                                <!-- <input id="format" type="text" class="form-control" name="format" value="{{ old('format') }}" required autofocus> -->
                                <select id="format" type="text" class="form-control" name="format">
                                    <option value="ลากิจ">ลากิจ</option>
                                    <option value="ลาป่วย">ลาป่วย</option>
                                </select>
                                
                                @if ($errors->has('format'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('format') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('dates') ? ' has-error' : '' }}">
                            <label for="format" class="col-md-4 control-label">วันที่</label>
                            <div class="col-md-8">  
                                <input type="date" name="Start">
                                @if ($errors->has('Start'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('Start') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">อธิบายเพิ่มเติม</label>

                            <div class="col-md-6">
                                
                                <textarea id="description" type="text" class="form-control" name="description" required></textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" href="{{url('leave')}}/{{$subject->id}}">
                                    ขอลา
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop