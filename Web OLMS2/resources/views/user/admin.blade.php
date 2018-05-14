@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-info">
                    <strong>ผู้ดูแลทั้งหมด</strong>
                </div>
                @if(!empty($admins) and isset($admins))
                    @foreach($admins as $index => $admin)
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>{{ $admin->name }}</b></div>
                            <div class="panel-body">
                                <ul>
                                    อีเมล์ : {{ $admin->email }} <br>
                                    เข้าร่วมเมื่อ : {{ $admin->created_at->format('d M Y') }} ({{ $admin->created_at->diffForHumans() }}) <br>
                                    ใช้งานครั้งล่าสุด : <span class="label label-danger">{{ $admin->updated_at->diffForHumans() }}</span> <br>
                                </ul>
                            </div>
                        </div>
                    @endforeach  
                    <div class="text-center">
                        {{ $admins->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop