@extends('base.backend')

@section('title')
Add Contacts
@stop

@section('content-title')
Add Contacts to {{ $cat->title }}
@stop

@section('inner-content')
<p>
    <a href="{{ action('CategoryController@getDetails', [$cat->getUrlTitle()]) }}" class="btn btn-default">&larr; Back</a>
</p>

<form method="post" action="#">
    {!! Form::token() !!}
    <p>
        <button type="button" class="btn btn-info" id="select-all">Select All</button>
    </p>
    <table class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $c)
            <tr>
                <td><input type="checkbox" name="contacts[]" value="{{ $c->id }}" /></td>
                <td>{{ $c->getFullName() }}</td>
                <td>{{ $c->phone }}</td>                
            </tr>
            @endforeach
        </tbody>
    </table>    
        <button type="submit" class="btn btn-success pull-right">Add Selected</button>
</form>

{!! $contacts->render() !!}

@if($contacts->isEmpty())
<p class="alert alert-info">There are no more contacts to add</p>
@endif

@stop