@extends('base.master')

@section('title')
Password Reset
@stop

@section('content')

<div id="page-login">
    <div class="container">
        @include('includes.flash-messages')
        
        <form method="post" action="{{ action('Auth\AuthController@postReset') }}" class="form-login">
            {!! Form::token() !!}
            <img src="{{ asset('img/logo.png') }}" alt="Egerton Sacco" class="img-responsive" />
            <h2 class="form-login-heading">Reset Password</h2>
            <div class="login-wrap">
                <label>Email*</label>
                <input autocomplete="off" value="{{ $email or '' }}" name="email" type="email" autofocus="" placeholder="Email" class="form-control">
                {!!$errors->first('email', '<p class="small text-danger">:message</p>')!!}
                            
                <label class="checkbox">
                    <span class="pull-right">
                        <a id="forgot" href="{{ action('Auth\AuthController@getLogin') }}" data-toggle="modal">Login Here</a>

                    </span>
                </label>
                <button type="submit" class="btn btn-theme btn-block"><i class="fa fa-unlock"></i> RESET</button>                

            </div>
        </form>        
    </div>
</div>
@stop

@section('scripts')
<script src="{{ asset('assets/js/jquery.backstretch.min.js') }}"></script>
<script>
    (function($) {
        $.backstretch("{{ asset('assets/img/dark-offices.jpg') }}", {speed: 500});
    }(jQuery));
</script>
@stop
