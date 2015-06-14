@extends('base.backend')

@section('content-title')
Members Contacts
@stop

@section('title')
All Contacts
@stop

@section('inner-content')
<p class="small">These are the contacts of your members</p>
<ul class="nav nav-pills">
    <li><a href="{{ action('ContactController@getNew')}}">New Contact</a></li>
    <li><a href="{{ action('CategoryController@getIndex') }}">Categories</a></li>
    <li><a href="{{ action('CategoryController@getNew') }}">New Category</a></li>
</ul>
<br />
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
                <a href="{{ action('ContactController@getDelete', [$contacts[$i]->id]) }}" class="delete btn btn-xs btn-danger">Delete</a>
                <a href="" class="btn btn-xs btn-success">Send Message</a>
            </td>
        </tr>
        @endfor
    </tbody>
</table>

{!! $contacts->render() !!}

@stop

