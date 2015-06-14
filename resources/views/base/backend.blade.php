@extends('base.master')

@section('styles')
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />


<link rel="stylesheet" href="{{asset('assets/css/style-responsive.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/js/gritter/css/jquery.gritter.css')}}" type="text/css" />
@yield('extra-styles')
@stop

@section('content')
<section id="container">

    <header class="header black-bg">
        <div class="sidebar-toggle-box">
            <div class="fa fa-bars tooltips" data-placement="bottom" data-original-title="Toggle Navigation"></div>
        </div>

        <a href="{{ url('/') }}" class="logo"><b>Egerton Sacco</b></a>

        <div class="nav notify-row" id="top_menu">
            <ul class="nav top-menu">                
                <li id="header_inbox_bar" class="dropdown">
                    <a class="dropdown-toggle" href="{{ action('MessageController@getIndex') }}">
                        <i class="fa fa-envelope-o"></i>
                    </a>                    
                </li>
            </ul>
        </div><!--End Notification bar -->

        <div class="top-menu">
            <ul class="nav pull-right top-menu">
                <li><a class="tooltips" title="Coming Soon" data-toggle="tooltip" data-placement="bottom" href="#" style="margin-top: 15px">Help</a></li>
                <li><a class="logout" href="{{ action('Auth\AuthController@getLogout') }}">Logout</a></li>
            </ul>
        </div>
    </header><!--header end-->

    <!--sidebar-->
    @include('includes.sidebar')

    <!--content-->
    <section id="main-content">
        <section class="wrapper">

            @include('includes.flash-messages')                    

            <h3>
                <i class="fa fa-angle-right"></i>
                @yield('content-title')
            </h3>
            <hr />
            <div class="container-fluid">
                @yield('inner-content')
            </div>
        </section>
    </section>


</section>

<footer class="text-center">
    <p>
        Egerton Sacco SMS Survey System &copy; {{ date('Y') }}
    </p>
    <p>
        For technical support contact <a href="mailto:jameskmw48@gmail.com">jameskmw48@gmail.com</a> or <a href="mailto:hashim4580@gmail.com ">hashim4580@gmail.com</a>
    </p>
</footer>
@stop

@section('scripts')
<script src="{{asset('assets/js/jquery.dcjqaccordion.2.7.js')}}"></script>
<script src="{{asset('assets/js/gritter/js/jquery.gritter.js')}}"></script>
<script src="{{asset('assets/js/jquery.scrollTo.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('assets/js/common-scripts.js')}}"></script>
<script>
(function ($) {
    //show Gritter messages
    var msgWrapper = $('#gritter-msg');
    if (msgWrapper.size()) {
        var messages = JSON.parse(msgWrapper.html());
        messages.forEach(function (msg) {
            $.gritter.add(msg);
        });

    }
}(jQuery));
</script>

@yield('extra-scripts')

@stop

