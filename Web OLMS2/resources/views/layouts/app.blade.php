<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>OLMS2</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('js/confirm.js') }}"></script>
    <!-- Latest compiled and minified CSS -->
    <link href="{{ asset('css/toggle.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('js/toggle.js') }}"></script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        OLMS2
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @role('admin')
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">บริหารจัดการ
                                <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('subject.index') }}">รายวิชาทั้งหมด</a></li>
                                    <li><a href="{{ route('user.index') }}">ผู้ใช้งานทั้งหมด</a></li> 
                                    <li><a href="{{ route('user.student') }}">นักศึกษาทั้งหมด</a></li> 
                                    <li><a href="{{ route('user.teacher') }}">อาจารย์ทั้งหมด</a></li> 
                                    <li><a href="{{ route('user.admin') }}">ผู้ดูแลทั้งหมด</a></li> 
                                </ul>
                            </li>
                            <li><a href="{{ url('status') }}">ขอลา</a></li>
                            <li><a href="{{ route('subject.create') }}">สร้างรายวิชา</a></li>
                        @endrole
                        @role('teacher')
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">บริหารจัดการ
                                <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('subject.index') }}">รายวิชาทั้งหมด</a></li>
                                    <li><a href="{{ url('ownerSubject') }}">เจ้าของรายวิชา</a></li> 
                                </ul>
                            </li>
                            <li><a href="{{ url('requests') }}">คำร้องขอลา</a></li>
                            <li><a href="{{ route('subject.create') }}">สร้างรายวิชา</a></li>
                        @endrole
                        @role('student')
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">บริหารจัดการ
                                <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('subject.index') }}">รายวิชาทั้งหมด</a></li>                                                    
                                    <li><a href="{{ route('user.teacher') }}">อาจารย์ทั้งหมด</a></li> 
                                    <li><a href="{{ route('user.admin') }}">ผู้ดูแลทั้งหมด</a></li>
                                    <li><a href="{{ url('subjectFollow') }}">รายวิชาติดตาม</a></li> 
                                </ul>
                            </li>                           
                            <li>
                                <a href="{{ url('activity') }}">สถาณะการลา</a>
                            </li>
                            <li >
                                <a id="btnshow"><span onclick="showSearch()" class="glyphicon glyphicon-search"></span></a>
                                <div class="dropdown" id="show" style="display:none;">
                                    <form action="/search" method="get" class="navbar-form">
                                    {!! csrf_field() !!}
                                        <input name="search" type="text" class="form-control "  placeholder="ค้นหารายวิชา" >
                                        <button type="submit" class="btn btn-primary" >ค้นหา <span class="glyphicon glyphicon-search"></span></button>
                                    </form>
                                </div>
                            </li>
                        @endrole
                        
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Notification 
                                    @if(Auth::user()->unreadnotifications->count())
                                        <span class="badge">{{ Auth::user()->unreadnotifications->count() }}</span>
                                    @endif
                                </a>
                                @if(Auth::user()->unreadnotifications->count())
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="{{ url('markasRead')}}">mark as all read</a>
                                            @foreach(Auth::user()->unreadnotifications as $index => $data)
                                                @if($data->type == 'App\Notifications\NewsNotifications')
                                                    <a href="{{ url('subject') }}/{{ $data->data['target'] }}">วิชา {{ $data->data['data']}} มีข้อความใหม่</a>
                                                @elseif($data->type == 'App\Notifications\InvoicePaid')
                                                    @role('teacher')
                                                    <a href="{{ url('requests') }}">คำขอลาวิชา {{ $data->data['data']}}</a>
                                                    @endrole
                                                    @role('student')
                                                    <a href="{{ url('activity') }}">คำขอลาวิชา {{ $data->data['data']}}</a>
                                                    @endrole
                                                @endif
                                            @endforeach
                                        </li>
                                    </ul>
                                 @endif
                            </li>                      
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->username }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
