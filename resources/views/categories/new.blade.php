@extends('base.backend')

@section('title')
 New Category
@stop

@section('content-title')
    @if($category->id)
    Editing Category
    @else
    New Category
    @endif
@stop

@section('inner-content')
<ul class="nav nav-pills">
    <li><a href="{{ action('ContactController@getIndex')}}">Contacts</a></li>
    <li><a href="{{ action('CategoryController@getIndex') }}">Categories</a></li>
    <li><a href="{{ action('ContactController@getNew') }}">New Contact</a></li>
</ul>
<br />
<form id="category-form-new" method="post" action="{{ action('CategoryController@postNew', [$category->id]) }}" class="form">
    {!! Form::token() !!}
    <div class="form-group">
        <label>Title*</label>
        <input value="{{ $category->title }}" type="text" name="title" class="form-control" />
        <p class="help-block small">maximum of 60 characters and a minimum of 2</p>
        {!! $errors->first('title', '<p class="text-danger small">:message</p>') !!}
    </div>
    <div class="form-group">
        <label>Description*</label>
        <textarea class="form-control" name="description" placeholder="enter a short description for this category">{{ $category->description }}</textarea>
        <p class="help-block small">maximum of 200 characters</p>
        {!! $errors->first('description', '<p class="text-danger small">:message</p>') !!}
    </div>
    
    <button type="submit" class="btn btn-success pull-right">Submit</button>
    @if($category->id)
    <a class="btn btn-default" href="{{ action('CategoryController@getDetails', [$category->id])}}">Cancel</a>
    @endif    
    <div class="clearfix"></div>
</form>
@stop