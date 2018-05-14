@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <div class="panel-body">
                    <div class="table-responsive">
                        <form method="post" >
                        {!! csrf_field() !!}
                        <table class="table table-hover">
                            
                            <!-- <button class="btn btn-primary btn-xs" type="submit" formaction="{{url('deleteall')}}" onclick="daleteAll()">Delete check <span class="glyphicon glyphicon-remove"> </span></button> &nbsp; -->
                            @role('teacher | admin')
                                <button class="btn btn-primary btn-xs" type="submit" formaction="{{url('acceptall')}}" onclick="acceptAll()">Accept check <span class="glyphicon glyphicon-ok"> </span></button> &nbsp;
                                <button class="btn btn-primary btn-xs" type="submit" formaction="{{url('ejectall')}}" onclick="ejectAll()">Eject check <span class="glyphicon glyphicon-ban-circle"> </span></button>
                            @endrole
                            <thead>
                                <tr>
                                    <th scope = "row" >#</th>
                                    <th>ชื่อ</th>
                                    <th class="col-md-1">รายวิชา</th>
                                    <th class="col-md-4">คำอธิบาย</th>
                                    <th class="col-md-1">วันที่ลา</th>
                                    <th>สถานะ</th>
                                    <th>ตัวเลือก</th>
                                </tr>
                            </thead>
                            @foreach($subject as $activity)
                                @if(!empty($activity) and isset($activity))
                                    @foreach($activity->activity as $index => $activitys)   
                                        @role('teacher')                               
                                            @if($activitys->status == 0)
                                            <tbody>
                                                <tr>
                                                    <th scope = "row"> <input type="checkbox" id="{{$index}}" name="chk[]" value="{{$activitys->id}}" ></th>
                                                    <td class="col-md-2"> {{ $activitys->user->name }}</a> </td>
                                                    <td class="col-md-1">
                                                        {{ $activitys->subject->name }} 
                                                    </td>
                                                    <td class="col-md-4"> {{ $activitys->description }} </td>
                                                    <td class="col-md-1"><span class="label label-info"> {{ $activitys->Start }} </span></td>
                                                    <td>
                                                        <span class="label label-warning">ขอลาครั้งที่ {{$activitys->count}}</span><br>
                                                        <span class="label label-warning">รออนุมัติการลา</span>
                                                    </td>
                                                    <td>
                                                        <a onclick="confirmAccept({{$activitys->id}})" class="btn btn-primary btn-xs" >อนุมัติการลา <span class="glyphicon glyphicon-ok"> </span></a><br>
                                                        <a onclick="confirmEject({{$activitys->id}})" class="btn btn-default btn-xs" >ปฏิเสธการลา <span class="glyphicon glyphicon-ban-circle"> </span></a>                                                           
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @endif
                                        @endrole
                                        @role('student')                               
                                            <tbody>
                                                <tr>
                                                    <th scope = "row"> <input type="checkbox" id="{{$index}}" name="chk[]" value="{{$activitys->id}}" > {{ $index+1}}</th>
                                                    <td class="col-md-2"> {{ $activitys->user->name }}</a> </td>
                                                    <td class="col-md-1">
                                                        {{ $activitys->subject->name }} 
                                                    </td>
                                                    <td class="col-md-4"> {{ $activitys->description }} </td>
                                                    <td class="col-md-1"><span class="label label-info"> {{ $activitys->Start }} </span></td>
                                                    <td>
                                                        @if($activitys->status == 0)
                                                            <span class="label label-warning">ขอลาครั้งที่ {{$activitys->count}}</span><br>
                                                            <span class="label label-warning">รออนุมัติการลา</span>
                                                        @elseif( $activitys->status  == 1)
                                                            <span class="label label-warning">ขอลาครั้งที่ {{$activitys->count}}</span><br>
                                                            <span class="label label-success">อนุมัติการลา</span>
                                                        @else
                                                            <span class="label label-warning">ขอลาครั้งที่ {{$activitys->count}}</span><br>
                                                            <span class="label label-danger">ไม่อนุมัติการลา</span> 
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($activitys->status == 0)
                                                            <button class="btn btn-danger btn-xs" onclick="confirmDelete({{$activitys->id}})" method="delete">ลบ <span class="glyphicon glyphicon-remove"> </span></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        @endrole
                                    @endforeach
                                @endif
                            @endforeach
                        </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop