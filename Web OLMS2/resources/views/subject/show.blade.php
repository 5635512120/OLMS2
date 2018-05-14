@extends('layouts.app')

@section('content')
    <div class="container">
    @if(isset($success)) 
        <div class="alert alert-success">
	        <ul>
               {{$success}}
	        </ul>
		</div>
    @endif 
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Subject</b>
                        @role('student')
                            
                            <div class="pull-right">
                            <a href="{{url('activity/create')}}/{{$subject->id}}">
                                <button  class="btn btn-primary btn-sm">
                                    ขอลา
                                </button>
                            </a>&nbsp;
                            
                                <a href="{{url('follow')}}/{{$subject->id}}">
                                    @if(isset($students))
                                        <button class="btn btn-danger btn-sm">กำลังติดตาม</button>
                                    @else
                                        <button class="btn btn-danger btn-sm">ติดตาม</button>
                                    @endif
                                </a>
                            </div>
                            
                        @endrole
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li>รายวิชา : {{$subject->name}} </li>
                            <li>รหัสวิชา : {{$subject->code}} </li>
                            <li>อาจารย์ผู้สอน : {{$subject->owner->name}} </li>
                        </ul>
                    </div>
                    
                </div>
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @role('admin')
                    <form class="form-horizontal" method="POST" action="{{url('postnews')}}/{{$subject->id}}">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="col-md-12"><br>
                                <textarea id="name" type="text" class="form-control" name="name" placeholder="ประกาศ..." required autofocus></textarea>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                        
                            <div>
                                <button type="submit" class="btn btn-primary" href="{{url('postnews')}}/{{$subject->id}}">
                                    Post
                                </button>
                            </div><br>                       
                    </form>
                @endrole 
                @role('teacher')
                <form class="form-horizontal" method="POST" action="{{url('postnews')}}/{{$subject->id}}">
                {{ csrf_field() }}
                {{ method_field('POST') }}
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-1 control-label">ประกาศ</label>
                    <div class="col-md-12"><br>
                        <textarea id="name" type="text" class="form-control" name="name" required autofocus></textarea>
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>                        
                    <div>
                        <button type="submit" class="btn btn-primary" href="{{url('postnews')}}/{{$subject->id}}">
                            Post
                        </button>
                    </div>  <br>                     
            </form>
                @endrole 
            </div>
        </div>       
    </div>
    <!-- @role('admin'||'teacher') -->
    
    <!-- @endrole -->
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                
                @if(!empty($subject) and isset($subject))
                    @foreach($subject->news as $index => $subjects)
                    <div class="panel panel-primary">
                        <div class="rows panel-heading">
                            <b>{{ $subject->name }}</b>
                            @role('admin')
                                <div class="pull-right">
                                    <a href="{{url('/deletenews')}}/{{$subjects->id}}" method="get">
                                        <span class="glyphicon glyphicon-remove" style="color:white;"></span>
                                    </a>
                                </div>
                            @endrole
                            @role('teacher')
                                <div class="pull-right">
                                    <a href="{{url('/deletenews')}}/{{$subjects->id}}" method="get">
                                        <span class="glyphicon glyphicon-remove" style="color:white;"></span>
                                    </a>
                                </div>
                            @endrole  
                        </div>
                        <div class="panel-body">
                            <ul>
                                <p style="word-wrap:break-word;">{{ $subject->owner->name }} : {{ $subjects->comment }}</p> 
                                ใช้งานครั้งล่าสุด : <span class="label label-danger">{{ $subjects->created_at->diffForHumans() }}</span> <br>
                            </ul>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@stop