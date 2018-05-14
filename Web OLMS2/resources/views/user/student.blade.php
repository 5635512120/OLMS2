@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-info">
                    <strong>นักศึกษาทั้งหมด</strong>
                </div>
                @if(!empty($students) and isset($students))
                    @foreach($students as $index => $student)
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>{{ $student->name }}</b></div>
                            <div class="panel-body">
                                <ul>
                                    อีเมล์ : {{ $student->email }} <br>
                                    เข้าร่วมเมื่อ : {{ $student->created_at->format('d M Y') }} ({{ $student->created_at->diffForHumans() }}) <br>
                                    ใช้งานครั้งล่าสุด : <span class="label label-danger">{{ $student->updated_at->diffForHumans() }}</span> <br>
                                </ul>
                            </div>
                        </div>
                    @endforeach  
                    <div class="text-center">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop