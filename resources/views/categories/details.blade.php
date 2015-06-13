@extends('base.backend')

@section('title')
{{$category->title}}
@stop

@section('content-title')
{{$category->title}}
@stop

@section('inner-content')
<ul class="nav nav-pills">
    <li><a href="{{ action('CategoryController@getIndex') }}">&larr; Back</a></li>
    <li><a href="{{ action('CategoryController@getNew', [$category->getUrlTitle()]) }}">Edit Category</a></li>
    <li><a href="{{ action('CategoryController@getAddContacts', [$category->id])}}">Add Contacts</a></li>
    <li><a href="{{ action('CategoryController@getNew') }}">New Category</a></li>
    <li><a href="{{ action('CategoryController@getDelete', [$category->id]) }}" class="text-danger delete">Delete</a></li>
</ul>
<h3>Description:</h3>
<p>
    {{ $category->description }}
</p>
<h3>Associated Contacts:</h3>
<table class="table table-striped table-hover table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone Number</th>
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i < count($contacts); $i++)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $contacts[$i]->getFullName() }}</td>
            <td>{{ $contacts[$i]->phone }}</td>
            <td>
                <a href="{{ action('ContactController@getNew', [$contacts[$i]->id]) }}" class="btn btn-xs btn-primary">Edit</a>
                <a href="{{ action('CategoryController@getRemoveContact', [$category->id, $contacts[$i]->id]) }}" class="delete btn btn-xs btn-danger tooltips" title="Remove Contact From Category" data-placement="bottom">Remove</a>
                <a href="" class="btn btn-xs btn-success">Send Message</a>
            </td>
        </tr>
        @endfor
    </tbody>
</table>
{!! $contacts->render() !!}

@if($contacts->isEmpty())
<p class="alert alert-info">There are no contacts in this category</p>
@endif
@stop