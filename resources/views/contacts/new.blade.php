@extends('base.backend')

@section('title')
New Contact
@stop

@section('content-title')
    @if($contact->id > 0)
        Editing Contact
    @else 
        New Contact
    @endif
@stop

@section('inner-content')
<ul class="nav nav-pills">
    <li><a href="{{ action('ContactController@getIndex')}}">Contacts</a></li>
    <li><a href="{{ action('CategoryController@getIndex') }}">Categories</a></li>
    <li><a href="{{ action('CategoryController@getNew') }}">New Category</a></li>
</ul>
<br />

@if(Session::has('contact-duplicate'))
<p class="alert alert-info">You can view that contact <a href="{{ Session::get('contact-duplicate') }}">here</a></p>
@endif
<form method="post" action="{{ action('ContactController@postNew', [$contact->id]) }}" class="form" id="contact-new-form">
    {!! Form::token() !!}
    <div class="form-group">
        <label>First Name*</label>
        <input value="{{ $contact->first_name }}" type="text" name="first_name" class="form-control" />
        {!! $errors->first('first_name', '<p class="text-danger small">:message</p>') !!}
    </div>
    <div class="form-group">
        <label>Last Name*</label>
        <input value="{{ $contact->last_name }}" type="text" name="last_name" class="form-control" />
        {!! $errors->first('last_name', '<p class="text-danger small">:message</p>') !!}
    </div>
    <div class="form-group">
        <label>Phone Number*</label>
        <input value="{{ $contact->phone }}" type="text" name="phone_number" class="form-control" />
        <p class="help-block small">Phone Number should be a valid Kenyan numbers in the format. 0702123456</p>
        {!! $errors->first('phone_number', '<p class="text-danger small">:message</p>') !!}
    </div>
    <div class="form-group">
        <label>Account No</label>
        <input value="{{ $contact->account_no }}" type="text" name="account_no"class="form-control" />
        {!! $errors->first('account_no', '<p class="text-danger small">:message</p>') !!}
    </div>
    <a href="{{ action('ContactController@getIndex')}}" class="btn btn-default">Cancel</a>
    <button class="btn btn-success pull-right" type="submit">Submit</button>
    <div class="clearfix"></div>
    <p class="help-block small">All Fields Marked with * are required</p>
</form>
@stop