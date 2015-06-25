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
                <h6>ID: {{ $campaigns[$i]->id_string }}</h6>
                Status:
                @if( $campaigns[$i]->is_active)
                    @if($campaigns[$i]->is_closed)
                    <span class="text-danger">Closed</span>
                    @else
                    <span class="text-success">Open</span>
                    @endif                
                @else 
                <span class="text-primary">Draft</span>
                @endif
            </td>
            <td>
                @if( $campaigns[$i]->is_active)
                <a href="{{ action('StatsController@getCampaign', [$campaigns[$i]->id_string]) }}" class="btn btn-primary btn-xs">View Stats</a>
                    @if($campaigns[$i]->is_closed)
                    <a href="{{ action('StatsController@getOpen', [$campaigns[$i]->id_string]) }}" class="btn btn-success tooltips btn-xs" title="Campaign Will now be able to receive new responses">Open</a>
                    @else 
                    <a href="{{ action('StatsController@getClose', [$campaigns[$i]->id_string]) }}" class="btn btn-warning btn-xs tooltips" title="New User responses will be ignored">Close</a>
                    @endif
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