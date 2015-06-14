@extends('base.backend')

@section('title')
Add Contacts
@stop

@section('content-title')
Add Contacts to {{$campaign->title}}
@stop

@section('inner-content')
<p>
    <a href="{{ action('CampaignController@getIndex') }}" class="btn btn-default">Campaigns</a>
</p>
<br />

<p class="small">Showing only contacts not already added</p>
<ul class="nav nav-pills">
    <li><a href="{{ action('CampaignController@getContacts', [$campaign->id]) }}" class="tooltips" data-toggle="tooltip" title="Contacts Already Added to this campaign">Campaign Contacts</a></li>
    <li class="active"><a href="{{ action('CampaignController@getAddContacts', [$campaign->id]) }}" class="tooltips" data-toggle="tooltip" title="Add more Contacts to this campaign">Add</a></li>
</ul>

@if($contacts->isEmpty())
<p class="alert alert-info">Whoops! Looks like there are no contacts to add</p>
@else 


<form method="post" action="{{ action('CampaignController@postAddContacts', [$campaign->id]) }}">
    {!! Form::token() !!}
    <button id="select-all" class="btn btn-xs btn-info">Select all</button>
    <button id="deselect-all" class="btn btn-xs btn-default">Deselect All</button>
    <table class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $c)
            <tr>
                <td><input type="checkbox" name="add[]" value="{{$c->id}}" /></td>
                <td>{{$c->getFullName() }}</td>
                <td>{{ $c->phone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <button class="btn btn-success" type="submit">Add</button>
</form>

@endif
{!! $contacts->render() !!}
@stop 