@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    @if(!empty($activity) and isset($activity))
                        <div class="panel panel-primary">
                            <div class="rows panel-heading">
                                <b>{{ $activity->subject->name }}</b>
                            </div>
                            <div class="panel-body">
                                <ul>
                                    ชื่อ : {{ $activity->user->name }}
                                    รูปแบบ : {{ $activity->formats }}
                                    อธิบาย : {{ $activity->description }} <br>
                                    สถาณะ :  @if( $activity->status  == 0)
                                                        <span class="label label-warning">ขอลาครั้งที่ {{$activity->count}}</span><br>
                                                        <span class="label label-danger">รออนุมัติการลา</span> 
                                                    @endif
                                                    @if( $activity->status  == 1)
                                                        <span class="label label-warning">ขอลาครั้งที่ {{$activity->count}}</span><br>
                                                        <span class="label label-success">อนุมัติการลา</span> 
                                                    @endif
                                                    @role('teacher')
                                                        @if($activity->status == 0)
                                                        <a href="{{url('accept')}}/{{$activitys->id}}">อนุมัติการลา</a>
                                                        @endif
                                                    @endrole  
                                                    @role('admin')
                                                        @if($activity->status == 0)
                                                        <a href="{{url('accept')}}/{{$activitys->id}}">อนุมัติการลา</a>
                                                        @endif
                                                    @endrole <br>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop