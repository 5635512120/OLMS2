@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope = "row" ></th>
                                    <th>Teacher</th>
                                    <th>Subject</th>
                                    <th>Code</th>
                                </tr>
                            </thead>
                            @if(!empty($subjects) and isset($subjects))
                                @foreach($subjects as $index => $subject)                                  
                                        <tbody>
                                            <tr>
                                                <th scope = "row"> {{ $index+1 }} </th>
                                                <td> {{ $subject->owner->name }} </td>
                                                <td> <a  href="{{url('subject')}}/{{$subject->id}}">{{ $subject->name }}</a> </td>
                                                <td> {{ $subject->code }} </td>
                                            </tr>
                                        </tbody>                                  
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
                @if(!empty($subjects) and isset($subjects))
                    <div class="text-center">
                        {{ $subjects->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop