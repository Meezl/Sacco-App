@extends('base.backend')

@section('title')
Campaign Preview
@stop

@section('content-title')
Overview of {{ $campaign->title }}
@stop

@section('inner-content')
<p>
    Number of users that will be contacted: {{ $text->users }}
</p>
<h2>Summary</h2>
<ul>
    <li><b>Length: </b>{{ $text->length }}</li>
    <li><b>Cost per Sms: </b>{{ $text->cost }}</li>
    <li><b>Total Cost: </b>{{ $text->total }}</li>
    @if($campaign->help_text)
    <li><b>Contains Help Text</b></li>
    @else
        <li><b>No Help Text</b></li>
    @endif
    
</ul>
<div class="well well-lg">
    {!! nl2br(htmlentities($text->sms)) !!}  
</div>

<br/>

    <a href="{{ action('CampaignController@getContacts', [$campaign->id]) }}" class="btn btn-default pull-left">&larr; Edit</a>    
<div class="clearfix"></div>
<br />

<form class="text-center" method="post" action="{{ action('CampaignController@postSend', [$campaign->id]) }}">
    <button type="submit" class="btn btn-success tooltips" data-toggle="tooltip" title="There is no going back">Send</button>
</form>
@stop