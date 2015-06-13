@extends('base.backend')

@section('title')
Contact Categories
@stop

@section('content-title')
Contact Categories
@stop

@section('inner-content')
<p class="small">
    Contact Categories help you organize your contacts into groups then associate a campaign to only a subset of your contacts instead of bothering everyone
</p>
<p>
    <a class="btn btn-default tooltips" href="{{ action('CategoryController@getNew') }}" title="Create a new Category" data-toggle="tooltip" data-placement="bottom">New</a>
</p>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i < count($categories); $i++)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>
                <a href="{{ action('CategoryController@getDetails', [$categories[$i]->getUrlTitle()]) }}">{{ $categories[$i]->title }}</a>
            </td>
            <td>{{ $categories[$i]->getExcerpt() }}</td>
            <td>
                <a href="{{ action('CategoryController@getNew', [$categories[$i]->id]) }}" class="btn btn-default btn-xs">Edit</a>
                <a href="{{ action('CategoryController@getAddContacts', [$categories[$i]->id]) }}" class="btn btn-primary btn-xs">Add Contacts</a>
                <a href="{{ action('CategoryController@getDelete', [$categories[$i]->id]) }}" class="btn btn-danger btn-xs delete">Delete</a>                
                <a href="{{ action('CategoryController@getDetails', [$categories[$i]->getUrlTitle()]) }}" class="btn btn-success btn-xs">View</a>
            </td>
        </tr>
        @endfor
    </tbody>
</table>

{!! $categories->render() !!}

@if($categories->isEmpty())
<p class="alert alert-info">Whoops! It looks like you have no categories. Get Started by creating one <a href="{{ action('CategoryController@getNew') }}">here</a></p>
@endif
@stop