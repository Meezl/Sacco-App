@extends('base.backend')

@section('title')
My Campaigns
@stop

@section('content-title')
My Campaigns
@stop

@section('inner-content')
<p>
    <a href="{{ action('CampaignController@getNew') }}" class="btn btn-default">New</a>
</p>

<br />
<table class="table table-striped table-hover table-bordered">   
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i < count($campaigns); $i++)
        <tr>
            <td>{{ $i +1 }}</td>
            <td>{{ $campaigns[$i]->title }}</td>
            <td>
                <h5>Message: {{ $campaigns[$i]->message }} </h5>
                <h6>{{ $campaigns[$i]->getExcerpt() }}</h6>
                @if( $campaigns[$i]->is_active)
                Status: <span class="text-success">Active</span>
                @else 
                Status: <span class="text-primary">Draft</span>
                @endif
            </td>
            <td>
                @if( $campaigns[$i]->is_active)
                <a href="{{ action('StatsController@getCampaign', [$campaigns[$i]->id]) }}" class="btn btn-primary btn-xs tooltips" title="Comming Soon">View Stats</a>
                @else
                <a href="{{ action('CampaignController@getNew', [$campaigns[$i]->id]) }}" class="btn btn-primary btn-xs">Edit</a>
                @endif                
                <a href="{{ action('CampaignController@getDelete', [$campaigns[$i]->id]) }}" class="btn btn-danger btn-xs delete">Delete</a>                
            </td>
        </tr>
        @endfor
    </tbody>
</table>
{!! $campaigns->render() !!}

@if( $campaigns->isEmpty() )
<p class="alert alert-info">Whoops! Looks like currently there are no campaigns. You can create one
    <a href="{{ action('CampaignController@getNew') }}">here</a></p>
@endif

@stop