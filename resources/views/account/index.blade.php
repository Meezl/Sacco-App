@extends('base.backend')

@section('title')
Account Settings
@stop

@section('content-title')
My Account Settings
@stop

@section('inner-content')
<div class="row">
    <div class="col-sm-6">
        <h2>Account Information</h2>
        <form class="form" method="post" action="{{ action('AccountController@postIndex') }}">
            {!! Form::token() !!}
            <div class="form-group">
                <label>First Name*</label>
                <input value="{{ $user->first_name }}" type="text" name="first_name" class="form-control" />
                {!! $errors->first('first_name', '<p class="text-danger">:message</p>') !!}
            </div>
            <div class="form-group">
                <label>Last Name*</label>
                <input value="{{ $user->last_name }}" type="text" name="last_name" class="form-control" />
                {!! $errors->first('last_name', '<p class="text-danger">:message</p>') !!}
            </div>
            <div class="form-group">
                <label>Email*</label>
                <input value="{{ $user->email }}" type="email" name="email" class="form-control" />
                {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input value="{{ $user->phone }}" type="text" name="phone_number" class="form-control" />
                <p class="help-block small">Phone Number should be a valid Kenyan number in the format. 0702123456</p>
                {!! $errors->first('phone_number', '<p class="text-danger">:message</p>') !!}
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" />
                <p class="help-block small">Only Enter Password if you want to change it</p>
                {!! $errors->first('password', '<p class="text-danger">:message</p>') !!}
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" />
                <p class="help-block small">Password must be at least 6 characters in length</p>
            </div>
            <button type="submit" class="btn btn-success pull-right">Save</button>
            <div class="clearfix"></div>
        </form>
    </div>
    <div class="col-sm-6">
        <h2>Profile Image</h2>
        <form class="form" action="{{ action('AccountController@postImage') }}" method="post" enctype="multipart/form-data">
            {!! Form::token() !!}
            <div class="form-group">
                <label>Change Profile Image</label>
                <input type="file" class="form-control" name="avatar" />
            </div>
            <button type="submit" class="btn btn-success pull-right">Save</button>
            <div class="clearfix"></div>
        </form>
        
        <p class="well well-lg text-center">
            @if($user->getAvatar())
            <a href="{{ asset('uploads/images/'. $user->getAvatar()->filename) }}">
                <img src="{{ asset('uploads/images/'. $user->getAvatar()->getCropped()) }}" alt="{{ $user->getFullName() }}" class="img-responsive" />
            </a>
            @else
                Currently You have no profile Image
            @endif
        </p>
    </div>
</div>
@stop