@extends('base.backend')

@section('title')
Register New User
@stop

@section('content-title')
Register New Admin
@stop

@section('inner-content')
<form method="post" action="{{ action('UserController@postRegister') }}">
    {!! Form::token() !!}
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>First Name*</label>
                <input value="{{ $user->first_name }}" type="text" name="first_name" class="form-control" />
                {!!$errors->first('first_name', '<p class="small text-danger">:message</p>')!!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Last Name*</label>
                <input value="{{ $user->last_name }}" type="text" name="last_name" class="form-control" />
                {!!$errors->first('last_name', '<p class="small text-danger">:message</p>')!!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Email*</label>
                <input value="{{ $user->email }}" type="email" name="email" class="form-control" />
                {!!$errors->first('email', '<p class="small text-danger">:message</p>')!!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Phone Number</label>
                <input value="{{ $user->phone }}" type="text" name="phone_number" class="form-control" />
                <p class="help-block small">Phone Number should be a valid Kenyan numbers in the format. 0702123456</p>
                {!!$errors->first('phone_number', '<p class="small text-danger">:message</p>')!!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Password*</label>
                <input type="password" name="password" class="form-control" />
                {!!$errors->first('password', '<p class="small text-danger">:message</p>')!!}
            </div>

        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" />                
                <p class="help-block small">At least 6 characters in length</p>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success pull-right">Submit</button>
    <div class="clearfix"></div>

</form>
@stop