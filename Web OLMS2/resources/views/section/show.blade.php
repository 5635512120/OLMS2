@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Section</div>
                    <div class="panel-body">
                    <ul>
                        <li>Subject Id : {{$section->subject_id}} </li>
                        <li>Section : {{$section->section}} </li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop