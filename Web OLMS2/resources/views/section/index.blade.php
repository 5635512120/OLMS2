@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Section</div>
                    <div class="panel-body">
                   @if(!empty($section) and isset($section))
                        @foreach($section as $index => $sections)
                            <ul>
                                <li>Section : {{ $sections->section }} </li>
                            </ul>
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop