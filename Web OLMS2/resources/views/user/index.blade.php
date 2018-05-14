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
                                    <th>Name</th>
                                    <th>Roles</th>
                                    <th>E-mail</th>
                                </tr>
                            </thead>
                            @if(!empty($users) and isset($users))
                                @foreach($users as $index => $user)
                                    <tbody>
                                        <tr>
                                            <th scope = "row"> {{$user->id}} </th>
                                            <td> {{ $user->name }} </td>
                                            <td>
                                                @foreach($user->getRoles() as $role)
                                                        {{ $role }}                                                    
                                                @endforeach
                                            </td>
                                            <td> {{ $user->email}} </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
                @if(!empty($users) and isset($users))
                    <div class="text-center">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop