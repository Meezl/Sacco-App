@extends('base.master')

@section('title')
Login
@stop

@section('content')

<div id="page-login">
    <div class="container">
        <form action="{{ url('home') }}" class="form-login">
            <h2 class="form-login-heading">sign in now</h2>
            <div class="login-wrap">
                <input type="text" autofocus="" placeholder="User ID" class="form-control">
                <br>
                <input type="password" placeholder="Password" class="form-control">
                <label class="checkbox">
                    <span class="pull-right">
                        <a href="login.html#myModal" data-toggle="modal"> Forgot Password?</a>

                    </span>
                </label>
                <button type="submit" href="index.html" class="btn btn-theme btn-block"><i class="fa fa-lock"></i> SIGN IN</button>
                <hr>

                <div class="registration">
                    Don't have an account yet?<br>
                    <a href="#" class="">
                        Create an account
                    </a>
                </div>

            </div>

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                            <button type="button" class="btn btn-theme">Submit</button>
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
    }(jQuery));
</script>
@stop
