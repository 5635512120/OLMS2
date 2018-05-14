@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        
        @if(!empty($subject) and isset($subject))
        @foreach($subject as $index => $subjects)
        
            @if(!empty($subjects) and isset($subjects))
                @foreach($subjects->news as $index => $sub)
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>{{$subjects->name}}</b></div>
                        <div class="panel-body">
                            <ul>
                                 <P>{{ $sub->comment }}</P> 
                                ประกาศเมื่อ : <span class="label label-danger">{{ $sub->created_at->diffForHumans() }}</span> <br>
                            </ul>
                        </div>
                    </div>
                @endforeach
             @endif
           
        @endforeach
        @endif
    </div>
</div>
</div>
@endsection
