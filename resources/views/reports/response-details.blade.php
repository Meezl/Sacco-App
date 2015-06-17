@extends('base.backend')

@section('title')
Response Detail for - {{ $campaign->getIdString() }}
@stop

@section('content-title')
Response Detail for - {{ $campaign->getIdString() }}
@stop

@section('inner-content')
<ul class="nav nav-pills">
    <li><a href="{{ action('StatsController@getResponses', [$campaign->getIdString()]) }}" class="btn btn-default">&larr; Back</a></li>
    <li><a href="{{ action('StatsController@getCampaign', [$campaign->getIdString()]) }}">{{ $campaign->getIdString() }}</a></li>
    <li><a href="{{ action('CampaignController@getIndex') }}">Campaigns</a></li>
</ul>
<p>

</p>
<br />
<div class="row">
    <div class="col-sm-6">
        <h3>Campaign Message</h3>
        <div class="well well-lg">
            {!! nl2br(htmlentities($campaign->getSms())) !!}
        </div> 
    </div>
    <div class="col-sm-6">
        <h3>User Response</h3>
        <div class="well well-lg">
            {{ $response->text }}
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-sm-6">
        <h3>Associated Contact</h3>
        <ul>
            <li><b>Number: </b>{{ $message->sender }}</li>
            <li><b>Name:</b>
                @if(is_null($contact))
                    New Contact
                @else
                {{ $contact->getFullName() }}
                @endif
            </li>
        </ul>        
    </div>
    <div class="col-sm-6">
        <h3>Original Message</h3>
        <p>
            {{ $message->text }}
        </p>
    </div>
</div>
@stop