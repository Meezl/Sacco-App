@extends('base.master')

@section('styles')
<link rel="stylesheet" href="{{asset('assets/font-awesome/css/font-awesome.css')}}" type="text/css" />
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

        <a href="" class="logo"><b>Egerton Sacco</b></a>

        <div class="nav notify-row" id="top_menu">
            <ul class="nav top-menu">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="">
                        <i class="fa fa-tasks"></i>
                        <span class="badge bg-theme">2</span>
                    </a>
                    <ul class="dropdown-menu extended tasks-bar">
                        <div class="notify-arrow notify-arrow-green"></div>
                        <li>
                            <p class="green">You Have 2 pending Tasks</p>
                        </li>
                        <li><a href="">Task 1</a></li>
                        <li><a href="">Task 2</a></li>
                        <li class="external">
                            <a href="">See All Tasks</a>
                        </li>
                    </ul>
                </li>
                <li id="header_inbox_bar" class="dropdown">
                    <a class="dropdown-toggle" href="">
                        <i class="fa fa-envelope-o"></i>
                        <span id="msg-count" class="badge bg-theme">2</span>
                    </a>                    
                </li>
            </ul>
        </div><!--End Notification bar -->

        <div class="top-menu">
            <ul class="nav pull-right top-menu">
                <li><a href="" style="margin-top: 15px">Help</a></li>
                <li><a class="logout" href="">Logout</a></li>
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

