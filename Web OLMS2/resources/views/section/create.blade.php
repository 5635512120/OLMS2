

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create Subject</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="/section">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}

                        <div class="form-group{{ $errors->has('subject_id') ? ' has-error' : '' }}">
                            <label for="subject_id" class="col-md-4 control-label">Subject Id</label>

                            <div class="col-md-6">
                                <input id="subject_id" type="text" class="form-control" name="subject_id" required>

                                @if ($errors->has('subject_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subject_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('section') ? ' has-error' : '' }}">
                            <label for="section" class="col-md-4 control-label">Section</label>

                            <div class="col-md-6">
                                <input id="section" type="number" class="form-control" name="section" value="{{ old('section') }}" required autofocus>

                                @if ($errors->has('section'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('section') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" href="subject">
                                    Create
                                </button>

                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop