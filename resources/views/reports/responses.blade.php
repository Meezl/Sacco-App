@extends('base.backend')

@section('title')
Responses for - {{ $campaign->getIdString() }}
@stop

@section('content-title')
Responses for - {{ $campaign->getIdString() }}
@stop

@section('inner-content')
<ul class="nav nav-pills">
    <li><a href="{{ action('StatsController@getCampaign', [$campaign->getIdString()]) }}" class="btn btn-default">&larr; Back</a></li>
    <li><a href="{{ action('CampaignController@getIndex') }}">Campaigns</a></li>
</ul>
<p>
    
</p>
<br />
<h3>Actual Text Sent</h3>
<div class="well well-lg">
    {!! nl2br(htmlentities($campaign->getSms())) !!}
</div>

<h3>Responses</h3>
<table class="table table-striped table-hover table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Response</th>
            <th>Submited On</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i < count($resps); $i++)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $resps[$i]->text }}</td>
            <td>{{ $resps[$i]->created_at }}</td>
            <td>
                <a href="{{ action('StatsController@getResponseDetails', [$campaign->getIdString(), $resps[$i]->id]) }}" class="btn btn-xs btn-success tooltips" title="View Contact Details">Details</a>
            </td>
        </tr>
        @endfor
    </tbody>    
</table>
{!! $resps->render() !!}
@stop