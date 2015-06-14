@extends('base.master')

@section('title')
Login
@stop

@section('content')

<div id="page-login">
    <div class="container">
        @include('includes.flash-messages')
        <form method="post" action="{{ url('auth/login') }}" class="form-login">
            {!! Form::token() !!}
            <img src="{{ asset('img/logo.png') }}" alt="Egerton Sacco" class="img-responsive" />
            <h2 class="form-login-heading">sign in now</h2>
            <div class="login-wrap">
                <input autocomplete="off" value="{{ $email or '' }}" name="email" type="email" autofocus="" placeholder="User ID" class="form-control">
                {!!$errors->first('email', '<p class="small text-danger">:message</p>')!!}
                <br>
                <input name="password" type="password" placeholder="Password" class="form-control">
                {!!$errors->first('password', '<p class="small text-danger">:message</p>')!!}
                <br />
                <label><input type="checkbox" name="remember" /> Remember me</label>
                <label class="checkbox">
                    <span class="pull-right">
                        <a id="forgot" href="{{ action('Auth\AuthController@getReset') }}" data-toggle="modal"> Forgot Password?</a>

                    </span>
                </label>
                <button type="submit" class="btn btn-theme btn-block"><i class="fa fa-lock"></i> SIGN IN</button>                

            </div>
        </form>
        
        <form method="post" action="{{ action('Auth\AuthController@getReset') }}">
             <!-- Modal -->
            <div class="modal fade" id="forgotPass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Forgot Password ?</h4>
                        </div>
                        <div class="modal-body">
                            <p>Enter your e-mail address below to reset your password.</p>
                            <input type="text" class="form-control placeholder-no-fix" autocomplete="off" placeholder="Email" name="email">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-theme">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal -->
        </form>
    </div>
</div>
@stop

@section('scripts')
<script src="{{ asset('assets/js/jquery.backstretch.min.js') }}"></script>
<script>
    (function($) {
        $.backstretch("{{ asset('assets/img/dark-offices.jpg') }}", {speed: 500});
        $('#forgot').attr('href', '#forgotPass');
    }(jQuery));
</script>
@stop
