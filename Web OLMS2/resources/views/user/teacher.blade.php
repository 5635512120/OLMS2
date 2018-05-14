@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-info">
                    <strong>อาจารย์ทั้งหมด</strong>
                </div>
                @if(!empty($teachers) and isset($teachers))
                    @foreach($teachers as $index => $teacher)
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>{{ $teacher->name }}</b></div>
                            <div class="panel-body">
                                <ul>
                                    อีเมล์ : {{ $teacher->email }} <br>
                                    เข้าร่วมเมื่อ : {{ $teacher->created_at->format('d M Y') }} ({{ $teacher->created_at->diffForHumans() }}) <br>
                                    ใช้งานครั้งล่าสุด : <span class="label label-danger">{{ $teacher->updated_at->diffForHumans() }}</span> <br>
                                </ul>
                            </div>
                        </div>
                    @endforeach  
                    <div class="text-center">
                        {{ $teachers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop